<?php
/**
 * Optimiseur Automatique d'Images
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

class ALMetal_Image_Optimizer {
    
    public function __construct() {
        // Hooks pour l'upload d'images
        add_filter('wp_handle_upload_prefilter', array($this, 'rename_uploaded_file'));
        add_filter('wp_generate_attachment_metadata', array($this, 'add_image_metadata'), 10, 2);
        add_action('add_attachment', array($this, 'set_image_alt_text'));
    }
    
    /**
     * Renommer automatiquement les fichiers uploadés
     */
    public function rename_uploaded_file($file) {
        // Vérifier si c'est une image
        if (strpos($file['type'], 'image') === false) {
            return $file;
        }
        
        // Récupérer le post parent (réalisation)
        $post_id = isset($_REQUEST['post_id']) ? intval($_REQUEST['post_id']) : 0;
        
        if ($post_id && get_post_type($post_id) === 'realisation') {
            $new_filename = $this->generate_seo_filename($post_id, $file['name']);
            
            if ($new_filename) {
                $file['name'] = $new_filename;
            }
        }
        
        return $file;
    }
    
    /**
     * Générer un nom de fichier SEO
     */
    private function generate_seo_filename($post_id, $original_filename) {
        // Récupérer les informations de la réalisation
        $post = get_post($post_id);
        $lieu = get_post_meta($post_id, '_almetal_lieu', true);
        $date = get_post_meta($post_id, '_almetal_date_realisation', true);
        $types = wp_get_post_terms($post_id, 'type_realisation');
        
        // Construire le nom
        $parts = array();
        
        // Type de réalisation
        if (!empty($types) && !is_wp_error($types)) {
            $type_slug = sanitize_title($types[0]->name);
            $parts[] = $type_slug;
        }
        
        // Matériau (si dans le titre)
        $title_lower = strtolower($post->post_title);
        if (strpos($title_lower, 'acier') !== false) {
            $parts[] = 'acier';
        } elseif (strpos($title_lower, 'inox') !== false) {
            $parts[] = 'inox';
        } elseif (strpos($title_lower, 'aluminium') !== false) {
            $parts[] = 'aluminium';
        }
        
        // Lieu
        if (!empty($lieu)) {
            $lieu_slug = sanitize_title($lieu);
            $parts[] = $lieu_slug;
        }
        
        // Année
        if (!empty($date)) {
            $year = date('Y', strtotime($date));
            $parts[] = $year;
        } else {
            $parts[] = date('Y');
        }
        
        // Identifiant unique (timestamp + random) pour éviter les doublons
        $unique_id = time() . '-' . wp_rand(1000, 9999);
        $parts[] = $unique_id;
        
        // Extension
        $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
        
        // Construire le nom final
        $new_filename = implode('-', $parts) . '.' . $extension;
        
        // Nettoyer
        $new_filename = sanitize_file_name($new_filename);
        
        return $new_filename;
    }
    
    /**
     * Ajouter les métadonnées d'image
     */
    public function add_image_metadata($metadata, $attachment_id) {
        $post_id = wp_get_post_parent_id($attachment_id);
        
        if ($post_id && get_post_type($post_id) === 'realisation') {
            // Mettre à jour le titre de l'image
            $image_title = $this->generate_image_title($post_id, $attachment_id);
            
            wp_update_post(array(
                'ID' => $attachment_id,
                'post_title' => $image_title,
                'post_excerpt' => $this->generate_image_caption($post_id),
                'post_content' => $this->generate_image_description($post_id)
            ));
        }
        
        return $metadata;
    }
    
    /**
     * Définir automatiquement le texte alt
     */
    public function set_image_alt_text($attachment_id) {
        $post_id = wp_get_post_parent_id($attachment_id);
        
        if ($post_id && get_post_type($post_id) === 'realisation') {
            $alt_text = $this->generate_alt_text($post_id, $attachment_id);
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
        }
    }
    
    /**
     * Générer le titre de l'image
     */
    private function generate_image_title($post_id, $attachment_id) {
        $post = get_post($post_id);
        $types = wp_get_post_terms($post_id, 'type_realisation');
        $type_name = !empty($types) && !is_wp_error($types) ? $types[0]->name : 'Métallerie';
        
        // Compter les images pour numéroter
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_parent' => $post_id,
            'orderby' => 'ID',
            'order' => 'ASC'
        ));
        
        $image_number = 1;
        foreach ($attachments as $index => $attachment) {
            if ($attachment->ID == $attachment_id) {
                $image_number = $index + 1;
                break;
            }
        }
        
        return $type_name . ' ' . $post->post_title . ' - Photo ' . $image_number;
    }
    
    /**
     * Générer la légende de l'image
     */
    private function generate_image_caption($post_id) {
        $post = get_post($post_id);
        $lieu = get_post_meta($post_id, '_almetal_lieu', true);
        
        $caption = $post->post_title;
        if (!empty($lieu)) {
            $caption .= ' à ' . $lieu;
        }
        $caption .= ' - AL Métallerie';
        
        return $caption;
    }
    
    /**
     * Générer la description de l'image
     */
    private function generate_image_description($post_id) {
        $post = get_post($post_id);
        $types = wp_get_post_terms($post_id, 'type_realisation');
        $lieu = get_post_meta($post_id, '_almetal_lieu', true);
        $date = get_post_meta($post_id, '_almetal_date_realisation', true);
        
        $description = 'Réalisation de ';
        
        if (!empty($types) && !is_wp_error($types)) {
            $description .= strtolower($types[0]->name) . ' ';
        }
        
        $description .= '« ' . $post->post_title . ' »';
        
        if (!empty($lieu)) {
            $description .= ' à ' . $lieu;
        }
        
        if (!empty($date)) {
            $description .= ' en ' . date_i18n('F Y', strtotime($date));
        }
        
        $description .= '. Travaux de métallerie sur-mesure réalisés par AL Métallerie.';
        
        return $description;
    }
    
    /**
     * Générer le texte alt SEO
     */
    private function generate_alt_text($post_id, $attachment_id) {
        $post = get_post($post_id);
        $types = wp_get_post_terms($post_id, 'type_realisation');
        $lieu = get_post_meta($post_id, '_almetal_lieu', true);
        
        $alt = '';
        
        // Type
        if (!empty($types) && !is_wp_error($types)) {
            $alt .= ucfirst($types[0]->name) . ' ';
        }
        
        // Matériau (si dans le titre)
        $title_lower = strtolower($post->post_title);
        if (strpos($title_lower, 'acier') !== false) {
            $alt .= 'en acier ';
        } elseif (strpos($title_lower, 'inox') !== false) {
            $alt .= 'en inox ';
        } elseif (strpos($title_lower, 'aluminium') !== false) {
            $alt .= 'en aluminium ';
        }
        
        // Lieu
        if (!empty($lieu)) {
            $alt .= $lieu . ' ';
        }
        
        $alt .= '- AL Métallerie';
        
        // Limiter à 125 caractères (recommandation SEO)
        if (strlen($alt) > 125) {
            $alt = substr($alt, 0, 122) . '...';
        }
        
        return $alt;
    }
}

// Initialiser
new ALMetal_Image_Optimizer();
