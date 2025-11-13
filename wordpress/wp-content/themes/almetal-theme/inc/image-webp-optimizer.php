<?php
/**
 * Optimiseur d'Images WebP
 * Convertit automatiquement les images en WebP avec compression
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

class ALMetal_Image_WebP_Optimizer {
    
    private $max_width = 1920;
    private $max_height = 1080;
    private $quality = 80;
    
    public function __construct() {
        // Hook pour optimiser les images lors de l'upload
        add_filter('wp_handle_upload', array($this, 'optimize_uploaded_image'), 10, 2);
        
        // Hook pour générer les tailles WebP
        add_filter('wp_generate_attachment_metadata', array($this, 'generate_webp_versions'), 10, 2);
        
        // Ajouter le support WebP dans WordPress
        add_filter('mime_types', array($this, 'add_webp_mime_type'));
        add_filter('file_is_displayable_image', array($this, 'webp_is_displayable'), 10, 2);
    }
    
    /**
     * Ajouter le type MIME WebP
     */
    public function add_webp_mime_type($mimes) {
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }
    
    /**
     * Permettre l'affichage des images WebP
     */
    public function webp_is_displayable($result, $path) {
        if (strpos($path, '.webp') !== false) {
            return true;
        }
        return $result;
    }
    
    /**
     * Optimiser l'image uploadée
     */
    public function optimize_uploaded_image($upload, $context) {
        // Vérifier que c'est une image
        if (strpos($upload['type'], 'image') === false) {
            return $upload;
        }
        
        $file_path = $upload['file'];
        $file_type = $upload['type'];
        
        // Vérifier que GD est disponible
        if (!function_exists('imagecreatefromjpeg')) {
            return $upload;
        }
        
        // Charger l'image selon son type
        $image = null;
        switch ($file_type) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($file_path);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($file_path);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($file_path);
                break;
            default:
                return $upload;
        }
        
        if (!$image) {
            return $upload;
        }
        
        // Obtenir les dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Calculer les nouvelles dimensions si nécessaire
        $new_width = $width;
        $new_height = $height;
        
        if ($width > $this->max_width || $height > $this->max_height) {
            $ratio = min($this->max_width / $width, $this->max_height / $height);
            $new_width = round($width * $ratio);
            $new_height = round($height * $ratio);
        }
        
        // Créer une nouvelle image redimensionnée
        $new_image = imagecreatetruecolor($new_width, $new_height);
        
        // Préserver la transparence pour PNG
        if ($file_type === 'image/png') {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
        }
        
        // Redimensionner
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        // Générer le nom du fichier WebP
        $webp_path = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $file_path);
        
        // Sauvegarder en WebP
        if (function_exists('imagewebp')) {
            imagewebp($new_image, $webp_path, $this->quality);
            
            // Remplacer le fichier original par le WebP
            @unlink($file_path);
            
            // Mettre à jour les informations de l'upload
            $upload['file'] = $webp_path;
            $upload['url'] = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $upload['url']);
            $upload['type'] = 'image/webp';
        }
        
        // Libérer la mémoire
        imagedestroy($image);
        imagedestroy($new_image);
        
        return $upload;
    }
    
    /**
     * Générer les versions WebP pour toutes les tailles
     */
    public function generate_webp_versions($metadata, $attachment_id) {
        $file_path = get_attached_file($attachment_id);
        
        if (!$file_path || !file_exists($file_path)) {
            return $metadata;
        }
        
        // Vérifier que c'est une image WebP
        if (strpos($file_path, '.webp') === false) {
            return $metadata;
        }
        
        // Les tailles sont déjà générées par WordPress
        // On s'assure juste que tout est en WebP
        
        return $metadata;
    }
    
    /**
     * Obtenir les informations d'optimisation
     */
    public function get_optimization_info() {
        return array(
            'max_width' => $this->max_width,
            'max_height' => $this->max_height,
            'quality' => $this->quality,
            'format' => 'WebP'
        );
    }
}

// Initialiser
new ALMetal_Image_WebP_Optimizer();
