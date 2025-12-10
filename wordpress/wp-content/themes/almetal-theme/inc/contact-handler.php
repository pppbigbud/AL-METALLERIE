<?php
/**
 * Gestionnaire du formulaire de contact
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Traiter la soumission du formulaire de contact
 */
function almetal_handle_contact_form() {
    // Vérifier le nonce
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'almetal_contact_form')) {
        wp_die('Erreur de sécurité');
    }

    // Récupérer et nettoyer les données
    $name = sanitize_text_field($_POST['contact_name']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $email = sanitize_email($_POST['contact_email']);
    $project_type = sanitize_text_field($_POST['contact_project']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    $consent = isset($_POST['contact_consent']) ? true : false;

    // Validation
    $errors = array();

    if (empty($name)) {
        $errors[] = 'Le nom est requis';
    }

    if (empty($phone)) {
        $errors[] = 'Le téléphone est requis';
    }

    if (empty($email) || !is_email($email)) {
        $errors[] = 'L\'email est invalide';
    }

    if (empty($project_type)) {
        $errors[] = 'Le type de projet est requis';
    }

    if (empty($message)) {
        $errors[] = 'Le message est requis';
    }

    if (!$consent) {
        $errors[] = 'Vous devez accepter l\'utilisation de vos données';
    }

    // Si erreurs, retourner
    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode('<br>', $errors)));
        return;
    }

    // Préparer l'email
    $to = 'aurelien@al-metallerie.fr';
    $subject = 'Nouvelle demande de contact - ' . $project_type;
    
    // Corps de l'email en HTML
    $body = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #F08B18; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #F08B18; }
            .value { margin-top: 5px; }
            .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Nouvelle demande de contact</h2>
            </div>
            <div class="content">
                <div class="field">
                    <div class="label">Nom complet :</div>
                    <div class="value">' . esc_html($name) . '</div>
                </div>
                <div class="field">
                    <div class="label">Téléphone :</div>
                    <div class="value"><a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a></div>
                </div>
                <div class="field">
                    <div class="label">Email :</div>
                    <div class="value"><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></div>
                </div>
                <div class="field">
                    <div class="label">Type de projet :</div>
                    <div class="value">' . esc_html($project_type) . '</div>
                </div>
                <div class="field">
                    <div class="label">Message :</div>
                    <div class="value">' . nl2br(esc_html($message)) . '</div>
                </div>
                <div class="field">
                    <div class="label">Date de réception :</div>
                    <div class="value">' . date('d/m/Y à H:i') . '</div>
                </div>
            </div>
            <div class="footer">
                <p>Ce message a été envoyé depuis le formulaire de contact du site AL Métallerie</p>
            </div>
        </div>
    </body>
    </html>
    ';

    // Headers
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email
    );

    // Envoyer l'email
    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        // Email de confirmation au client
        $client_subject = 'Confirmation de votre demande - AL Métallerie';
        $client_body = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #F08B18; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Merci pour votre demande !</h2>
                </div>
                <div class="content">
                    <p>Bonjour ' . esc_html($name) . ',</p>
                    <p>Nous avons bien reçu votre demande concernant : <strong>' . esc_html($project_type) . '</strong></p>
                    <p>Nous vous recontacterons dans les plus brefs délais pour étudier votre projet.</p>
                    <p>En attendant, n\'hésitez pas à nous contacter directement :</p>
                    <ul>
                        <li>Téléphone : <a href="tel:+33673333532">06 73 33 35 32</a></li>
                        <li>Email : <a href="mailto:aurelien@al-metallerie.fr">aurelien@al-metallerie.fr</a></li>
                        <li>Adresse : 14 route de Maringues, 63920 Peschadoires</li>
                    </ul>
                    <p>Cordialement,<br>L\'équipe AL Métallerie</p>
                </div>
                <div class="footer">
                    <p>AL Métallerie - Expert en métallerie à Thiers</p>
                </div>
            </div>
        </body>
        </html>
        ';

        $client_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: AL Métallerie <aurelien@al-metallerie.fr>'
        );

        wp_mail($email, $client_subject, $client_body, $client_headers);

        // Sauvegarder dans la base de données
        almetal_save_contact_submission($name, $phone, $email, $project_type, $message);
        
        // Intégrer avec le système d'opt-ins Analytics si disponible
        $consent_newsletter = isset($_POST['consent_newsletter']) && $_POST['consent_newsletter'] == '1';
        $consent_marketing = isset($_POST['consent_marketing']) && $_POST['consent_marketing'] == '1';
        $form_source = sanitize_text_field($_POST['form_source'] ?? 'contact_form');
        
        if (($consent_newsletter || $consent_marketing) && class_exists('Almetal_Analytics_Optin')) {
            Almetal_Analytics_Optin::create_optin(array(
                'email' => $email,
                'phone' => $phone,
                'name' => $name,
                'source' => $form_source,
                'form_id' => 'contact_' . $project_type,
                'consent_marketing' => $consent_marketing,
                'consent_newsletter' => $consent_newsletter,
                'visitor_id' => sanitize_text_field($_COOKIE['almetal_visitor_id'] ?? ''),
            ));
        }

        wp_send_json_success(array('message' => 'Message envoyé avec succès'));
    } else {
        wp_send_json_error(array('message' => 'Erreur lors de l\'envoi du message'));
    }
}
add_action('admin_post_almetal_contact_form', 'almetal_handle_contact_form');
add_action('admin_post_nopriv_almetal_contact_form', 'almetal_handle_contact_form');
// Ajouter aussi les hooks AJAX pour les requêtes JavaScript
add_action('wp_ajax_almetal_contact_form', 'almetal_handle_contact_form');
add_action('wp_ajax_nopriv_almetal_contact_form', 'almetal_handle_contact_form');

