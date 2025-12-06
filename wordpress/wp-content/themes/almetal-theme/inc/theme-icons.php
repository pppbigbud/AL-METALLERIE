<?php
/**
 * Gestion centralisée des icônes du thème
 * 
 * Ce fichier centralise toutes les icônes SVG utilisées dans le thème.
 * Les icônes sont automatiquement mises à jour lorsqu'une nouvelle catégorie est ajoutée.
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Récupérer toutes les icônes disponibles dans le thème
 * 
 * @return array Liste des icônes avec leur slug, nom et SVG
 */
function almetal_get_all_icons() {
    $icons = array();
    
    // ============================================
    // ICÔNES DU HEADER / NAVIGATION
    // ============================================
    $icons['header'] = array(
        'label' => 'Navigation',
        'icons' => array(
            'accueil' => array(
                'name' => 'Accueil',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
            ),
            'formations' => array(
                'name' => 'Formations',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>'
            ),
            'contact' => array(
                'name' => 'Contact',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>'
            ),
            'realisations' => array(
                'name' => 'Réalisations',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><rect x="6" y="10" width="12" height="4" rx="1"/><path d="M2 8l2-2h16l2 2"/><path d="M8 18v2"/><path d="M16 18v2"/></svg>'
            ),
        )
    );
    
    // ============================================
    // ICÔNES DES CATÉGORIES DE RÉALISATIONS
    // ============================================
    $category_icons = array(
        'portails' => array(
            'name' => 'Portails',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>'
        ),
        'garde-corps' => array(
            'name' => 'Garde-corps',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>'
        ),
        'escaliers' => array(
            'name' => 'Escaliers',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>'
        ),
        'pergolas' => array(
            'name' => 'Pergolas',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>'
        ),
        'grilles' => array(
            'name' => 'Grilles',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>'
        ),
        'ferronnerie-art' => array(
            'name' => 'Ferronnerie d\'art',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>'
        ),
        'vehicules' => array(
            'name' => 'Véhicules',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>'
        ),
        'serrurerie' => array(
            'name' => 'Serrurerie',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>'
        ),
        'mobilier-metallique' => array(
            'name' => 'Mobilier métallique',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>'
        ),
    );
    
    // Ajouter les icônes des catégories existantes dans WordPress
    $terms = get_terms(array(
        'taxonomy' => 'type_realisation',
        'hide_empty' => false,
    ));
    
    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            // Si l'icône existe déjà dans notre liste, on la garde
            if (!isset($category_icons[$term->slug])) {
                // Sinon on ajoute une icône par défaut
                $category_icons[$term->slug] = array(
                    'name' => $term->name,
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>'
                );
            }
        }
    }
    
    $icons['categories'] = array(
        'label' => 'Catégories',
        'icons' => $category_icons
    );
    
    // ============================================
    // ICÔNES GÉNÉRALES / ACTIONS
    // ============================================
    $icons['general'] = array(
        'label' => 'Général',
        'icons' => array(
            'curseur' => array(
                'name' => 'Curseur (clic)',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/><path d="M13 13l6 6"/></svg>'
            ),
            'fleche-droite' => array(
                'name' => 'Flèche droite',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>'
            ),
            'telephone' => array(
                'name' => 'Téléphone',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
            ),
            'localisation' => array(
                'name' => 'Localisation',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>'
            ),
            'etoile' => array(
                'name' => 'Étoile',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
            ),
            'coeur' => array(
                'name' => 'Cœur',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>'
            ),
            'check' => array(
                'name' => 'Validation',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
            ),
            'info' => array(
                'name' => 'Information',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            ),
            'outils' => array(
                'name' => 'Outils',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>'
            ),
            'devis' => array(
                'name' => 'Devis',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>'
            ),
        )
    );
    
    // Permettre aux plugins/thèmes enfants d'ajouter des icônes
    return apply_filters('almetal_theme_icons', $icons);
}

/**
 * Récupérer une icône par son slug
 * 
 * @param string $slug Le slug de l'icône
 * @return string|null Le SVG de l'icône ou null si non trouvée
 */
