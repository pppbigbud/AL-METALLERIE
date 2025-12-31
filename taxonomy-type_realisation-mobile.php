<?php
/**
 * Template pour les pages de taxonomie type_realisation - VERSION MOBILE
 * (ex: /type-realisation/portails/, /type-realisation/escaliers/)
 * 
 * Page dédiée par catégorie avec contenu SEO optimisé
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Récupérer le terme actuel
$current_term = get_queried_object();

// Icônes SVG par type de réalisation (version mobile 32px)
$category_icons = array(
    'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
    'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
    'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
    'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
    'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
    'ferronnerie-art' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
    'ferronnerie-dart' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
    'vehicules' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
    'serrurerie' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
    'mobilier-metallique' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
    'autres' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
);

$current_icon = isset($category_icons[$current_term->slug]) ? $category_icons[$current_term->slug] : $category_icons['autres'];

// Contenus SEO par catégorie (optimisés pour le référencement)
$seo_contents = array(
    'portails' => array(
        'intro' => 'Spécialiste de la fabrication de portails sur mesure dans le Puy-de-Dôme, AL Métallerie & Soudure conçoit des portails battants et coulissants adaptés à vos besoins.',
        'details' => 'Nos portails sont fabriqués en acier, fer forgé ou aluminium selon vos préférences. Chaque création est unique : portail plein pour plus d\'intimité, portail ajouré pour un style contemporain, ou portail ornemental pour une touche classique. Nous assurons également la motorisation et l\'installation complète. Intervention dans tout le Puy-de-Dôme : Thiers, Clermont-Ferrand, Riom, Issoire et leurs environs.',
        'cta' => 'Demandez votre devis gratuit pour un portail sur mesure.'
    ),
    'garde-corps' => array(
        'intro' => 'AL Métallerie & Soudure réalise des garde-corps sur mesure pour sécuriser vos escaliers, balcons et terrasses tout en apportant une touche esthétique à votre habitat.',
        'details' => 'Nos garde-corps sont conçus selon les normes de sécurité en vigueur (NF P01-012). Nous proposons différents styles : garde-corps à barreaux verticaux, à câbles inox, avec remplissage verre ou panneaux décoratifs. Matériaux disponibles : acier thermolaqué, inox brossé ou poli, fer forgé traditionnel. Chaque projet est étudié sur place pour garantir une adaptation parfaite à votre configuration.',
        'cta' => 'Contactez-nous pour un devis personnalisé.'
    ),
    'escaliers' => array(
        'intro' => 'Fabrication d\'escaliers métalliques sur mesure par AL Métallerie & Soudure. Escaliers droits, quart tournant, hélicoïdaux : nous réalisons tous types de configurations.',
        'details' => 'Nos escaliers allient solidité et design. Structure en acier ou inox, marches en métal, bois ou verre selon vos goûts. Nous concevons des escaliers intérieurs et extérieurs, avec limons latéraux ou crémaillère centrale. Chaque escalier est fabriqué dans notre atelier à Peschadoires puis installé par nos soins. Garantie décennale sur tous nos ouvrages.',
        'cta' => 'Obtenez un devis pour votre projet d\'escalier.'
    ),
    'ferronnerie-dart' => array(
        'intro' => 'La ferronnerie d\'art est notre passion. AL Métallerie & Soudure crée des pièces uniques qui subliment votre intérieur et extérieur.',
        'details' => 'Marquises, auvents, grilles décoratives, luminaires, mobilier d\'art... Nous travaillons le fer forgé selon les techniques traditionnelles tout en intégrant les tendances contemporaines. Chaque création est une œuvre unique, façonnée à la main dans notre atelier. Restauration de ferronnerie ancienne également disponible pour préserver le patrimoine.',
        'cta' => 'Parlez-nous de votre projet de ferronnerie d\'art.'
    ),
    'grilles' => array(
        'intro' => 'Protection et esthétisme avec nos grilles de défense sur mesure. AL Métallerie & Soudure sécurise vos ouvertures sans compromettre le style.',
        'details' => 'Grilles de fenêtres, grilles de porte, grilles de soupirail : nous fabriquons tous types de grilles de protection. Modèles fixes ou ouvrants, designs classiques ou modernes. L\'acier thermolaqué garantit une durabilité optimale et un entretien minimal. Pose professionnelle incluse dans nos prestations.',
        'cta' => 'Demandez un devis pour vos grilles de protection.'
    ),
    'serrurerie' => array(
        'intro' => 'Services de serrurerie métallique par AL Métallerie & Soudure. Fabrication et pose de portes, portillons et systèmes de fermeture sur mesure.',
        'details' => 'Notre expertise en serrurerie couvre la fabrication de portes métalliques, portillons de jardin, trappes d\'accès et systèmes de verrouillage. Nous travaillons l\'acier et l\'inox pour des réalisations durables et sécurisées. Intégration de serrures multipoints, cylindres haute sécurité et systèmes de contrôle d\'accès selon vos besoins.',
        'cta' => 'Contactez-nous pour votre projet de serrurerie.'
    ),
    'mobilier-metallique' => array(
        'intro' => 'Mobilier métallique sur mesure : tables, chaises, étagères, verrières... AL Métallerie & Soudure crée le mobilier qui correspond exactement à vos envies.',
        'details' => 'Du mobilier industriel au design contemporain, nous réalisons toutes vos idées. Tables avec pieds en métal, bibliothèques sur mesure, verrières d\'intérieur, consoles, porte-manteaux... Chaque pièce est fabriquée selon vos dimensions et finitions souhaitées. Possibilité de combiner métal et bois pour un rendu chaleureux.',
        'cta' => 'Imaginez votre mobilier, nous le créons.'
    ),
    'vehicules' => array(
        'intro' => 'Aménagements métalliques pour véhicules par AL Métallerie & Soudure. Hard-tops, racks, protections et accessoires sur mesure.',
        'details' => 'Nous concevons des équipements métalliques pour tous types de véhicules : hard-tops pour pick-up, galeries de toit, protections de benne, racks à outils. Fabrication robuste en acier ou aluminium, adaptée à un usage intensif. Idéal pour les professionnels et les passionnés de plein air.',
        'cta' => 'Équipez votre véhicule sur mesure.'
    ),
    'autres' => array(
        'intro' => 'AL Métallerie & Soudure réalise tous vos projets métalliques sur mesure, même les plus originaux.',
        'details' => 'Piscines inox, récupérateurs d\'eau, structures décoratives, pièces techniques... Notre savoir-faire nous permet de répondre à toutes vos demandes. Chaque projet est étudié individuellement pour vous proposer la solution la plus adaptée. N\'hésitez pas à nous soumettre vos idées les plus créatives.',
        'cta' => 'Parlez-nous de votre projet unique.'
    ),
);

$current_seo = isset($seo_contents[$current_term->slug]) ? $seo_contents[$current_term->slug] : $seo_contents['autres'];
?>

<!-- Header Mobile avec bouton RETOUR -->
<?php get_template_part('template-parts/header', 'mobile'); ?>

<main class="mobile-taxonomy-page">
    <div class="mobile-taxonomy-container">
        
        <!-- Tag avec icône -->
        <div class="mobile-taxonomy-tag scroll-zoom">
            <span class="mobile-taxonomy-icon"><?php echo $current_icon; ?></span>
            <span><?php echo esc_html($current_term->name); ?></span>
        </div>

        <!-- Titre H1 SEO -->
        <h1 class="mobile-taxonomy-title scroll-fade scroll-delay-1">
            <?php echo esc_html(strtoupper($current_term->name)); ?>
        </h1>
        
        <!-- Introduction SEO -->
        <p class="mobile-taxonomy-intro scroll-fade scroll-delay-2">
            <?php echo esc_html($current_seo['intro']); ?>
        </p>

        <!-- Bloc SEO détaillé -->
        <div class="mobile-taxonomy-seo-block scroll-fade scroll-delay-3">
            <h2 class="mobile-seo-subtitle">
                Nos <?php echo esc_html(strtolower($current_term->name)); ?> sur mesure à Thiers
            </h2>
            <p class="mobile-seo-text">
                <?php echo esc_html($current_seo['details']); ?>
            </p>
            <p class="mobile-seo-cta">
                <strong><?php echo esc_html($current_seo['cta']); ?></strong>
            </p>
            <div class="mobile-seo-actions">
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="mobile-btn-cta">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                    Contactez-nous
                </a>
                <a href="tel:+33673333532" class="mobile-btn-phone">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    06 73 33 35 32
                </a>
            </div>
        </div>

        <!-- Compteur de réalisations -->
        <div class="mobile-taxonomy-count scroll-fade">
            <span class="count-number"><?php echo esc_html($current_term->count); ?></span>
            <span class="count-text"><?php echo _n('réalisation', 'réalisations', $current_term->count, 'almetal'); ?></span>
        </div>

        <?php
        // Requête pour les réalisations de cette catégorie
        $tax_query = new WP_Query(array(
            'post_type' => 'realisation',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'type_realisation',
                    'field' => 'slug',
                    'terms' => $current_term->slug,
                ),
            ),
        ));
        ?>

        <!-- Grille de réalisations -->
        <div class="mobile-archive-grid">
            <?php if ($tax_query->have_posts()) : ?>
                <?php while ($tax_query->have_posts()) : $tax_query->the_post(); ?>
                    <?php
                    $terms = get_the_terms(get_the_ID(), 'type_realisation');
                    $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
                    ?>
                    
                    <article class="mobile-realisation-card scroll-slide-up">
                        <div class="mobile-realisation-card-inner">
                            <div class="mobile-realisation-image">
                                <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                                
                                <!-- Badges positionnés en absolu par rapport à l'image -->
                                <?php if ($lieu) : ?>
                                    <div class="mobile-city-badge">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <?php echo almetal_city_link_html($lieu, 'mobile-city-link'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Badges catégories et matériaux en haut à droite -->
                                <div class="mobile-top-badges">
                                    <?php 
                                    $matiere = get_post_meta(get_the_ID(), '_almetal_matiere', true);
                                    if ($matiere) : 
                                    ?>
                                        <a href="<?php echo esc_url(almetal_get_matiere_url($matiere)); ?>" class="mobile-matiere-badge">
                                            <?php
                                            $matiere_labels = array(
                                                'acier' => 'Acier',
                                                'inox' => 'Inox',
                                                'aluminium' => 'Aluminium',
                                                'cuivre' => 'Cuivre',
                                                'laiton' => 'Laiton',
                                                'fer-forge' => 'Fer forgé',
                                                'mixte' => 'Mixte'
                                            );
                                            echo esc_html($matiere_labels[$matiere] ?? ucfirst($matiere));
                                            ?>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($terms && !is_wp_error($terms)) : ?>
                                        <?php foreach ($terms as $term) : ?>
                                            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="mobile-category-badge">
                                                <?php
                                                // Icônes SVG directement intégrées
                                                $mobile_icons = array(
                                                    'portails' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                                                    'garde-corps' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
                                                    'escaliers' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                                                    'pergolas' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
                                                    'grilles' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                                                    'ferronnerie-art' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                                                    'ferronnerie-dart' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                                                    'vehicules' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
                                                    'serrurerie' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
                                                    'mobilier-metallique' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
                                                    'autres' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>'
                                                );
                                                
                                                $icon_svg = isset($mobile_icons[$term->slug]) ? $mobile_icons[$term->slug] : $mobile_icons['autres'];
                                                echo $icon_svg;
                                                ?>
                                                <?php echo esc_html($term->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mobile-realisation-content">
                                <h3 class="mobile-realisation-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <?php if (has_excerpt()) : ?>
                                    <p class="mobile-realisation-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <span class="mobile-realisation-cta">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php esc_html_e('Voir le projet', 'almetal'); ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </article>
                    
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="mobile-archive-empty">
                    <?php esc_html_e('Aucune réalisation dans cette catégorie pour le moment.', 'almetal'); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Section Zone d'Intervention et Carte Interactive -->
        <div class="mobile-taxonomy-map-section">
        <h2 class="mobile-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            Zone d'Intervention - <?php echo esc_html($current_term->name); ?>
        </h2>
        
        <?php
        // Récupérer les villes depuis le CPT city_page
        $cities_from_cpt = array();
        $post_types = array('city_page', 'city-page', 'villes', 'ville', 'city');
        
        foreach ($post_types as $post_type) {
            if (post_type_exists($post_type)) {
                $cities = get_posts(array(
                    'post_type' => $post_type,
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                
                if ($cities && !is_wp_error($cities)) {
                    foreach ($cities as $city) {
                        $city_name = get_the_title($city->ID);
                        $city_name = str_replace(array(
                            'Ferronier à ',
                            'Ferronnier à ',
                            'Serrurier à ',
                            'Métallier ',
                            'AL Métallerie ',
                            'AL Métallerie'
                        ), '', $city_name);
                        $city_name = trim($city_name);
                        
                        // Coordonnées des villes depuis les meta fields
                        $lat = get_post_meta($city->ID, '_city_lat', true);
                        $lng = get_post_meta($city->ID, '_city_lng', true);
                        
                        if (!empty($lat) && !empty($lng)) {
                            $cities_from_cpt[$city_name] = array(floatval($lat), floatval($lng));
                        }
                    }
                    break; // Utiliser le premier CPT trouvé
                }
            }
        }
        
        // Utiliser les villes du CPT si trouvées, sinon fallback sur le tableau par défaut
        $main_cities = !empty($cities_from_cpt) ? array_keys($cities_from_cpt) : array(
            'Thiers', 'Clermont-Ferrand', 'Riom', 'Issoire', 
            'Ambert', 'Coudes', 'Peschadoires', 'Courpière',
            'Olliergues', 'Saint-Éloy-les-Mines', 'Aigueperse',
            'Besse-et-Saint-Anastaise', 'Chamalières', 'Châtel-Guyon'
        );
        ?>
        
        <!-- Carte interactive adaptée pour mobile -->
        <div id="taxonomy-map-mobile" style="height: 350px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); margin: 1rem 0;">
            <!-- La carte sera initialisée ici par JavaScript -->
        </div>
        
        <!-- Menu déroulant pour sélectionner une ville -->
        <div class="mobile-city-selector" style="margin: 1.5rem 0;">
            <select id="mobile-city-select" class="mobile-select" style="width: 100%; padding: 1rem; background: #2a2a2a; color: #fff; border: 2px solid #444; border-radius: 8px; font-size: 1rem;">
                <option value="">Sélectionner une ville...</option>
                <?php
                foreach ($main_cities as $city) {
                    echo '<option value="' . esc_attr($city) . '">' . esc_html($city) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Lien retour vers toutes les réalisations -->
        <div class="mobile-taxonomy-back scroll-fade">
            <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="mobile-back-all-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <?php esc_html_e('Voir toutes les réalisations', 'almetal'); ?>
            </a>
        </div>

    </div>
</main>

<script>
    // Préparer les données des villes pour la carte mobile
    var taxonomyCities = <?php
        $cities_data = array();
        
        // Utiliser les coordonnées du CPT si disponibles
        $main_cities_coords = !empty($cities_from_cpt) ? $cities_from_cpt : array(
            'Thiers' => array(45.8556, 3.5747),
            'Clermont-Ferrand' => array(45.7772, 3.0870),
            'Riom' => array(45.8931, 3.1137),
            'Issoire' => array(45.5439, 3.2525),
            'Ambert' => array(45.5489, 3.7511),
            'Coudes' => array(45.7344, 3.1972),
            'Peschadoires' => array(45.8567, 3.5634),
            'Courpière' => array(45.8683, 3.6189),
            'Olliergues' => array(45.7333, 3.7167),
            'Saint-Éloy-les-Mines' => array(46.2667, 3.4667),
            'Aigueperse' => array(45.9667, 3.3167),
            'Besse-et-Saint-Anastaise' => array(45.5167, 2.9833),
            'Chamalières' => array(45.7833, 3.1167),
            'Châtel-Guyon' => array(45.9667, 3.1167)
        );
        
        foreach ($main_cities_coords as $city => $coords) {
            // Compter le nombre de réalisations pour cette ville (toutes catégories, comme la version desktop)
            $realisations_query = new WP_Query(array(
                'post_type' => 'realisation',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => '_almetal_lieu',
                        'value' => $city,
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_almetal_lieu',
                        'value' => '%' . $city . '%',
                        'compare' => 'LIKE'
                    )
                )
            ));
            
            $projects_count = $realisations_query->found_posts;
            
            // Debug : afficher le nombre trouvé
            error_log('Ville: ' . $city . ' - Projets trouvés: ' . $projects_count);
            
            // N'ajouter la ville que s'il y a des projets
            if ($projects_count > 0) {
                $cities_data[] = array(
                    'name' => $city,
                    'lat' => $coords[0],
                    'lng' => $coords[1],
                    'count' => $projects_count,
                    'url' => home_url("/villes/metalier-" . sanitize_title($city) . "/")
                );
            }
        }
        
        echo json_encode($cities_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    ?>;
    
    // Charger Leaflet.js si ce n'est pas déjà fait
    if (typeof L === 'undefined') {
        console.log('Chargement de Leaflet.js depuis le CDN de secours...');
        // Charger aussi le CSS de Leaflet
        var leafletCSS = document.createElement('link');
        leafletCSS.rel = 'stylesheet';
        leafletCSS.href = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css';
        document.head.appendChild(leafletCSS);
        
        var leafletScript = document.createElement('script');
        leafletScript.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
        leafletScript.onload = function() {
            console.log('Leaflet.js chargé avec succès');
            // Initialiser la carte après le chargement
            initializeMobileMap();
        };
        leafletScript.onerror = function() {
            console.error('Impossible de charger Leaflet.js');
            // Afficher un message d'erreur à l'utilisateur
            document.getElementById('taxonomy-map-mobile').innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #2a2a2a; color: #fff; text-align: center; padding: 2rem;"><div><h3 style="color: #F08B18; margin-bottom: 1rem;">Carte temporairement indisponible</h3><p>Veuillez réessayer plus tard ou contactez-nous directement.</p></div></div>';
        };
        document.head.appendChild(leafletScript);
    } else {
        // Leaflet est déjà chargé, initialiser la carte
        initializeMobileMap();
    }
    
    function initializeMobileMap() {
        // Initialiser la carte mobile
        var map = L.map('taxonomy-map-mobile').setView([45.8556, 3.5747], 9);
        
        // Ajouter la couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors © CARTO',
            maxZoom: 19
        }).addTo(map);
        
        // Ajouter les marqueurs pour chaque ville
        taxonomyCities.forEach(function(city) {
            // Afficher TOUS les marqueurs, même si count = 0
            console.log('Ajout marqueur pour:', city.name, 'count:', city.count, 'lat:', city.lat, 'lng:', city.lng);
            
            // Vérifier que les coordonnées existent
            if (!city.lat || !city.lng) {
                console.warn('Coordonnées manquantes pour:', city.name);
                return;
            }
            
            // Créer un marqueur personnalisé
            var customIcon = L.divIcon({
                html: '<div style="background: #F08B18; width: 24px; height: 24px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><span style="transform: rotate(45deg); color: white; font-size: 12px; font-weight: bold;">' + (city.count || 0) + '</span></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -24],
                className: 'custom-mobile-map-marker'
            });
            
            var marker = L.marker([city.lat, city.lng], { icon: customIcon }).addTo(map);
            
            // Ajouter un popup
            var popupContent = '<strong>' + city.name + '</strong><br>' + 
                (city.count || 0) + ' projet' + ((city.count || 0) > 1 ? 's' : '') + '<br>' +
                '<a href="' + city.url + '" style="color: #F08B18;">Voir les réalisations</a>';
            
            marker.bindPopup(popupContent);
            
            // Centrer sur la ville si sélectionnée dans le menu déroulant
            var select = document.getElementById('mobile-city-select');
            if (select) {
                select.addEventListener('change', function() {
                    if (this.value === city.name) {
                        map.setView([city.lat, city.lng], 13);
                        marker.openPopup();
                    }
                });
            }
        });
        
        // Adapter la vue pour afficher tous les marqueurs
        if (taxonomyCities.length > 0) {
            var group = new L.featureGroup();
            taxonomyCities.forEach(function(city) {
                // Ajouter TOUS les marqueurs au groupe
                group.addLayer(L.marker([city.lat, city.lng]));
            });
            if (group.getLayers().length > 0) {
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }
    }
</script>

        <!-- Section Pourquoi Nous Choisir -->
        <div class="mobile-why-choose-section">
            <h2 class="mobile-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Pourquoi Choisir AL Métallerie
            </h2>
            
            <div class="mobile-features-grid">
                <div class="mobile-feature-item">
                    <div class="mobile-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                            <path d="M9 12l2 2 4-4"/>
                            <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1h18zM3 19c0 .552.448 1 1 1h16c.552 0 1-.448 1-1v-6c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6z"/>
                        </svg>
                    </div>
                    <h3>Garantie Décennale</h3>
                    <p>Toutes nos réalisations sont couvertes par une assurance décennale pour votre tranquillité.</p>
                </div>
                
                <div class="mobile-feature-item">
                    <div class="mobile-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Artisans Qualifiés</h3>
                    <p>Notre équipe de métalliers expérimentés maîtrise les techniques traditionnelles et modernes.</p>
                </div>
                
                <div class="mobile-feature-item">
                    <div class="mobile-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Matériaux Premium</h3>
                    <p>Nous sélectionnons les meilleurs matériaux (acier, inox, aluminium) pour une durabilité exceptionnelle.</p>
                </div>
            </div>
        </div>
        
        <!-- Section Questions Fréquentes -->
        <div class="mobile-faq-section">
            <h2 class="mobile-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Questions Fréquentes
            </h2>
            
            <div class="mobile-faq-list">
                <div class="mobile-faq-item">
                    <div class="mobile-faq-question">
                        Quels sont les délais de fabrication ?
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div class="mobile-faq-answer">
                        Les délais varient de 4 à 8 semaines selon la complexité du projet et les matériaux choisis.
                    </div>
                </div>
                
                <div class="mobile-faq-item">
                    <div class="mobile-faq-question">
                        Intervention dans quelles villes ?
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div class="mobile-faq-answer">
                        Nous intervenons dans tout le Puy-de-Dôme : Thiers, Clermont-Ferrand, Riom, Issoire et environs.
                    </div>
                </div>
                
                <div class="mobile-faq-item">
                    <div class="mobile-faq-question">
                        Quelle est la durée de garantie ?
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div class="mobile-faq-answer">
                        Nous offrons une garantie décennale sur la structure et 2 ans sur les finitions de nos réalisations.
                    </div>
                </div>
            </div>
        </div>

<?php get_footer(); ?>
