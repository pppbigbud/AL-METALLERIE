<?php
/**
 * Importateur de publications Facebook
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajouter une page d'administration pour l'import
 */
function almetal_add_import_page() {
    add_submenu_page(
        'edit.php?post_type=realisation',
        __('Importer depuis Facebook', 'almetal'),
        __('Import Facebook', 'almetal'),
        'manage_options',
        'almetal-facebook-import',
        'almetal_facebook_import_page'
    );
}
add_action('admin_menu', 'almetal_add_import_page');

/**
 * Page d'administration pour l'import
 */
function almetal_facebook_import_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Importer des publications Facebook', 'almetal'); ?></h1>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Instructions', 'almetal'); ?></h2>
            <ol>
                <li><?php _e('Connectez-vous à Facebook en tant qu\'administrateur de la page AL Métallerie', 'almetal'); ?></li>
                <li><?php _e('Allez dans Paramètres → Vos informations Facebook', 'almetal'); ?></li>
                <li><?php _e('Cliquez sur "Télécharger les informations de la page"', 'almetal'); ?></li>
                <li><?php _e('Sélectionnez : Publications, Photos (Format JSON)', 'almetal'); ?></li>
                <li><?php _e('Téléchargez le fichier et uploadez-le ci-dessous', 'almetal'); ?></li>
            </ol>
        </div>

        <?php
        if (isset($_GET['imported'])) {
            $count = intval($_GET['imported']);
            echo '<div class="notice notice-success"><p>';
            printf(__('%d réalisation(s) importée(s) avec succès !', 'almetal'), $count);
            echo '</p></div>';
        }

        if (isset($_GET['error'])) {
            echo '<div class="notice notice-error"><p>';
            echo esc_html($_GET['error']);
            echo '</p></div>';
        }
        ?>

        <form method="post" enctype="multipart/form-data" style="margin-top: 20px;">
            <?php wp_nonce_field('almetal_facebook_import', 'almetal_import_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="facebook_json"><?php _e('Fichier JSON Facebook', 'almetal'); ?></label>
                    </th>
                    <td>
                        <input type="file" name="facebook_json" id="facebook_json" accept=".json" required>
                        <p class="description"><?php _e('Fichier JSON exporté depuis Facebook', 'almetal'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="default_type"><?php _e('Type par défaut', 'almetal'); ?></label>
                    </th>
                    <td>
                        <?php
                        $types = get_terms(array(
                            'taxonomy' => 'type_realisation',
                            'hide_empty' => false,
                        ));
                        ?>
                        <select name="default_type" id="default_type">
                            <option value=""><?php _e('Aucun', 'almetal'); ?></option>
                            <?php foreach ($types as $type) : ?>
                                <option value="<?php echo esc_attr($type->term_id); ?>">
                                    <?php echo esc_html($type->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Type de réalisation à attribuer par défaut', 'almetal'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="import_images"><?php _e('Importer les images', 'almetal'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="import_images" id="import_images" value="1" checked>
                        <p class="description"><?php _e('Télécharger et importer les images des publications', 'almetal'); ?></p>
                    </td>
                </tr>
            </table>

            <?php submit_button(__('Importer les publications', 'almetal')); ?>
        </form>

        <div class="card" style="max-width: 800px; margin-top: 20px; background: #fff3cd;">
            <h3><?php _e('⚠️ Important', 'almetal'); ?></h3>
            <ul>
                <li><?php _e('L\'import peut prendre plusieurs minutes selon le nombre de publications', 'almetal'); ?></li>
                <li><?php _e('Les publications déjà importées (même ID Facebook) seront ignorées', 'almetal'); ?></li>
                <li><?php _e('Vous pourrez modifier les réalisations après l\'import', 'almetal'); ?></li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Traiter l'import du fichier JSON
 */
function almetal_process_facebook_import() {
    // Vérifier les permissions et le nonce
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!isset($_POST['almetal_import_nonce']) || !wp_verify_nonce($_POST['almetal_import_nonce'], 'almetal_facebook_import')) {
        return;
    }

    // Vérifier le fichier
    if (!isset($_FILES['facebook_json']) || $_FILES['facebook_json']['error'] !== UPLOAD_ERR_OK) {
        wp_redirect(add_query_arg('error', urlencode(__('Erreur lors de l\'upload du fichier', 'almetal')), wp_get_referer()));
        exit;
    }

    // Lire le fichier JSON
    $json_content = file_get_contents($_FILES['facebook_json']['tmp_name']);
    $data = json_decode($json_content, true);

    if (!$data) {
        wp_redirect(add_query_arg('error', urlencode(__('Fichier JSON invalide', 'almetal')), wp_get_referer()));
        exit;
    }

    $default_type = isset($_POST['default_type']) ? intval($_POST['default_type']) : 0;
    $import_images = isset($_POST['import_images']);
    $imported_count = 0;

    // Parcourir les publications
    if (isset($data['posts'])) {
        foreach ($data['posts'] as $post_data) {
            $imported = almetal_import_facebook_post($post_data, $default_type, $import_images);
            if ($imported) {
                $imported_count++;
            }
        }
    }

    // Rediriger avec message de succès
    wp_redirect(add_query_arg('imported', $imported_count, wp_get_referer()));
    exit;
}
add_action('admin_post_almetal_facebook_import', 'almetal_process_facebook_import');

/**
 * Importer une publication Facebook
 */
function almetal_import_facebook_post($post_data, $default_type = 0, $import_images = true) {
    // Vérifier si la publication existe déjà
    $facebook_id = isset($post_data['id']) ? $post_data['id'] : '';
    if ($facebook_id) {
        $existing = get_posts(array(
            'post_type' => 'realisation',
            'meta_key' => '_almetal_facebook_id',
            'meta_value' => $facebook_id,
            'posts_per_page' => 1,
        ));

        if (!empty($existing)) {
            return false; // Déjà importé
        }
    }

    // Extraire les données
    $title = isset($post_data['title']) ? $post_data['title'] : '';
    $content = isset($post_data['post']) ? $post_data['post'] : '';
    $timestamp = isset($post_data['timestamp']) ? $post_data['timestamp'] : time();
    
    // Si pas de titre, utiliser les premiers mots du contenu
    if (empty($title) && !empty($content)) {
        $title = wp_trim_words($content, 10, '...');
    }
    
    if (empty($title)) {
        $title = 'Réalisation du ' . date_i18n('d/m/Y', $timestamp);
    }

    // Créer la réalisation
    $post_id = wp_insert_post(array(
        'post_type' => 'realisation',
        'post_title' => sanitize_text_field($title),
        'post_content' => wp_kses_post($content),
        'post_status' => 'draft', // En brouillon pour révision
        'post_date' => date('Y-m-d H:i:s', $timestamp),
    ));

    if (is_wp_error($post_id)) {
        return false;
    }

    // Sauvegarder l'ID Facebook
    if ($facebook_id) {
        update_post_meta($post_id, '_almetal_facebook_id', $facebook_id);
    }

    // Sauvegarder la date de réalisation
    update_post_meta($post_id, '_almetal_date_realisation', date('Y-m-d', $timestamp));

    // Attribuer le type par défaut
    if ($default_type) {
        wp_set_object_terms($post_id, $default_type, 'type_realisation');
    }

    // Importer les images
    if ($import_images && isset($post_data['attachments'])) {
        foreach ($post_data['attachments'] as $attachment) {
            if (isset($attachment['data']) && is_array($attachment['data'])) {
                foreach ($attachment['data'] as $media) {
                    if (isset($media['media']['uri'])) {
                        $image_url = $media['media']['uri'];
                        almetal_import_facebook_image($image_url, $post_id);
                    }
                }
            }
        }
    }

    return true;
}

/**
 * Importer une image depuis Facebook
 */
function almetal_import_facebook_image($image_url, $post_id) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Télécharger l'image
    $tmp = download_url($image_url);

    if (is_wp_error($tmp)) {
        return false;
    }

    // Préparer le fichier
    $file_array = array(
        'name' => basename($image_url),
        'tmp_name' => $tmp,
    );

    // Importer dans la médiathèque
    $attachment_id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($attachment_id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }

    // Définir comme image à la une si c'est la première
    if (!has_post_thumbnail($post_id)) {
        set_post_thumbnail($post_id, $attachment_id);
    }

    return $attachment_id;
}
