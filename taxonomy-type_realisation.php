<?php
/**
 * Template pour les pages de taxonomie type_realisation
 * (ex: /type-realisation/portails/, /type-realisation/escaliers/)
 * Design identique à la page archive /realisations/
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Récupérer le terme actuel
$current_term = get_queried_object();

// Icônes SVG par type de réalisation
$category_icons = array(
    'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
    'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
    'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
    'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
    'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
    'ferronnerie-art' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
    'ferronnerie-dart' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
    'vehicules' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
    'serrurerie' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
    'mobilier-metallique' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
    'autres' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
);

$current_icon = isset($category_icons[$current_term->slug]) ? $category_icons[$current_term->slug] : $category_icons['autres'];

// Contenus SEO par catégorie (150-200 mots pour améliorer le ratio texte)
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

<div class="archive-page taxonomy-type-realisation">
    <!-- Hero Section -->
    <div class="archive-hero">
        <div class="container">
            <h1 class="archive-title">
                <span class="archive-icon"><?php echo $current_icon; ?></span>
                <?php echo esc_html($current_term->name); ?>
            </h1>
            <p class="archive-subtitle">
                <?php echo esc_html($current_seo['intro']); ?>
            </p>
        </div>
    </div>
    
    <!-- Section SEO descriptive -->
    <div class="taxonomy-seo-content">
        <div class="container">
            <div class="seo-text-block">
                <h2>Nos <?php echo esc_html(strtolower($current_term->name)); ?> sur mesure à Thiers</h2>
                <p><?php echo esc_html($current_seo['details']); ?></p>
                <p class="seo-cta"><strong><?php echo esc_html($current_seo['cta']); ?></strong> 
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-link">Contactez-nous</a> 
                    ou appelez le <a href="tel:+33673333532" class="inline-link">06 73 33 35 32</a>.
                </p>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="archive-content">
        <div class="container">
            <!-- Compteur de résultats -->
            <div class="taxonomy-results-count">
                <span class="count-number"><?php echo esc_html($current_term->count); ?></span>
                <span class="count-text"><?php echo _n('réalisation', 'réalisations', $current_term->count, 'almetal'); ?></span>
            </div>

            <?php
            // Requête personnalisée pour charger TOUTES les réalisations de cette catégorie
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

            <?php if ($tax_query->have_posts()) : ?>
                <div class="archive-grid realisations-grid taxonomy-grid">
                    <?php
                    while ($tax_query->have_posts()) :
                        $tax_query->the_post();
                        
                        // Récupérer les types de réalisation
                        $types = get_the_terms(get_the_ID(), 'type_realisation');
                        $type_classes = '';
                        if ($types && !is_wp_error($types)) {
                            foreach ($types as $type) {
                                $type_classes .= ' type-' . $type->slug;
                            }
                        }
                        ?>
                        
                        <?php
                        // Arguments pour le template-part
                        $card_args = array(
                            'show_category_badges' => true,
                            'show_location_badge' => true,
                            'show_meta' => true,
                            'show_cta' => true, // Activer le bouton CTA dans les taxonomies
                            'is_first' => false,
                            'image_size' => 'medium_large'
                        );
                        
                        // Utiliser le template-part unifié
                        get_template_part('template-parts/card-realisation', null, $card_args);
                        ?>

                    <?php endwhile; ?>
                </div>

                <?php wp_reset_postdata(); ?>

            <?php else : ?>
                <div class="no-results">
                    <p><?php _e('Aucune réalisation dans cette catégorie pour le moment.', 'almetal'); ?></p>
                </div>
            <?php endif; ?>

            <!-- Lien retour vers toutes les réalisations -->
            <div class="taxonomy-back-link">
                <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    <?php _e('Voir toutes les réalisations', 'almetal'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <?php 
    // Afficher la FAQ stylisée
    if (function_exists('almetal_display_taxonomy_faq')) {
        almetal_display_taxonomy_faq();
    }
    ?>
</div>

<?php
get_footer();
