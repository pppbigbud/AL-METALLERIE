<?php
/**
 * Optimisation Automatique des Images pour Performance Mobile
 * 
 * Crée des versions optimisées des images pour le mobile :
 * - Slideshow: 480x640 (portrait mobile)
 * - Cards formations: 200x134 (dimensions affichées)
 * - Logo: 100x100
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe d'optimisation des images
 */
class Almetal_Image_Auto_Optimizer {
    
    /**
     * Qualité WebP (0-100)
     */
    const WEBP_QUALITY = 75;
    
    /**
     * Qualité JPEG (0-100)
     */
    const JPEG_QUALITY = 80;
    
    /**
     * Tailles optimisées pour mobile
     */
    const SIZES = array(
        'slideshow-mobile' => array(
            'width' => 480,
            'height' => 640,
            'crop' => true,
        ),
        'card-mobile' => array(
            'width' => 200,
            'height' => 134,
            'crop' => true,
        ),
        'logo-mobile' => array(
            'width' => 100,
            'height' => 100,
            'crop' => false,
        ),
    );
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Ajouter le menu admin
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // AJAX pour l'optimisation
        add_action('wp_ajax_almetal_optimize_images', array($this, 'ajax_optimize_images'));
        
        // Optimiser automatiquement lors de l'upload
        add_filter('wp_generate_attachment_metadata', array($this, 'auto_optimize_on_upload'), 10, 2);
    }
    
    /**
     * Ajouter le menu admin
     */
    public function add_admin_menu() {
        add_submenu_page(
            'tools.php',
            'Optimisation Images Mobile',
            'Optim. Images Mobile',
            'manage_options',
            'almetal-image-optimizer',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Page d'administration
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>🖼️ Optimisation des Images Mobile</h1>
            
            <div class="card" style="max-width: 800px; padding: 20px;">
                <h2>Pourquoi optimiser ?</h2>
                <p>Les images trop grandes ralentissent le chargement sur mobile. Cette page permet de :</p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><strong>Slideshow</strong> : Créer des versions 480x640px (économie ~200KB par image)</li>
                    <li><strong>Cards Formations</strong> : Créer des versions 200x134px (économie ~50KB par image)</li>
                    <li><strong>Logo</strong> : Créer une version 100x100px</li>
                </ul>
                
                <h3>État actuel</h3>
                <?php $this->display_current_status(); ?>
                
                <h3>Actions</h3>
                <p>
                    <button id="optimize-slideshow" class="button button-primary button-large">
                        🎠 Optimiser les images du Slideshow
                    </button>
                </p>
                <p>
                    <button id="optimize-formations" class="button button-secondary button-large">
                        📚 Optimiser les images des Formations
                    </button>
                </p>
                <p>
                    <button id="optimize-logo" class="button button-secondary">
                        🏷️ Optimiser le Logo
                    </button>
                </p>
                
                <div id="optimization-results" style="margin-top: 20px;"></div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            function optimizeImages(type) {
                var $results = $('#optimization-results');
                $results.html('<p>⏳ Optimisation en cours...</p>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'almetal_optimize_images',
                        type: type,
                        nonce: '<?php echo wp_create_nonce('almetal_optimize_images'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $results.html('<div class="notice notice-success"><p>✅ ' + response.data.message + '</p></div>');
                            if (response.data.details) {
                                $results.append('<pre>' + response.data.details + '</pre>');
                            }
                        } else {
                            $results.html('<div class="notice notice-error"><p>❌ ' + response.data + '</p></div>');
                        }
                    },
                    error: function() {
                        $results.html('<div class="notice notice-error"><p>❌ Erreur de connexion</p></div>');
                    }
                });
            }
            
            $('#optimize-slideshow').click(function() { optimizeImages('slideshow'); });
            $('#optimize-formations').click(function() { optimizeImages('formations'); });
            $('#optimize-logo').click(function() { optimizeImages('logo'); });
        });
        </script>
        <?php
    }
    
    /**
     * Afficher l'état actuel des images
     */
    private function display_current_status() {
        // Vérifier les images du slideshow
        $slides = Almetal_Slideshow_Admin::get_slides();
        $slideshow_count = count(array_filter($slides, function($s) { return !empty($s['image']); }));
        
        // Vérifier les images des formations
        $cards = Almetal_Formations_Cards_Admin::get_cards();
        $formations_count = count(array_filter($cards, function($c) { return !empty($c['image']); }));
        
        echo '<table class="widefat" style="max-width: 500px;">';
        echo '<tr><th>Type</th><th>Images</th><th>Optimisées</th></tr>';
        echo '<tr><td>Slideshow</td><td>' . $slideshow_count . '</td><td>' . $this->count_optimized('slideshow') . '</td></tr>';
        echo '<tr><td>Formations</td><td>' . $formations_count . '</td><td>' . $this->count_optimized('formations') . '</td></tr>';
        echo '</table>';
    }
    
    /**
     * Compter les images optimisées
     */
    private function count_optimized($type) {
        $upload_dir = wp_upload_dir();
        $optimized_dir = $upload_dir['basedir'] . '/optimized-mobile/';
        
        if (!is_dir($optimized_dir)) {
            return 0;
        }
        
        $pattern = $optimized_dir . $type . '-*.webp';
        return count(glob($pattern));
    }
    
    /**
     * AJAX: Optimiser les images
     */
    public function ajax_optimize_images() {
        check_ajax_referer('almetal_optimize_images', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permissions insuffisantes');
        }
        
        $type = sanitize_text_field($_POST['type'] ?? '');
        $results = array();
        
        switch ($type) {
            case 'slideshow':
                $results = $this->optimize_slideshow_images();
                break;
            case 'formations':
                $results = $this->optimize_formations_images();
                break;
            case 'logo':
                $results = $this->optimize_logo();
                break;
            default:
                wp_send_json_error('Type inconnu');
        }
        
        wp_send_json_success($results);
    }
    
    /**
     * Optimiser les images du slideshow
     */
    private function optimize_slideshow_images() {
        $slides = Almetal_Slideshow_Admin::get_slides();
        $optimized = 0;
        $details = array();
        
        foreach ($slides as $index => $slide) {
            if (empty($slide['image'])) continue;
            
            $result = $this->optimize_image(
                $slide['image'],
                'slideshow-' . $index,
                self::SIZES['slideshow-mobile']
            );
            
            if ($result['success']) {
                $optimized++;
                $details[] = "✓ Slide " . ($index + 1) . ": " . $result['saved'] . " économisés";
            } else {
                $details[] = "✗ Slide " . ($index + 1) . ": " . $result['error'];
            }
        }
        
        return array(
            'message' => "$optimized image(s) optimisée(s) pour le slideshow",
            'details' => implode("\n", $details)
        );
    }
    
    /**
     * Optimiser les images des formations
     */
    private function optimize_formations_images() {
        $cards = Almetal_Formations_Cards_Admin::get_cards();
        $optimized = 0;
        $details = array();
        
        foreach ($cards as $index => $card) {
            if (empty($card['image'])) continue;
            
            $result = $this->optimize_image(
                $card['image'],
                'formation-' . $index,
                self::SIZES['card-mobile']
            );
            
            if ($result['success']) {
                $optimized++;
                $details[] = "✓ Card " . ($index + 1) . ": " . $result['saved'] . " économisés";
            } else {
                $details[] = "✗ Card " . ($index + 1) . ": " . $result['error'];
            }
        }
        
        return array(
            'message' => "$optimized image(s) optimisée(s) pour les formations",
            'details' => implode("\n", $details)
        );
    }
    
    /**
     * Optimiser le logo
     */
    private function optimize_logo() {
        $logo_path = get_template_directory() . '/assets/images/logo.webp';
        
        if (!file_exists($logo_path)) {
            $logo_path = get_template_directory() . '/assets/images/logo.png';
        }
        
        if (!file_exists($logo_path)) {
            return array(
                'message' => 'Logo non trouvé',
                'details' => ''
            );
        }
        
        $result = $this->optimize_image(
            get_template_directory_uri() . '/assets/images/logo.webp',
            'logo',
            self::SIZES['logo-mobile']
        );
        
        return array(
            'message' => $result['success'] ? 'Logo optimisé' : 'Erreur: ' . $result['error'],
            'details' => $result['success'] ? $result['saved'] . ' économisés' : ''
        );
    }
    
    /**
     * Optimiser une image
     */
    private function optimize_image($image_url, $name, $size) {
        // Convertir l'URL en chemin local
        $upload_dir = wp_upload_dir();
        $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
        
        // Si c'est une image du thème
        if (strpos($image_url, get_template_directory_uri()) !== false) {
            $image_path = str_replace(get_template_directory_uri(), get_template_directory(), $image_url);
        }
        
        if (!file_exists($image_path)) {
            return array('success' => false, 'error' => 'Fichier non trouvé: ' . $image_path);
        }
        
        // Créer le dossier de destination
        $optimized_dir = $upload_dir['basedir'] . '/optimized-mobile/';
        if (!is_dir($optimized_dir)) {
            wp_mkdir_p($optimized_dir);
        }
        
        // Charger l'image
        $image_info = getimagesize($image_path);
        if (!$image_info) {
            return array('success' => false, 'error' => 'Image invalide');
        }
        
        $original_size = filesize($image_path);
        $mime = $image_info['mime'];
        
        // Créer l'image source
        switch ($mime) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($image_path);
                break;
            case 'image/png':
                $source = imagecreatefrompng($image_path);
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($image_path);
                break;
            default:
                return array('success' => false, 'error' => 'Format non supporté: ' . $mime);
        }
        
        if (!$source) {
            return array('success' => false, 'error' => 'Impossible de charger l\'image');
        }
        
        // Dimensions originales
        $orig_width = imagesx($source);
        $orig_height = imagesy($source);
        
        // Calculer les nouvelles dimensions
        $new_width = $size['width'];
        $new_height = $size['height'];
        
        if ($size['crop']) {
            // Crop centré
            $ratio_orig = $orig_width / $orig_height;
            $ratio_new = $new_width / $new_height;
            
            if ($ratio_orig > $ratio_new) {
                $crop_width = $orig_height * $ratio_new;
                $crop_height = $orig_height;
                $crop_x = ($orig_width - $crop_width) / 2;
                $crop_y = 0;
            } else {
                $crop_width = $orig_width;
                $crop_height = $orig_width / $ratio_new;
                $crop_x = 0;
                $crop_y = ($orig_height - $crop_height) / 2;
            }
        } else {
            // Redimensionnement proportionnel
            $ratio = min($new_width / $orig_width, $new_height / $orig_height);
            $new_width = round($orig_width * $ratio);
            $new_height = round($orig_height * $ratio);
            $crop_x = 0;
            $crop_y = 0;
            $crop_width = $orig_width;
            $crop_height = $orig_height;
        }
        
        // Créer l'image de destination
        $dest = imagecreatetruecolor($new_width, $new_height);
        
        // Préserver la transparence pour PNG
        if ($mime === 'image/png') {
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
        }
        
        // Redimensionner
        imagecopyresampled(
            $dest, $source,
            0, 0, $crop_x, $crop_y,
            $new_width, $new_height,
            $crop_width, $crop_height
        );
        
        // Sauvegarder en WebP
        $output_path = $optimized_dir . $name . '.webp';
        $success = imagewebp($dest, $output_path, self::WEBP_QUALITY);
        
        // Libérer la mémoire
        imagedestroy($source);
        imagedestroy($dest);
        
        if (!$success) {
            return array('success' => false, 'error' => 'Erreur lors de la sauvegarde');
        }
        
        $new_size = filesize($output_path);
        $saved = $original_size - $new_size;
        
        return array(
            'success' => true,
            'original_size' => $original_size,
            'new_size' => $new_size,
            'saved' => $this->format_bytes($saved),
            'path' => $output_path
        );
    }
    
    /**
     * Formater les bytes
     */
    private function format_bytes($bytes) {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
    
    /**
     * Optimiser automatiquement lors de l'upload
     */
    public function auto_optimize_on_upload($metadata, $attachment_id) {
        // Vérifier si c'est une image
        $mime = get_post_mime_type($attachment_id);
        if (strpos($mime, 'image/') !== 0) {
            return $metadata;
        }
        
        // Créer une version mobile optimisée
        $file = get_attached_file($attachment_id);
        if ($file && file_exists($file)) {
            $upload_dir = wp_upload_dir();
            $optimized_dir = $upload_dir['basedir'] . '/optimized-mobile/';
            
            if (!is_dir($optimized_dir)) {
                wp_mkdir_p($optimized_dir);
            }
            
            // Créer une version mobile (480px de large)
            $this->create_mobile_version($file, $optimized_dir, $attachment_id);
        }
        
        return $metadata;
    }
    
    /**
     * Créer une version mobile d'une image
     */
    private function create_mobile_version($file, $output_dir, $attachment_id) {
        $image = wp_get_image_editor($file);
        
        if (is_wp_error($image)) {
            return false;
        }
        
        // Redimensionner à 480px de large max
        $image->resize(480, 640, false);
        $image->set_quality(self::WEBP_QUALITY);
        
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $output_path = $output_dir . 'upload-' . $attachment_id . '-mobile.webp';
        
        $result = $image->save($output_path, 'image/webp');
        
        return !is_wp_error($result);
    }
    
    /**
     * Obtenir l'URL de l'image optimisée
     */
    public static function get_optimized_url($original_url, $type = 'slideshow', $index = 0) {
        $upload_dir = wp_upload_dir();
        $optimized_file = $upload_dir['basedir'] . '/optimized-mobile/' . $type . '-' . $index . '.webp';
        
        if (file_exists($optimized_file)) {
            return $upload_dir['baseurl'] . '/optimized-mobile/' . $type . '-' . $index . '.webp';
        }
        
        return $original_url;
    }
}

// Initialiser
new Almetal_Image_Auto_Optimizer();

/**
 * Helper pour obtenir l'URL optimisée
 */
function almetal_get_optimized_image($url, $type = 'slideshow', $index = 0) {
    return Almetal_Image_Auto_Optimizer::get_optimized_url($url, $type, $index);
}
