<?php
/**
 * Générateur de contenu enrichi pour les pages réalisations
 * Génère automatiquement du contenu SEO optimisé basé sur les métadonnées
 * 
 * @package AL-Metallerie
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================================================
 * METABOX ADMIN - Générateur de contenu SEO
 * ============================================================================
 */

/**
 * Ajouter la metabox dans l'éditeur de réalisation
 */
function almetal_add_content_generator_metabox() {
    add_meta_box(
        'almetal_content_generator',
        '🚀 Générateur de Contenu SEO',
        'almetal_content_generator_metabox_callback',
        'realisation',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'almetal_add_content_generator_metabox');

/**
 * Callback de la metabox
 */
function almetal_content_generator_metabox_callback($post) {
    wp_nonce_field('almetal_content_generator_nonce', 'almetal_content_generator_nonce_field');
    
    // Vérifier si le contenu a déjà été généré
    $content_generated = get_post_meta($post->ID, '_almetal_content_generated', true);
    ?>
    <div class="almetal-content-generator-box">
        <style>
            .almetal-content-generator-box {
                padding: 15px;
                background: #f9f9f9;
                border-radius: 8px;
            }
            .almetal-generator-info {
                display: flex;
                align-items: flex-start;
                gap: 15px;
                margin-bottom: 20px;
                padding: 15px;
                background: #fff;
                border-left: 4px solid #F08B18;
                border-radius: 4px;
            }
            .almetal-generator-info svg {
                flex-shrink: 0;
                color: #F08B18;
            }
            .almetal-generator-info h4 {
                margin: 0 0 8px 0;
                color: #1d2327;
            }
            .almetal-generator-info p {
                margin: 0;
                color: #646970;
                font-size: 13px;
            }
            .almetal-generator-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                margin-bottom: 15px;
            }
            .almetal-btn-generate {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: #F08B18;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .almetal-btn-generate:hover {
                background: #d97a14;
                transform: translateY(-1px);
            }
            .almetal-btn-generate:disabled {
                background: #ccc;
                cursor: not-allowed;
                transform: none;
            }
            .almetal-btn-preview {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: #fff;
                color: #1d2327;
                border: 1px solid #c3c4c7;
                border-radius: 6px;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .almetal-btn-preview:hover {
                border-color: #F08B18;
                color: #F08B18;
            }
            .almetal-generator-options {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
                margin-bottom: 15px;
                padding: 15px;
                background: #fff;
                border-radius: 6px;
            }
            .almetal-generator-options label {
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }
            .almetal-preview-container {
                display: none;
                margin-top: 20px;
                padding: 20px;
                background: #191919;
                border-radius: 8px;
                max-height: 400px;
                overflow-y: auto;
            }
            .almetal-preview-container.active {
                display: block;
            }
            .almetal-preview-container h3 {
                color: #F08B18;
                margin-top: 0;
            }
            .almetal-status-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }
            .almetal-status-badge.generated {
                background: #d4edda;
                color: #155724;
            }
            .almetal-status-badge.not-generated {
                background: #fff3cd;
                color: #856404;
            }
            .almetal-loading {
                display: none;
                align-items: center;
                gap: 10px;
                color: #646970;
            }
            .almetal-loading.active {
                display: flex;
            }
            .almetal-spinner {
                width: 20px;
                height: 20px;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #F08B18;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .almetal-success-message {
                display: none;
                padding: 10px 15px;
                background: #d4edda;
                color: #155724;
                border-radius: 6px;
                margin-top: 15px;
            }
            .almetal-success-message.active {
                display: block;
            }
        </style>
        
        <div class="almetal-generator-info">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            <div>
                <h4>Générez automatiquement du contenu SEO optimisé</h4>
                <p>Ce générateur crée des sections enrichies (Le Projet, Notre Réalisation, Détails Techniques, etc.) 
                basées sur les métadonnées de la réalisation. <strong>Remplissez d'abord les champs ci-dessous</strong> 
                (lieu, matière, finition, durée) pour un meilleur résultat.</p>
            </div>
        </div>
        
        <div style="margin-bottom: 15px;">
            <strong>Statut :</strong>
            <?php if ($content_generated) : ?>
                <span class="almetal-status-badge generated">✓ Contenu enrichi généré</span>
            <?php else : ?>
                <span class="almetal-status-badge not-generated">⚠ Contenu non enrichi</span>
            <?php endif; ?>
        </div>
        
        <div class="almetal-generator-options">
            <label>
                <input type="checkbox" name="almetal_gen_projet" value="1" checked>
                Section "Le Projet"
            </label>
            <label>
                <input type="checkbox" name="almetal_gen_realisation" value="1" checked>
                Section "Notre Réalisation"
            </label>
            <label>
                <input type="checkbox" name="almetal_gen_details" value="1" checked>
                Détails Techniques
            </label>
            <label>
                <input type="checkbox" name="almetal_gen_resultat" value="1" checked>
                Section "Résultat"
            </label>
            <label>
                <input type="checkbox" name="almetal_gen_similaires" value="1" checked>
                Projets Similaires
            </label>
            <label>
                <input type="checkbox" name="almetal_gen_cta" value="1" checked>
                CTA Final
            </label>
        </div>
        
        <div class="almetal-generator-actions">
            <button type="button" id="almetal-preview-content" class="almetal-btn-preview">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                Prévisualiser
            </button>
            <button type="button" id="almetal-generate-content" class="almetal-btn-generate">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                Générer et insérer le contenu
            </button>
        </div>
        
        <div class="almetal-loading" id="almetal-loading">
            <div class="almetal-spinner"></div>
            <span>Génération en cours...</span>
        </div>
        
        <div class="almetal-success-message" id="almetal-success">
            ✓ Contenu généré avec succès ! Il a été ajouté à la fin du contenu existant.
        </div>
        
        <div class="almetal-preview-container" id="almetal-preview-container">
            <h3>Prévisualisation du contenu</h3>
            <div id="almetal-preview-content"></div>
        </div>
        
        <input type="hidden" name="almetal_generated_content" id="almetal_generated_content" value="">
        <input type="hidden" name="almetal_do_generate" id="almetal_do_generate" value="0">
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Prévisualiser le contenu
        $('#almetal-preview-content').on('click', function() {
            var $btn = $(this);
            var $preview = $('#almetal-preview-container');
            var $previewContent = $('#almetal-preview-content');
            var $loading = $('#almetal-loading');
            
            $loading.addClass('active');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'almetal_preview_content',
                    post_id: <?php echo $post->ID; ?>,
                    nonce: '<?php echo wp_create_nonce('almetal_preview_nonce'); ?>',
                    sections: {
                        projet: $('input[name="almetal_gen_projet"]').is(':checked'),
                        realisation: $('input[name="almetal_gen_realisation"]').is(':checked'),
                        details: $('input[name="almetal_gen_details"]').is(':checked'),
                        resultat: $('input[name="almetal_gen_resultat"]').is(':checked'),
                        similaires: $('input[name="almetal_gen_similaires"]').is(':checked'),
                        cta: $('input[name="almetal_gen_cta"]').is(':checked')
                    }
                },
                success: function(response) {
                    $loading.removeClass('active');
                    if (response.success) {
                        $previewContent.html(response.data.html);
                        $preview.addClass('active');
                    } else {
                        alert('Erreur : ' + response.data.message);
                    }
                },
                error: function() {
                    $loading.removeClass('active');
                    alert('Erreur de connexion');
                }
            });
        });
        
        // Générer et insérer le contenu
        $('#almetal-generate-content').on('click', function() {
            if (!confirm('Voulez-vous générer le contenu SEO enrichi ? Il sera ajouté à la fin du contenu existant.')) {
                return;
            }
            
            var $btn = $(this);
            var $loading = $('#almetal-loading');
            var $success = $('#almetal-success');
            
            $btn.prop('disabled', true);
            $loading.addClass('active');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'almetal_generate_and_save_content',
                    post_id: <?php echo $post->ID; ?>,
                    nonce: '<?php echo wp_create_nonce('almetal_generate_nonce'); ?>',
                    sections: {
                        projet: $('input[name="almetal_gen_projet"]').is(':checked'),
                        realisation: $('input[name="almetal_gen_realisation"]').is(':checked'),
                        details: $('input[name="almetal_gen_details"]').is(':checked'),
                        resultat: $('input[name="almetal_gen_resultat"]').is(':checked'),
                        similaires: $('input[name="almetal_gen_similaires"]').is(':checked'),
                        cta: $('input[name="almetal_gen_cta"]').is(':checked')
                    }
                },
                success: function(response) {
                    $loading.removeClass('active');
                    $btn.prop('disabled', false);
                    
                    if (response.success) {
                        $success.addClass('active');
                        // Mettre à jour le badge de statut
                        $('.almetal-status-badge').removeClass('not-generated').addClass('generated').html('✓ Contenu enrichi généré');
                        
                        // Recharger la page après 1.5s pour voir le contenu
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert('Erreur : ' + response.data.message);
                    }
                },
                error: function() {
                    $loading.removeClass('active');
                    $btn.prop('disabled', false);
                    alert('Erreur de connexion');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX - Prévisualiser le contenu
 */
function almetal_ajax_preview_content() {
    check_ajax_referer('almetal_preview_nonce', 'nonce');
    
    $post_id = intval($_POST['post_id']);
    
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_send_json_error(array('message' => 'Permissions insuffisantes'));
    }
    
    $html = almetal_generate_realisation_content($post_id);
    
    if (empty($html)) {
        wp_send_json_error(array('message' => 'Impossible de générer le contenu. Vérifiez que les métadonnées sont remplies.'));
    }
    
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_almetal_preview_content', 'almetal_ajax_preview_content');

/**
 * AJAX - Générer et sauvegarder le contenu
 */
function almetal_ajax_generate_and_save_content() {
    check_ajax_referer('almetal_generate_nonce', 'nonce');
    
    $post_id = intval($_POST['post_id']);
    
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_send_json_error(array('message' => 'Permissions insuffisantes'));
    }
    
    // Générer le contenu
    $generated_content = almetal_generate_realisation_content($post_id);
    
    if (empty($generated_content)) {
        wp_send_json_error(array('message' => 'Impossible de générer le contenu. Vérifiez que les métadonnées sont remplies.'));
    }
    
    // Récupérer le contenu existant
    $post = get_post($post_id);
    $existing_content = $post->post_content;
    
    // Ajouter un séparateur si contenu existant
    $separator = '';
    if (!empty(trim($existing_content))) {
        $separator = "\n\n<!-- CONTENU SEO ENRICHI GÉNÉRÉ AUTOMATIQUEMENT -->\n\n";
    }
    
    // Mettre à jour le post
    $updated = wp_update_post(array(
        'ID' => $post_id,
        'post_content' => $existing_content . $separator . $generated_content
    ));
    
    if (is_wp_error($updated)) {
        wp_send_json_error(array('message' => 'Erreur lors de la sauvegarde'));
    }
    
    // Marquer comme généré
    update_post_meta($post_id, '_almetal_content_generated', 1);
    update_post_meta($post_id, '_almetal_content_generated_date', current_time('mysql'));
    
    wp_send_json_success(array('message' => 'Contenu généré avec succès'));
}
add_action('wp_ajax_almetal_generate_and_save_content', 'almetal_ajax_generate_and_save_content');

/**
 * Génère le contenu enrichi complet pour une réalisation
 * 
 * @param int $post_id ID de la réalisation
 * @return string HTML du contenu enrichi
 */
function almetal_generate_realisation_content($post_id) {
    // Récupérer toutes les métadonnées
    $data = almetal_get_realisation_data($post_id);
    
    if (!$data) {
        return '';
    }
    
    $output = '';
    
    // Section "Le Projet"
    $output .= almetal_generate_section_projet($data);
    
    // Section "Notre Réalisation"
    $output .= almetal_generate_section_realisation($data);
    
    // Section "Détails Techniques"
    $output .= almetal_generate_section_details($data);
    
    // Section "Résultat"
    $output .= almetal_generate_section_resultat($data);
    
    // Section "Projets Similaires"
    $output .= almetal_generate_section_similaires($data);
    
    // CTA Final
    $output .= almetal_generate_cta_final($data);
    
    return $output;
}

/**
 * Récupère toutes les données d'une réalisation
 */
function almetal_get_realisation_data($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return null;
    }
    
    // Type de réalisation
    $types = get_the_terms($post_id, 'type_realisation');
    $type_name = $types && !is_wp_error($types) ? $types[0]->name : 'Réalisation';
    $type_slug = $types && !is_wp_error($types) ? $types[0]->slug : 'autres';
    
    // Métadonnées
    $data = array(
        'post_id' => $post_id,
        'title' => get_the_title($post_id),
        'content' => $post->post_content,
        'type_name' => $type_name,
        'type_slug' => $type_slug,
        'lieu' => get_post_meta($post_id, '_almetal_lieu', true) ?: 'Puy-de-Dôme',
        'client_type' => get_post_meta($post_id, '_almetal_client_type', true) ?: 'particulier',
        'client_nom' => get_post_meta($post_id, '_almetal_client_nom', true),
        'matiere' => get_post_meta($post_id, '_almetal_matiere', true) ?: 'acier',
        'peinture' => get_post_meta($post_id, '_almetal_peinture', true) ?: 'thermolaquage',
        'duree' => get_post_meta($post_id, '_almetal_duree', true) ?: '2 semaines',
        'pose' => get_post_meta($post_id, '_almetal_pose', true) ?: 'oui',
        'date_realisation' => get_post_meta($post_id, '_almetal_date_realisation', true),
        'dimensions' => get_post_meta($post_id, '_almetal_dimensions', true),
        'poids' => get_post_meta($post_id, '_almetal_poids', true),
        'garantie' => get_post_meta($post_id, '_almetal_garantie', true) ?: '10 ans',
        'temoignage' => get_post_meta($post_id, '_almetal_temoignage', true),
    );
    
    // Labels
    $matiere_labels = array(
        'acier' => 'Acier',
        'inox' => 'Inox 304/316',
        'aluminium' => 'Aluminium',
        'fer-forge' => 'Fer forgé',
        'mixte' => 'Mixte (acier/bois)'
    );
    $data['matiere_label'] = isset($matiere_labels[$data['matiere']]) ? $matiere_labels[$data['matiere']] : ucfirst($data['matiere']);
    
    $peinture_labels = array(
        'thermolaquage' => 'Thermolaquage',
        'galvanisation' => 'Galvanisation à chaud',
        'peinture-epoxy' => 'Peinture époxy',
        'brut' => 'Aspect brut ciré',
        'inox-brosse' => 'Inox brossé'
    );
    $data['peinture_label'] = isset($peinture_labels[$data['peinture']]) ? $peinture_labels[$data['peinture']] : ucfirst($data['peinture']);
    
    // Année
    $data['annee'] = $data['date_realisation'] ? date('Y', strtotime($data['date_realisation'])) : date('Y');
    
    return $data;
}