/**
 * Créer la table de contacts lors de l'activation du thème
 */
function almetal_create_contacts_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'almetal_contacts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        phone varchar(50) NOT NULL,
        email varchar(255) NOT NULL,
        project_type varchar(100) NOT NULL,
        message text NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
// Créer la table au chargement du fichier
add_action('after_setup_theme', 'almetal_create_contacts_table');

/**
 * Sauvegarder la soumission dans la base de données
 */
function almetal_save_contact_submission($name, $phone, $email, $project_type, $message) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'almetal_contacts';

    // S'assurer que la table existe
    almetal_create_contacts_table();

    // Insérer les données
    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'project_type' => $project_type,
            'message' => $message
        ),
        array('%s', '%s', '%s', '%s', '%s')
    );
}

/**
 * Ajouter une page d'administration pour voir les contacts
 */
function almetal_add_contacts_admin_page() {
    add_menu_page(
        'Demandes de contact',
        'Contacts',
        'manage_options',
        'almetal-contacts',
        'almetal_contacts_admin_page',
        'dashicons-email',
        30
    );
}
add_action('admin_menu', 'almetal_add_contacts_admin_page');

/**
 * Afficher la page d'administration des contacts
 */
function almetal_contacts_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'almetal_contacts';
    
    // Traiter les actions de suppression
    if (isset($_POST['action']) && $_POST['action'] === 'delete_contacts') {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'bulk_delete_contacts')) {
            wp_die('Erreur de sécurité');
        }
        
        if (!empty($_POST['contact_ids']) && is_array($_POST['contact_ids'])) {
            $ids = array_map('intval', $_POST['contact_ids']);
            $ids_placeholder = implode(',', array_fill(0, count($ids), '%d'));
            $wpdb->query($wpdb->prepare(
                "DELETE FROM $table_name WHERE id IN ($ids_placeholder)",
                ...$ids
            ));
            echo '<div class="notice notice-success"><p>' . count($ids) . ' message(s) supprimé(s).</p></div>';
        }
    }
    
    // Suppression individuelle
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_contact_' . $_GET['id'])) {
            wp_die('Erreur de sécurité');
        }
        
        $id = intval($_GET['id']);
        $wpdb->delete($table_name, array('id' => $id), array('%d'));
        echo '<div class="notice notice-success"><p>Message supprimé.</p></div>';
    }

    // Récupérer tous les contacts
    $contacts = $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY submitted_at DESC"
    );
    
    $total_contacts = count($contacts);

    ?>
    <div class="wrap">
        <h1>Demandes de contact <span class="title-count">(<?php echo $total_contacts; ?>)</span></h1>
        
        <?php if (empty($contacts)) : ?>
            <p>Aucune demande de contact pour le moment.</p>
        <?php else : ?>
            <form method="post" id="contacts-form">
                <?php wp_nonce_field('bulk_delete_contacts'); ?>
                <input type="hidden" name="action" value="delete_contacts">
                
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <button type="submit" class="button action" onclick="return confirm('Supprimer les messages sélectionnés ?');">
                            🗑️ Supprimer la sélection
                        </button>
                    </div>
                    <div class="alignright">
                        <span class="displaying-num"><?php echo $total_contacts; ?> élément(s)</span>
                    </div>
                </div>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <input type="checkbox" id="cb-select-all-1" onclick="toggleAllCheckboxes(this)">
                            </td>
                            <th style="width:120px;">Date</th>
                            <th style="width:150px;">Nom</th>
                            <th style="width:120px;">Téléphone</th>
                            <th style="width:180px;">Email</th>
                            <th style="width:120px;">Type de projet</th>
                            <th>Message</th>
                            <th style="width:80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact) : ?>
                            <tr>
                                <th scope="row" class="check-column">
                                    <input type="checkbox" name="contact_ids[]" value="<?php echo esc_attr($contact->id); ?>">
                                </th>
                                <td><?php echo esc_html(date('d/m/Y H:i', strtotime($contact->submitted_at))); ?></td>
                                <td><strong><?php echo esc_html($contact->name); ?></strong></td>
                                <td><a href="tel:<?php echo esc_attr($contact->phone); ?>"><?php echo esc_html($contact->phone); ?></a></td>
                                <td><a href="mailto:<?php echo esc_attr($contact->email); ?>"><?php echo esc_html($contact->email); ?></a></td>
                                <td><?php echo esc_html($contact->project_type); ?></td>
                                <td>
                                    <span class="message-preview" title="<?php echo esc_attr($contact->message); ?>">
                                        <?php echo esc_html(wp_trim_words($contact->message, 15)); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=almetal-contacts&action=delete&id=' . $contact->id), 'delete_contact_' . $contact->id); ?>" 
                                       class="button button-small" 
                                       style="color:#d63638;"
                                       onclick="return confirm('Supprimer ce message ?');"
                                       title="Supprimer">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <input type="checkbox" id="cb-select-all-2" onclick="toggleAllCheckboxes(this)">
                            </td>
                            <th>Date</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Type de projet</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="tablenav bottom">
                    <div class="alignleft actions bulkactions">
                        <button type="submit" class="button action" onclick="return confirm('Supprimer les messages sélectionnés ?');">
                            🗑️ Supprimer la sélection
                        </button>
                    </div>
                </div>
            </form>
            
            <script>
            function toggleAllCheckboxes(source) {
                var checkboxes = document.querySelectorAll('input[name="contact_ids[]"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = source.checked;
                }
                // Synchroniser les deux checkboxes "select all"
                document.getElementById('cb-select-all-1').checked = source.checked;
                document.getElementById('cb-select-all-2').checked = source.checked;
            }
            </script>
            
            <style>
            .message-preview {
                cursor: help;
            }
            .title-count {
                font-weight: normal;
                color: #666;
                font-size: 14px;
            }
            </style>
        <?php endif; ?>
    </div>
    <?php
}