function almetal_get_icon($slug) {
    $all_icons = almetal_get_all_icons();
    
    foreach ($all_icons as $group) {
        if (isset($group['icons'][$slug])) {
            return $group['icons'][$slug]['svg'];
        }
    }
    
    return null;
}

/**
 * Récupérer une icône par son slug avec attributs personnalisés
 * 
 * @param string $slug Le slug de l'icône
 * @param array $attrs Attributs à ajouter/modifier (width, height, class, etc.)
 * @return string|null Le SVG modifié ou null si non trouvée
 */
function almetal_get_icon_with_attrs($slug, $attrs = array()) {
    $svg = almetal_get_icon($slug);
    
    if (!$svg) {
        return null;
    }
    
    // S'assurer que fill="none" est présent
    if (!preg_match('/fill="none"/', $svg)) {
        $svg = preg_replace('/<svg/', '<svg fill="none"', $svg);
    }
    
    // Modifier les attributs du SVG
    foreach ($attrs as $attr => $value) {
        // Remplacer l'attribut existant ou l'ajouter
        if (preg_match('/' . $attr . '="[^"]*"/', $svg)) {
            $svg = preg_replace('/' . $attr . '="[^"]*"/', $attr . '="' . esc_attr($value) . '"', $svg);
        } else {
            $svg = preg_replace('/<svg/', '<svg ' . $attr . '="' . esc_attr($value) . '"', $svg);
        }
    }
    
    // Supprimer tout fill sur les éléments enfants (rect, circle, path, etc.)
    $svg = preg_replace('/<(rect|circle|ellipse|polygon|polyline)([^>]*)\s+fill="[^"]*"/', '<$1$2', $svg);
    
    return $svg;
}

/**
 * Afficher le sélecteur d'icônes pour l'admin
 * 
 * @param string $name Nom du champ
 * @param string $selected Slug de l'icône sélectionnée
 * @param string $id ID du champ (optionnel)
 */
function almetal_render_icon_selector($name, $selected = '', $id = '') {
    $all_icons = almetal_get_all_icons();
    $field_id = $id ?: 'icon-selector-' . uniqid();
    ?>
    <div class="almetal-icon-selector" id="<?php echo esc_attr($field_id); ?>">
        <div class="icon-selector-preview">
            <?php if ($selected && ($icon = almetal_get_icon($selected))) : ?>
                <span class="icon-preview"><?php echo $icon; ?></span>
                <span class="icon-name"><?php echo esc_html($selected); ?></span>
            <?php else : ?>
                <span class="icon-preview no-icon">—</span>
                <span class="icon-name">Aucune icône</span>
            <?php endif; ?>
            <button type="button" class="button icon-selector-toggle">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </button>
        </div>
        
        <div class="icon-selector-dropdown">
            <div class="icon-selector-search">
                <input type="text" placeholder="Rechercher une icône..." class="icon-search-input">
            </div>
            
            <div class="icon-selector-options">
                <!-- Option aucune icône -->
                <div class="icon-option <?php echo empty($selected) ? 'selected' : ''; ?>" data-value="">
                    <span class="icon-svg">—</span>
                    <span class="icon-label">Aucune icône</span>
                </div>
                
                <?php foreach ($all_icons as $group_key => $group) : ?>
                    <div class="icon-group">
                        <div class="icon-group-label"><?php echo esc_html($group['label']); ?></div>
                        <?php foreach ($group['icons'] as $slug => $icon) : ?>
                            <div class="icon-option <?php echo $selected === $slug ? 'selected' : ''; ?>" 
                                 data-value="<?php echo esc_attr($slug); ?>"
                                 data-name="<?php echo esc_attr($icon['name']); ?>">
                                <span class="icon-svg"><?php echo $icon['svg']; ?></span>
                                <span class="icon-label"><?php echo esc_html($icon['name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($selected); ?>" class="icon-selector-value">
    </div>
    <?php
}