/**
 * Section "Le Projet"
 */
function almetal_generate_section_projet($data) {
    $client_label = $data['client_type'] === 'professionnel' ? 'un client professionnel' : 'un particulier';
    
    // Variations de texte selon le type
    $introductions = array(
        'portails' => array(
            "Ce projet de portail sur mesure nous a été confié par {$client_label} de {$data['lieu']}. L'objectif était de créer une entrée à la fois esthétique et sécurisée, parfaitement intégrée à l'architecture existante.",
            "Un {$client_label} de {$data['lieu']} nous a sollicités pour la conception et la fabrication d'un portail personnalisé. Le cahier des charges incluait des exigences précises en termes de design et de fonctionnalité.",
        ),
        'garde-corps' => array(
            "Ce garde-corps a été réalisé pour {$client_label} situé à {$data['lieu']}. La demande portait sur un ouvrage alliant sécurité aux normes et esthétique contemporaine.",
            "Un {$client_label} de {$data['lieu']} souhaitait sécuriser son espace tout en apportant une touche design. Notre expertise en métallerie sur mesure a permis de répondre parfaitement à cette attente.",
        ),
        'escaliers' => array(
            "Cet escalier métallique a été conçu pour {$client_label} à {$data['lieu']}. Le défi était de créer un ouvrage fonctionnel s'intégrant harmonieusement dans l'espace disponible.",
            "Un {$client_label} de {$data['lieu']} nous a confié la réalisation d'un escalier sur mesure. L'objectif : optimiser l'espace tout en créant un élément architectural remarquable.",
        ),
        'default' => array(
            "Cette réalisation a été commandée par {$client_label} de {$data['lieu']}. Notre atelier de métallerie à Thiers a mis tout son savoir-faire artisanal au service de ce projet.",
            "Un {$client_label} de {$data['lieu']} a fait appel à AL Métallerie pour cette création sur mesure. Notre expertise en fabrication artisanale a permis de concrétiser sa vision.",
        )
    );
    
    $type_key = isset($introductions[$data['type_slug']]) ? $data['type_slug'] : 'default';
    $intro = $introductions[$type_key][array_rand($introductions[$type_key])];
    
    // Contraintes selon le type
    $contraintes = array(
        'portails' => "Les contraintes du projet incluaient l'adaptation aux dimensions exactes de l'entrée, le choix d'un système d'ouverture adapté (battant ou coulissant), et une finition résistante aux intempéries du Puy-de-Dôme.",
        'garde-corps' => "Le projet devait respecter les normes NF P01-012 en vigueur (hauteur minimale, espacement des barreaux), tout en s'adaptant aux spécificités architecturales du lieu.",
        'escaliers' => "Les contraintes techniques comprenaient l'optimisation de l'encombrement, le respect des normes d'accessibilité, et l'intégration harmonieuse avec les matériaux existants.",
        'default' => "Ce projet nécessitait une étude approfondie pour répondre aux contraintes techniques et esthétiques spécifiques du client."
    );
    
    $contrainte = isset($contraintes[$data['type_slug']]) ? $contraintes[$data['type_slug']] : $contraintes['default'];
    
    $html = '<section class="realisation-section realisation-projet">';
    $html .= '<h2><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Le Projet</h2>';
    $html .= '<div class="section-content">';
    $html .= '<p>' . $intro . '</p>';
    $html .= '<p>' . $contrainte . '</p>';
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/**
 * Section "Notre Réalisation"
 */
function almetal_generate_section_realisation($data) {
    // Techniques selon le matériau
    $techniques = array(
        'acier' => 'soudure MIG/MAG et finition par thermolaquage',
        'inox' => 'soudure TIG pour des cordons parfaits et un aspect impeccable',
        'aluminium' => 'soudure TIG spécifique aluminium et anodisation',
        'fer-forge' => 'techniques traditionnelles de forge à chaud et travail à la main',
        'mixte' => 'assemblage multi-matériaux avec fixations adaptées'
    );
    $technique = isset($techniques[$data['matiere']]) ? $techniques[$data['matiere']] : $techniques['acier'];
    
    // Finitions
    $finitions = array(
        'thermolaquage' => 'Le thermolaquage assure une protection durable (garantie 10 ans) et une finition esthétique dans la couleur RAL choisie par le client.',
        'galvanisation' => 'La galvanisation à chaud offre une protection anticorrosion exceptionnelle, idéale pour les environnements extérieurs exigeants.',
        'peinture-epoxy' => 'La peinture époxy bi-composant garantit une excellente résistance aux UV et aux intempéries.',
        'brut' => 'L\'aspect brut ciré met en valeur la beauté naturelle du métal tout en le protégeant.',
        'inox-brosse' => 'Le brossage de l\'inox crée une finition satinée élégante et facile d\'entretien.'
    );
    $finition_desc = isset($finitions[$data['peinture']]) ? $finitions[$data['peinture']] : $finitions['thermolaquage'];
    
    $html = '<section class="realisation-section realisation-fabrication">';
    $html .= '<h2><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg> Notre Réalisation</h2>';
    $html .= '<div class="section-content">';
    $html .= '<p>Pour ce ' . strtolower($data['type_name']) . ', nous avons utilisé du <strong>' . $data['matiere_label'] . '</strong>, un matériau que nous maîtrisons parfaitement dans notre atelier de métallerie à Peschadoires, près de Thiers.</p>';
    $html .= '<p>La fabrication a été réalisée entièrement sur mesure, en utilisant nos techniques de ' . $technique . '. Chaque étape a été effectuée avec le plus grand soin par notre artisan métallier.</p>';
    $html .= '<p>' . $finition_desc . '</p>';
    
    if ($data['pose'] === 'oui') {
        $html .= '<p>La pose a été assurée par nos soins à ' . $data['lieu'] . ', garantissant une installation parfaite et conforme aux normes en vigueur.</p>';
    }
    
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/**
 * Section "Détails Techniques"
 */
function almetal_generate_section_details($data) {
    $html = '<section class="realisation-section realisation-details">';
    $html .= '<h2><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg> Détails Techniques</h2>';
    $html .= '<div class="details-grid">';
    
    // Matériau
    $html .= '<div class="detail-item">';
    $html .= '<span class="detail-label">Matériau principal</span>';
    $html .= '<span class="detail-value">' . $data['matiere_label'] . '</span>';
    $html .= '</div>';
    
    // Finition
    $html .= '<div class="detail-item">';
    $html .= '<span class="detail-label">Finition</span>';
    $html .= '<span class="detail-value">' . $data['peinture_label'] . '</span>';
    $html .= '</div>';
    
    // Dimensions si disponibles
    if (!empty($data['dimensions'])) {
        $html .= '<div class="detail-item">';
        $html .= '<span class="detail-label">Dimensions</span>';
        $html .= '<span class="detail-value">' . esc_html($data['dimensions']) . '</span>';
        $html .= '</div>';
    }
    
    // Poids si disponible
    if (!empty($data['poids'])) {
        $html .= '<div class="detail-item">';
        $html .= '<span class="detail-label">Poids</span>';
        $html .= '<span class="detail-value">' . esc_html($data['poids']) . '</span>';
        $html .= '</div>';
    }
    
    // Durée fabrication
    $html .= '<div class="detail-item">';
    $html .= '<span class="detail-label">Durée fabrication</span>';
    $html .= '<span class="detail-value">' . esc_html($data['duree']) . '</span>';
    $html .= '</div>';
    
    // Pose
    $html .= '<div class="detail-item">';
    $html .= '<span class="detail-label">Pose incluse</span>';
    $html .= '<span class="detail-value">' . ($data['pose'] === 'oui' ? 'Oui' : 'Non') . '</span>';
    $html .= '</div>';
    
    // Garantie
    $html .= '<div class="detail-item">';
    $html .= '<span class="detail-label">Garantie</span>';
    $html .= '<span class="detail-value">' . esc_html($data['garantie']) . '</span>';
    $html .= '</div>';
    
    // Lieu
    $html .= '<div class="detail-item">';
    $html .= '<span class="detail-label">Lieu d\'installation</span>';
    $html .= '<span class="detail-value">' . esc_html($data['lieu']) . '</span>';
    $html .= '</div>';
    
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/**
 * Section "Résultat"
 */
function almetal_generate_section_resultat($data) {
    $resultats = array(
        'portails' => "Le portail terminé répond parfaitement aux attentes du client : une entrée élégante et sécurisée qui valorise la propriété. La qualité de fabrication artisanale et la finition soignée garantissent une durabilité exceptionnelle.",
        'garde-corps' => "Le garde-corps installé allie parfaitement sécurité et esthétique. Conforme aux normes en vigueur, il apporte une touche design tout en assurant une protection optimale.",
        'escaliers' => "L'escalier réalisé s'intègre harmonieusement dans l'espace. Sa conception sur mesure optimise la circulation tout en créant un véritable élément architectural.",
        'default' => "Cette réalisation illustre notre savoir-faire en métallerie sur mesure. La qualité des finitions et l'attention portée aux détails témoignent de notre engagement pour l'excellence."
    );
    
    $resultat = isset($resultats[$data['type_slug']]) ? $resultats[$data['type_slug']] : $resultats['default'];
    
    $html = '<section class="realisation-section realisation-resultat">';
    $html .= '<h2><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Résultat</h2>';
    $html .= '<div class="section-content">';
    $html .= '<p>' . $resultat . '</p>';
    
    // Témoignage client si disponible
    if (!empty($data['temoignage'])) {
        $html .= '<blockquote class="client-testimonial">';
        $html .= '<p>"' . esc_html($data['temoignage']) . '"</p>';
        if (!empty($data['client_nom'])) {
            $html .= '<cite>— ' . esc_html($data['client_nom']) . ', ' . $data['lieu'] . '</cite>';
        }
        $html .= '</blockquote>';
    }
    
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/**
 * Section "Projets Similaires"
 */
function almetal_generate_section_similaires($data) {
    // Récupérer les réalisations du même type
    $args = array(
        'post_type' => 'realisation',
        'posts_per_page' => 4,
        'post__not_in' => array($data['post_id']),
        'tax_query' => array(
            array(
                'taxonomy' => 'type_realisation',
                'field' => 'slug',
                'terms' => $data['type_slug']
            )
        )
    );
    
    $similaires = new WP_Query($args);
    
    if (!$similaires->have_posts()) {
        return '';
    }
    
    $html = '<section class="realisation-section realisation-similaires">';
    $html .= '<h2><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Projets Similaires</h2>';
    $html .= '<div class="similaires-grid">';
    
    while ($similaires->have_posts()) {
        $similaires->the_post();
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        $lieu_sim = get_post_meta(get_the_ID(), '_almetal_lieu', true);
        
        $html .= '<a href="' . get_permalink() . '" class="similaire-card">';
        if ($thumb) {
            $html .= '<div class="similaire-image"><img src="' . esc_url($thumb) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy"></div>';
        }
        $html .= '<div class="similaire-content">';
        $html .= '<h3>' . get_the_title() . '</h3>';
        if ($lieu_sim) {
            $html .= '<span class="similaire-lieu">' . esc_html($lieu_sim) . '</span>';
        }
        $html .= '</div>';
        $html .= '</a>';
    }
    wp_reset_postdata();
    
    $html .= '</div>';
    $html .= '<p class="voir-tous"><a href="' . get_term_link($data['type_slug'], 'type_realisation') . '">Voir tous nos ' . strtolower($data['type_name']) . ' →</a></p>';
    $html .= '</section>';
    
    return $html;
}

/**
 * CTA Final
 */
function almetal_generate_cta_final($data) {
    $html = '<section class="realisation-section realisation-cta">';
    $html .= '<div class="cta-content">';
    $html .= '<h2>Un projet similaire ?</h2>';
    $html .= '<p>Vous souhaitez un ' . strtolower($data['type_name']) . ' sur mesure pour votre propriété à ' . $data['lieu'] . ' ou dans le Puy-de-Dôme ? Contactez AL Métallerie pour un <strong>devis gratuit</strong>.</p>';
    $html .= '<div class="cta-features">';
    $html .= '<span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Devis gratuit sous 48h</span>';
    $html .= '<span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Fabrication artisanale</span>';
    $html .= '<span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Pose incluse</span>';
    $html .= '</div>';
    $html .= '<div class="cta-buttons">';
    $html .= '<a href="' . home_url('/contact/') . '" class="btn btn-primary">Demander un devis gratuit</a>';
    $html .= '<a href="tel:+33673333532" class="btn btn-secondary"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> 06 73 33 35 32</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/**
 * Schema FAQ spécifique à la réalisation
 */
function almetal_schema_faq_realisation() {
    if (!is_singular('realisation')) {
        return;
    }
    
    $post_id = get_the_ID();
    $data = almetal_get_realisation_data($post_id);
    
    if (!$data) {
        return;
    }
    
    // FAQ selon le type
    $faqs_by_type = array(
        'portails' => array(
            array(
                'question' => 'Quel est le prix d\'un portail sur mesure ?',
                'answer' => 'Le prix d\'un portail sur mesure varie entre 1 500€ et 5 000€ selon les dimensions, le matériau et la motorisation. Contactez-nous pour un devis personnalisé gratuit.'
            ),
            array(
                'question' => 'Quel délai pour la fabrication d\'un portail ?',
                'answer' => 'Comptez en moyenne 2 à 4 semaines pour la fabrication d\'un portail sur mesure, selon la complexité du design et les finitions choisies.'
            ),
            array(
                'question' => 'Proposez-vous la motorisation des portails ?',
                'answer' => 'Oui, nous proposons l\'installation de motorisations pour portails battants et coulissants, avec télécommande et options de domotique.'
            )
        ),
        'garde-corps' => array(
            array(
                'question' => 'Quel est le prix d\'un garde-corps au mètre linéaire ?',
                'answer' => 'Le prix d\'un garde-corps sur mesure varie entre 150€ et 400€ par mètre linéaire pose comprise, selon le matériau et le design choisi.'
            ),
            array(
                'question' => 'Les garde-corps sont-ils conformes aux normes ?',
                'answer' => 'Oui, tous nos garde-corps respectent la norme NF P01-012 : hauteur minimale de 1m, espacement des barreaux inférieur à 11cm, résistance aux charges.'
            ),
            array(
                'question' => 'Quel entretien pour un garde-corps en acier ?',
                'answer' => 'Un garde-corps thermolaqué nécessite peu d\'entretien : un nettoyage à l\'eau savonneuse 2 fois par an suffit. La garantie anticorrosion est de 10 ans.'
            )
        ),
        'escaliers' => array(
            array(
                'question' => 'Quel est le prix d\'un escalier métallique ?',
                'answer' => 'Le prix d\'un escalier métallique sur mesure varie entre 3 000€ et 15 000€ selon le type (droit, quart tournant, hélicoïdal), les dimensions et les finitions.'
            ),
            array(
                'question' => 'Quels types de marches proposez-vous ?',
                'answer' => 'Nous proposons des marches en tôle larmée, caillebotis, bois massif ou verre selon vos préférences et l\'usage prévu (intérieur/extérieur).'
            ),
            array(
                'question' => 'L\'escalier inclut-il la rampe ?',
                'answer' => 'Oui, nos escaliers sont livrés complets avec rampe et garde-corps assortis, fabriqués dans le même matériau pour une harmonie parfaite.'
            )
        ),
        'default' => array(
            array(
                'question' => 'Proposez-vous des devis gratuits ?',
                'answer' => 'Oui, nous nous déplaçons gratuitement pour étudier votre projet et vous remettre un devis détaillé sous 48h, sans engagement.'
            ),
            array(
                'question' => 'Quelle est votre zone d\'intervention ?',
                'answer' => 'Nous intervenons dans un rayon de 50km autour de Thiers, couvrant tout le Puy-de-Dôme : Clermont-Ferrand, Riom, Vichy, Ambert, Issoire...'
            ),
            array(
                'question' => 'Quelles garanties offrez-vous ?',
                'answer' => 'Nous offrons une garantie décennale sur la structure et 10 ans sur les finitions thermolaquées. Tous nos ouvrages sont conformes aux normes en vigueur.'
            )
        )
    );
    
    $type_key = isset($faqs_by_type[$data['type_slug']]) ? $data['type_slug'] : 'default';
    $faqs = $faqs_by_type[$type_key];
    
    $faq_items = array();
    foreach ($faqs as $faq) {
        $faq_items[] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faq_items
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_faq_realisation', 9);

/**
 * Schema Product pour chaque réalisation
 */
function almetal_schema_product_realisation() {
    if (!is_singular('realisation')) {
        return;
    }
    
    $post_id = get_the_ID();
    $data = almetal_get_realisation_data($post_id);
    
    if (!$data) {
        return;
    }
    
    $image = get_the_post_thumbnail_url($post_id, 'large');
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $data['title'],
        'description' => ucfirst($data['type_name']) . ' en ' . $data['matiere_label'] . ' réalisé sur mesure par AL Métallerie à ' . $data['lieu'] . '. Fabrication artisanale, finition ' . $data['peinture_label'] . '.',
        'image' => $image ?: '',
        'brand' => array(
            '@type' => 'Brand',
            'name' => 'AL Métallerie & Soudure'
        ),
        'manufacturer' => array(
            '@type' => 'Organization',
            'name' => 'AL Métallerie & Soudure',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressLocality' => 'Peschadoires',
                'postalCode' => '63920',
                'addressCountry' => 'FR'
            )
        ),
        'category' => $data['type_name'],
        'material' => $data['matiere_label'],
        'offers' => array(
            '@type' => 'Offer',
            'availability' => 'https://schema.org/InStock',
            'priceSpecification' => array(
                '@type' => 'PriceSpecification',
                'priceCurrency' => 'EUR'
            ),
            'seller' => array(
                '@type' => 'LocalBusiness',
                '@id' => home_url('/#localbusiness')
            )
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_product_realisation', 10);
