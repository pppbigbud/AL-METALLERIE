<?php
/**
 * Classe de gestion des images
 */
class ACSP_Image_Manager {
    
    /**
     * Obtenir ou créer une image
     */
    public function get_or_create_image($topic, $keyword) {
        global $wpdb;
        
        $table_images = $wpdb->prefix . 'acsp_images';
        
        // Chercher une image existante non utilisée récemment
        $image = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM $table_images 
            WHERE category = %s 
            ORDER BY used_count ASC, RAND() 
            LIMIT 1
        ", $this->get_category_from_keyword($keyword)));
        
        if ($image) {
            // Télécharger et créer l'attachment WordPress
            $attachment_id = $this->download_and_create_attachment($image->url, $image->alt_text);
            
            if ($attachment_id) {
                // Mettre à jour le compteur d'utilisation
                $wpdb->update(
                    $table_images,
                    ['used_count' => $image->used_count + 1],
                    ['id' => $image->id]
                );
                
                return $attachment_id;
            }
        }
        
        // Si aucune image trouvée, en générer une
        return $this->generate_placeholder_image($topic, $keyword);
    }
    
    /**
     * Télécharger et créer un attachment WordPress
     */
    private function download_and_create_attachment($image_url, $alt_text = '') {
        // Télécharger l'image
        $response = wp_remote_get($image_url);
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }
        
        $image_data = wp_remote_retrieve_body($response);
        $filename = basename(parse_url($image_url, PHP_URL_PATH));
        
        // Vérifier le type MIME
        $filetype = wp_check_filetype($filename);
        if ($filetype['type'] === false) {
            $filename = uniqid() . '.jpg';
            $filetype = wp_check_filetype($filename);
        }
        
        // Upload dans WordPress
        $upload = wp_upload_bits($filename, null, $image_data);
        
        if (!empty($upload['error'])) {
            return false;
        }
        
        // Créer l'attachment
        $attachment = [
            'post_mime_type' => $filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        ];
        
        $attachment_id = wp_insert_attachment($attachment, $upload['file']);
        
        if ($attachment_id) {
            // Générer les thumbnails
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
            
            // Ajouter le texte ALT
            if ($alt_text) {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
            }
            
            return $attachment_id;
        }
        
        return false;
    }
    
    /**
     * Générer une image de remplacement
     */
    private function generate_placeholder_image($topic, $keyword) {
        // Dimensions
        $width = (int) get_option('acsp_image_width', 1200);
        $height = (int) get_option('acsp_image_height', 630);
        
        // Créer l'image
        $image = imagecreatetruecolor($width, $height);
        
        if (!$image) {
            return false;
        }
        
        // Couleurs
        $bg_color = imagecolorallocate($image, 70, 80, 90); // Gris métallique
        $text_color = imagecolorallocate($image, 255, 255, 255); // Blanc
        $accent_color = imagecolorallocate($image, 240, 139, 24); // Orange AL Métallerie
        
        // Fond
        imagefill($image, 0, 0, $bg_color);
        
        // Ajouter un dégradé subtil
        for ($i = 0; $i < $height; $i++) {
            $alpha = $i / $height * 30;
            $line_color = imagecolorallocatealpha($image, 255, 255, 255, $alpha);
            imageline($image, 0, $i, $width, $i, $line_color);
        }
        
        // Logo ou motif décoratif
        $this->add_logo_pattern($image, $width, $height, $accent_color);
        
        // Titre de l'article
        $title = $topic->topic;
        $this->add_text_to_image($image, $title, $width, $height, 48, $text_color);
        
        // Sous-titre
        $subtitle = 'AL Métallerie & Soudure - Thiers';
        $this->add_text_to_image($image, $subtitle, $width, $height, 24, $text_color, true);
        
        // Sauvegarder temporairement
        $filename = 'acsp-' . uniqid() . '.jpg';
        $upload_dir = wp_upload_dir();
        $filepath = $upload_dir['path'] . '/' . $filename;
        
        imagejpeg($image, $filepath, 90);
        imagedestroy($image);
        
        // Créer l'attachment
        $attachment = [
            'post_mime_type' => 'image/jpeg',
            'post_title' => $title,
            'post_content' => '',
            'post_status' => 'inherit'
        ];
        
        $attachment_id = wp_insert_attachment($attachment, $filepath);
        
        if ($attachment_id) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $filepath);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
            
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $title);
            
            return $attachment_id;
        }
        
        return false;
    }
    
    /**
     * Ajouter un motif logo
     */
    private function add_logo_pattern($image, $width, $height, $color) {
        // Motif simple de cercles métalliques
        for ($x = 50; $x < $width; $x += 150) {
            for ($y = 50; $y < $height; $y += 150) {
                imagefilledellipse($image, $x, $y, 80, 80, $color);
                imagefilledellipse($image, $x, $y, 60, 60, imagecolorallocate($image, 70, 80, 90));
                imagefilledellipse($image, $x, $y, 40, 40, $color);
            }
        }
    }
    
    /**
     * Ajouter du texte à l'image
     */
    private function add_text_to_image($image, $text, $width, $height, $font_size, $color, $is_subtitle = false) {
        // Utiliser une police système
        $font = 5; // Police GD intégrée
        
        // Centrer le texte
        $text_width = imagefontwidth($font) * strlen($text);
        $text_height = imagefontheight($font);
        
        if ($is_subtitle) {
            $x = ($width - $text_width) / 2;
            $y = $height - 100;
        } else {
            $x = ($width - $text_width) / 2;
            $y = ($height - $text_height) / 2 - 50;
        }
        
        // Ajouter une ombre
        imagestring($image, $font, $x + 2, $y + 2, $text, imagecolorallocate($image, 0, 0, 0));
        // Ajouter le texte
        imagestring($image, $font, $x, $y, $text, $color);
    }
    
    /**
     * Obtenir la catégorie à partir du mot-clé
     */
    private function get_category_from_keyword($keyword) {
        $category_map = [
            'portail' => 'portails',
            'garde corps' => 'garde_corps',
            'escalier' => 'escaliers',
            'pergola' => 'pergolas',
            'verriere' => 'verrieres',
            'mobilier' => 'mobilier',
            'soudure' => 'soudure',
            'formation' => 'formations'
        ];
        
        foreach ($category_map as $key => $category) {
            if (strpos($keyword->keyword, $key) !== false) {
                return $category;
            }
        }
        
        return 'general';
    }
    
    /**
     * Importer des images depuis Unsplash
     */
    public function import_from_unsplash($query, $count = 5) {
        global $wpdb;
        
        $table_images = $wpdb->prefix . 'acsp_images';
        
        // URL de l'API Unsplash (usage gratuit sans clé)
        $api_url = "https://source.unsplash.com/featured/?" . http_build_query([
            $query,
            '1920x1080'
        ]);
        
        // Pour l'instant, on utilise des URLs prédéfinies
        $unsplash_images = [
            'https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=1920',
            'https://images.unsplash.com/photo-1629553844753-0f4a1c1b4c5a?q=80&w=1920',
            'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1920',
            'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=1920',
            'https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=1920'
        ];
        
        $imported = 0;
        for ($i = 0; $i < min($count, count($unsplash_images)); $i++) {
            // Vérifier si l'image existe déjà
            $exists = $wpdb->get_var($wpdb->prepare("
                SELECT id FROM $table_images 
                WHERE url = %s
            ", $unsplash_images[$i]));
            
            if (!$exists) {
                $wpdb->insert(
                    $table_images,
                    [
                        'url' => $unsplash_images[$i],
                        'source' => 'unsplash',
                        'alt_text' => ucfirst($query) . ' - AL Métallerie',
                        'category' => $this->get_category_from_keyword((object) ['keyword' => $query]),
                        'created_at' => current_time('mysql')
                    ]
                );
                $imported++;
            }
        }
        
        return $imported;
    }
}
