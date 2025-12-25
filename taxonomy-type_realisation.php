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

// Mettre en queue le CSS spécifique pour les pages catégories
wp_enqueue_style('taxonomy-seo', get_template_directory_uri() . '/assets/css/taxonomy-seo.css', array(), '1.0.0');

// Mettre en queue le JavaScript pour l'interactivité de la FAQ
wp_enqueue_script('taxonomy-faq', get_template_directory_uri() . '/assets/js/taxonomy-faq.js', array(), '1.0.0', true);

// Mettre en queue Leaflet.js pour la carte interactive
wp_enqueue_style('leaflet-css', 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4');
wp_enqueue_script('leaflet-js', 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js', array('jquery'), '1.9.4', true);

// Mettre en queue le JavaScript personnalisé pour la carte
wp_enqueue_script('taxonomy-map', get_template_directory_uri() . '/assets/js/taxonomy-map.js', array('jquery', 'leaflet-js'), '1.0.0', true);

// Ajouter un script inline pour vérifier le chargement
wp_add_inline_script('leaflet-js', '
    console.log("Leaflet version:", L ? L.version : "non chargé");
    if (typeof L === "undefined") {
        console.error("Leaflet.js n\'a pas pu être chargé depuis le CDN");
    }
');

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
        'cta' => 'Demandez votre devis gratuit pour un portail sur mesure.',
        'faq' => array(
            'Quels sont les délais de fabrication d\'un portail sur mesure ?' => 'Les délais varient de 4 à 8 semaines selon la complexité du design et les matériaux choisis. Nous vous fournissons un planning précis lors de la validation du devis.',
            'Quelle est la différence entre un portail en acier et en aluminium ?' => 'L\'acier est plus robuste et économique, idéal pour les grands portails. L\'aluminium est plus léger, ne rouille pas et convient parfaitement aux portails motorisés.',
            'Fournissez-vous la motorisation du portail ?' => 'Oui, nous sommes partenaires des meilleures marques (Somfy, Nice, BFT) et nous assurons l\'installation complète avec garantie décennale.',
            'Quel entretien nécessite un portail métallique ?' => 'Un portail thermolaqué nécessite peu d\'entretien : un nettoyage annuel à l\'eau savonneuse suffit. Le fer forgé nécessite une couche de protection tous les 5-7 ans.',
            'Quelle est la durée de garantie de vos portails ?' => 'Nous offrons une garantie décennale sur la structure et 2 ans sur la motorisation. Tous nos portails sont assurés pendant 10 ans en responsabilité civile professionnelle.'
        )
    ),
    'garde-corps' => array(
        'intro' => 'AL Métallerie & Soudure réalise des garde-corps sur mesure pour sécuriser vos escaliers, balcons et terrasses tout en apportant une touche esthétique à votre habitat.',
        'details' => 'Nos garde-corps sont conçus selon les normes de sécurité en vigueur (NF P01-012). Nous proposons différents styles : garde-corps à barreaux verticaux, à câbles inox, avec remplissage verre ou panneaux décoratifs. Matériaux disponibles : acier thermolaqué, inox brossé ou poli, fer forgé traditionnel. Chaque projet est étudié sur place pour garantir une adaptation parfaite à votre configuration.',
        'cta' => 'Contactez-nous pour un devis personnalisé.',
        'faq' => array(
            'Quelles sont les normes pour les garde-corps ?' => 'Les garde-corps doivent respecter la norme NF P01-012 : hauteur minimale de 1m pour les balcons, 0.9m pour les escaliers, et espacement maximum de 11cm entre les éléments.',
            'Quels matériaux choisir pour un garde-corps extérieur ?' => 'L\'inox 316 est idéal pour l\'extérieur (ne rouille pas), l\'acier thermolaqué offre un large choix de couleurs, et le fer forgé apporte un style traditionnel très recherché.',
            'Pouvez-vous installer des garde-corps sur mesure pour des formes particulières ?' => 'Oui, nous adaptons nos garde-corps à toutes les configurations : escaliers quart tournant, terrasses arrondies, balcons trapézoïdaux, etc.',
            'Quel est le prix moyen d\'un garde-corps sur mesure ?' => 'Les prix varient de 250€ à 800€ par mètre linéaire selon le matériau, le design et la complexité de l\'installation.',
            'Assurez-vous la mise en conformité des garde-corps existants ?' => 'Oui, nous pouvons rénover et mettre aux normes vos garde-corps existants en renforçant la structure ou en modifiant les espacements.'
        )
    ),
    'escaliers' => array(
        'intro' => 'Fabrication d\'escaliers métalliques sur mesure par AL Métallerie & Soudure. Escaliers droits, quart tournant, hélicoïdaux : nous réalisons tous types de configurations.',
        'details' => 'Nos escaliers allient solidité et design. Structure en acier ou inox, marches en métal, bois ou verre selon vos goûts. Nous concevons des escaliers intérieurs et extérieurs, avec limons latéraux ou crémaillère centrale. Chaque escalier est fabriqué dans notre atelier à Peschadoires puis installé par nos soins. Garantie décennale sur tous nos ouvrages.',
        'cta' => 'Obtenez un devis pour votre projet d\'escalier.',
        'faq' => array(
            'Quels types d\'escaliers métalliques fabriquez-vous ?' => 'Nous réalisons des escaliers droits, quart tournant, demi-tournant, hélicoïdaux, et sur mesure avec toutes les configurations possibles.',
            'Quelle est la hauteur de marche idéale pour un escalier confortable ?' => 'La hauteur idéale se situe entre 16 et 18cm, avec un giron de 25 à 30cm. Nous calculons le balancement optimal pour chaque projet.',
            'Pouvez-vous associer le métal à d\'autres matériaux ?' => 'Oui, nous combinons régulièrement l\'acier avec le bois (chêne, hêtre), le verre, ou la pierre pour des escaliers personnalisés.',
            'Quel est le délai de fabrication d\'un escalier sur mesure ?' => 'Comptez 6 à 10 semaines selon la complexité, incluant la prise de mesures, la fabrication en atelier et l\'installation.',
            'Vos escaliers sont-ils garantis ?' => 'Oui, nous appliquons la garantie décennale sur la structure et 2 ans sur les finitions. Tous nos escaliers sont conformes aux normes de sécurité.'
        )
    ),
    'ferronnerie-dart' => array(
        'intro' => 'La ferronnerie d\'art est notre passion. AL Métallerie & Soudure crée des pièces uniques qui subliment votre intérieur et extérieur.',
        'details' => 'Marquises, auvents, grilles décoratives, luminaires, mobilier d\'art... Nous travaillons le fer forgé selon les techniques traditionnelles tout en intégrant les tendances contemporaines. Chaque création est une œuvre unique, façonnée à la main dans notre atelier. Restauration de ferronnerie ancienne également disponible pour préserver le patrimoine.',
        'cta' => 'Parlez-nous de votre projet de ferronnerie d\'art.',
        'faq' => array(
            'Quelle est la différence entre ferronnerie et serrurerie ?' => 'La ferronnerie d\'art concerne les éléments décoratifs et architecturaux (grilles, portails, rampes), tandis que la serrurerie se concentre sur les éléments fonctionnels (serrures, fermures).',
            'Pouvez-vous reproduire des motifs anciens ?' => 'Oui, nous maîtrisons les techniques traditionnelles et pouvons reproduire ou restaurer des pièces d\'époque tout en respectant le style d\'origine.',
            'Quels types de créations en ferronnerie d\'art proposez-vous ?' => 'Nous créons des grilles décoratives, portails ornementaux, rampes d\'escalier, luminaires, mobilier, et toutes pièces sur mesure.',
            'Comment entretenir la ferronnerie d\'art ?' => 'Un traitement anti-corrosion est appliqué en usine. Un entretien annuel avec des produits adaptés préserve l\'aspect et la durabilité.',
            'Travaillez-vous pour les monuments historiques ?' => 'Oui, nous avons l\'expérience nécessaire pour les chantiers de restauration du patrimoine et nous respectons les contraintes architecturales.'
        )
    ),
    'grilles' => array(
        'intro' => 'Protection et esthétisme avec nos grilles de défense sur mesure. AL Métallerie & Soudure sécurise vos ouvertures sans compromettre le style.',
        'details' => 'Grilles de fenêtres, grilles de porte, grilles de soupirail : nous fabriquons tous types de grilles de protection. Modèles fixes ou ouvrants, designs classiques ou modernes. L\'acier thermolaqué garantit une durabilité optimale et un entretien minimal. Pose professionnelle incluse dans nos prestations.',
        'cta' => 'Demandez un devis pour vos grilles de protection.',
        'faq' => array(
            'Quels types de grilles de protection fabriquez-vous ?' => 'Grilles de fenêtre, de porte, de soupirail, de ventilation, et toutes protections sur mesure pour vos ouvertures.',
            'Les grilles sont-elles efficaces contre les effractions ?' => 'Oui, nos grilles en acier de 10mm avec soudures continues offrent une excellente protection. Nous pouvons aussi intégrer des serrures de sécurité.',
            'Pouvez-vous créer des grilles décoratives ?' => 'Oui, nous réalisons des grilles alliant sécurité et esthétique avec des motifs personnalisés qui s\'intègrent à votre architecture.',
            'Quelle est la différence entre grille fixe et ouvrante ?' => 'La grille fixe offre une sécurité maximale, la grille ouvrante permet l\'accès en cas d\'urgence (obligatoire pour certaines fenêtres).',
            'Comment fixez-vous les grilles ?' => 'Nous utilisons des fixations scellées dans la maçonnerie ou des visseries anti-effraction selon le support et le type de grille.'
        )
    ),
    'serrurerie' => array(
        'intro' => 'Services de serrurerie métallique par AL Métallerie & Soudure. Fabrication et pose de portes, portillons et systèmes de fermeture sur mesure.',
        'details' => 'Notre expertise en serrurerie couvre la fabrication de portes métalliques, portillons de jardin, trappes d\'accès et systèmes de verrouillage. Nous travaillons l\'acier et l\'inox pour des réalisations durables et sécurisées. Intégration de serrures multipoints, cylindres haute sécurité et systèmes de contrôle d\'accès selon vos besoins.',
        'cta' => 'Contactez-nous pour votre projet de serrurerie.',
        'faq' => array(
            'Quels types de portes métalliques fabriquez-vous ?' => 'Portes d\'entrée, portes de garage, portillons, portes de service, et toutes ouvertures sur mesure en acier ou aluminium.',
            'Proposez-vous des serrures haute sécurité ?' => 'Oui, nous intégrons les marques leaders (Fichet, Vachette, Mul-T-Lock) avec certification A2P et certification européenne.',
            'Pouvez-vous motoriser les portes existantes ?' => 'Oui, nous adaptons des motorisations sur portes sectionnelles, battantes, ou coulissantes avec télécommande et contrôle d\'accès.',
            'Quelle est la résistance au feu de vos portes ?' => 'Nous pouvons fabriquer des portes coupe-feu (CF 1h, 2h) certifiées et conformes à la réglementation ERP et habitation.',
            'Assurez-vous l\'entretien des serrures ?' => 'Oui, nous proposons des contrats de maintenance annuelle pour vérifier et entretenir vos systèmes de fermeture.'
        )
    ),
    'mobilier-metallique' => array(
        'intro' => 'Mobilier métallique sur mesure : tables, chaises, étagères, verrières... AL Métallerie & Soudure crée le mobilier qui correspond exactement à vos envies.',
        'details' => 'Du mobilier industriel au design contemporain, nous réalisons toutes vos idées. Tables avec pieds en métal, bibliothèques sur mesure, verrières d\'intérieur, consoles, porte-manteaux... Chaque pièce est fabriquée selon vos dimensions et finitions souhaitées. Possibilité de combiner métal et bois pour un rendu chaleureux.',
        'cta' => 'Imaginez votre mobilier, nous le créons.',
        'faq' => array(
            'Quels types de mobilier métallique pouvez-vous fabriquer ?' => 'Tables, chaises, étagères, verrières, bureaux, rangements, luminaires, et toutes créations personnalisées.',
            'Pouvez-vous associer le métal à d\'autres matériaux ?' => 'Oui, nous combinons l\'acier avec le bois massif, le verre, le béton, ou la pierre selon vos préférences.',
            'Le mobilier métallique est-il adapté à l\'intérieur ?' => 'Absolument, avec les bonnes finitions (thermolaquage, laques), le métal apporte un style moderne et durable à tout intérieur.',
            'Quels sont les délais de fabrication ?' => '4 à 6 semaines pour les pièces simples, 8 à 12 semaines pour les ensembles complexes ou les pièces sur-mesure.',
            'Pouvez-vous travailler à partir d\'un plan ou d\'une photo ?' => 'Oui, nous pouvons interpréter vos croquis, plans, ou même créer à partir d\'images inspiratrices.'
        )
    ),
    'vehicules' => array(
        'intro' => 'Aménagements métalliques pour véhicules par AL Métallerie & Soudure. Hard-tops, racks, protections et accessoires sur mesure.',
        'details' => 'Nous concevons des équipements métalliques pour tous types de véhicules : hard-tops pour pick-up, galeries de toit, protections de benne, racks à outils. Fabrication robuste en acier ou aluminium, adaptée à un usage intensif. Idéal pour les professionnels et les passionnés de plein air.',
        'cta' => 'Équipez votre véhicule sur mesure.',
        'faq' => array(
            'Quels types de véhicules pouvez-vous équiper ?' => 'Pick-ups, fourgons, utilitaires, 4x4, et véhicules de loisirs. Nous adaptons nos équipements à chaque modèle.',
            'Quels matériaux utilisez-vous pour les aménagements ?' => 'Acier pour la robustesse, aluminium pour la légèreté, inox pour les pièces exposées aux intempéries.',
            'Les aménagements sont-ils démontables ?' => 'Oui, nous concevons des systèmes modulaires et démontables pour pouvoir les retirer ou les transférer.',
            'Pouvez-vous intégrer des équipements électriques ?' => 'Oui, nous pouvons intégrer des éclairages, prises 12V/220V, systèmes de signalisation, et autres équipements électriques.',
            'Quelle est la durée de fabrication ?' => '2 à 4 semaines selon la complexité de l\'aménagement et les personnalisations demandées.'
        )
    ),
    'autres' => array(
        'intro' => 'AL Métallerie & Soudure réalise tous vos projets métalliques sur mesure, même les plus originaux.',
        'details' => 'Piscines inox, récupérateurs d\'eau, structures décoratives, pièces techniques... Notre savoir-faire nous permet de répondre à toutes vos demandes. Chaque projet est étudié individuellement pour vous proposer la solution la plus adaptée. N\'hésitez pas à nous soumettre vos idées les plus créatives.',
        'cta' => 'Parlez-nous de votre projet unique.',
        'faq' => array(
            'Quels types de projets pouvez-vous réaliser ?' => 'Toutes créations en métal : structures, pièces techniques, éléments décoratifs, et projets personnalisés.',
            'Travaillez-vous avec des architectes ?' => 'Oui, nous collaborons régulièrement avec des architectes, designers, et particuliers sur des projets sur mesure.',
            'Pouvez-vous travailler d\'après un simple croquis ?' => 'Oui, nous pouvons interpréter vos idées et vous proposer des solutions techniques et esthétiques.',
            'Quelle est votre zone d\'intervention ?' => 'Nous intervenons principalement dans le Puy-de-Dôme et les départements limitrophes (Auvergne).',
            'Comment obtenir un devis pour un projet original ?' => 'Contactez-nous avec vos idées, photos ou plans. Nous étudions votre projet gratuitement et vous proposons un devis détaillé.'
        )
    ),
);

$current_seo = isset($seo_contents[$current_term->slug]) ? $seo_contents[$current_term->slug] : $seo_contents['autres'];

// Récupérer une image aléatoire de la catégorie pour le hero
$random_image_query = new WP_Query(array(
    'post_type' => 'realisation',
    'posts_per_page' => 1,
    'orderby' => 'rand',
    'tax_query' => array(
        array(
            'taxonomy' => 'type_realisation',
            'field' => 'slug',
            'terms' => $current_term->slug,
        ),
    ),
));

$hero_background_image = '';
if ($random_image_query->have_posts()) {
    $random_image_query->the_post();
    $hero_background_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if (!$hero_background_image) {
        $hero_background_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    }
    wp_reset_postdata();
}

// Fallback image par défaut si aucune réalisation dans la catégorie
if (!$hero_background_image) {
    $hero_background_image = get_template_directory_uri() . '/assets/images/hero-default.webp';
}
?>

<div class="archive-page taxonomy-type-realisation">
    <!-- Hero Section -->
    <div class="archive-hero" style="background-image: url('<?php echo esc_url($hero_background_image); ?>');">
        <div class="hero-overlay"></div>
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
    
    <!-- Section Zone d'Intervention et Carte Interactive -->
    <div class="taxonomy-zone-intervention">
        <div class="container">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Zone d'Intervention - <?php echo esc_html($current_term->name); ?>
            </h2>
            <p class="section-subtitle">
                Intervention rapide pour vos <?php echo esc_html(strtolower($current_term->name)); ?> dans tout le département du Puy-de-Dôme (63) et les régions limitrophes
            </p>
            
            <!-- Carte interactive en haut -->
            <div id="taxonomy-map" style="height: 500px; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); margin-bottom: 2rem;">
                <!-- La carte sera initialisée ici par JavaScript -->
            </div>
            
            <!-- Boutons des villes en dessous -->
            <div class="cities-grid cities-grid-compact">
                <?php
                // Chercher les pages villes dans le custom post type du plugin
                $city_pages = array();
                
                // Essayer différents noms de custom post type possibles
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
                                // Récupérer le nom de la ville
                                $city_name = get_the_title($city->ID);
                                
                                // Nettoyer le nom en enlevant tous les préfixes indésirables
                                $city_name = str_replace(array(
                                    'Ferronier à ',
                                    'Ferronnier à ',
                                    'Serrurier à ',
                                    'Métallier ',
                                    'AL Métallerie ',
                                    'AL Métallerie'
                                ), '', $city_name);
                                $city_name = trim($city_name);
                                
                                if (!empty($city_name)) {
                                    $city_pages[$city_name] = get_permalink($city->ID);
                                }
                            }
                        }
                    }
                }
                
                // Si on a trouvé des pages villes, les afficher
                if (!empty($city_pages)) {
                    foreach ($city_pages as $city_name => $city_url) {
                        $city_slug = sanitize_title($city_name);
                        echo '<div class="city-item">';
                        echo '<a href="' . esc_url($city_url) . '" class="city-link" data-city="' . esc_attr($city_slug) . '">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
                        echo esc_html($city_name);
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    // Fallback : afficher les villes principales sans liens
                    $main_cities = array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire');
                    foreach ($main_cities as $city) {
                        $city_slug = sanitize_title($city);
                        echo '<div class="city-item">';
                        echo '<span class="city-name" data-city="' . esc_attr($city_slug) . '" style="opacity: 0.7; cursor: pointer;">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
                        echo esc_html($city);
                        echo '</span>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            
            <div class="zone-info">
                <p><strong>Intervention sous 48h</strong> pour toute demande urgente. Devis gratuit sur place dans un rayon de 50km autour de notre atelier à Peschadoires.</p>
            </div>
        </div>
    </div>
    
    <script>
        // Préparer les données des villes pour la carte
        var taxonomyCities = <?php
            $cities_data = array();
            
            // Récupérer les villes du plugin
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
                            
                            // Si les coordonnées existent, ajouter la ville à la carte
                            if (!empty($lat) && !empty($lng)) {
                                // Compter le nombre de réalisations pour cette ville
                                $realisations_query = new WP_Query(array(
                                    'post_type' => 'realisation',
                                    'posts_per_page' => -1,
                                    'meta_query' => array(
                                        array(
                                            'key' => 'ville_realisation',
                                            'value' => $city_name,
                                            'compare' => 'LIKE'
                                        )
                                    )
                                ));
                                $projects_count = $realisations_query->found_posts;
                                
                                $cities_data[] = array(
                                    'name' => $city_name,
                                    'lat' => floatval($lat),
                                    'lng' => floatval($lng),
                                    'url' => get_permalink($city->ID),
                                    'projects' => $projects_count,
                                    'rating' => '4.8' // Note Google Business
                                );
                            }
                        }
                    }
                }
            }
            
            echo json_encode($cities_data);
        ?>;
        
        // Charger Leaflet.js si ce n'est pas déjà fait
        if (typeof L === 'undefined') {
            console.log('Chargement de Leaflet.js depuis le CDN de secours...');
            var leafletScript = document.createElement('script');
            leafletScript.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
            leafletScript.onload = function() {
                console.log('Leaflet.js chargé avec succès');
                // Initialiser la carte après le chargement
                initializeTaxonomyMap();
            };
            leafletScript.onerror = function() {
                console.error('Impossible de charger Leaflet.js');
                // Afficher un message d'erreur à l'utilisateur
                document.getElementById('taxonomy-map').innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #2a2a2a; color: #fff; text-align: center; padding: 2rem;"><div><h3 style="color: #F08B18; margin-bottom: 1rem;">Carte temporairement indisponible</h3><p>Veuillez réessayer plus tard ou contactez-nous directement.</p></div></div>';
            };
            document.head.appendChild(leafletScript);
        } else {
            // Leaflet est déjà chargé, initialiser la carte
            initializeTaxonomyMap();
        }
        
        function initializeTaxonomyMap() {
            // Appeler la fonction principale du script taxonomy-map.js
            if (typeof initializeMap === 'function') {
                initializeMap();
            }
        }
    </script>
    
    <!-- Section Pourquoi Nous Choisir -->
    <div class="taxonomy-why-choose">
        <div class="container">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Pourquoi Choisir AL Métallerie pour Vos <?php echo esc_html($current_term->name); ?>
            </h2>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4"/>
                            <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1h18zM3 19c0 .552.448 1 1 1h16c.552 0 1-.448 1-1v-6c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6z"/>
                        </svg>
                    </div>
                    <h3>Garantie Décennale</h3>
                    <p>Toutes nos <?php echo esc_html(strtolower($current_term->name)); ?> sont couvertes par une assurance décennale pour votre tranquillité d'esprit.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Artisans Qualifiés</h3>
                    <p>Notre équipe de métalliers expérimentés maîtrise parfaitement les techniques traditionnelles et modernes.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Matériaux Premium</h3>
                    <p>Nous sélectionnons les meilleurs matériaux (acier, inox, aluminium) pour une durabilité exceptionnelle.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Fabrication Française</h3>
                    <p>Toutes nos créations sont 100% fabriquées dans notre atelier à Peschadoires, Auvergne.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Devis Personnalisé</h3>
                    <p>Étude gratuite de votre projet avec des plans détaillés et un devis adapté à votre budget.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3>Installation Professionnelle</h3>
                    <p>Notre équipe assure la pose complète avec respect des normes et du chantier.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section FAQ Spécifique -->
    <div class="taxonomy-faq-section">
        <div class="container">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Questions Fréquemment Posées - <?php echo esc_html($current_term->name); ?>
            </h2>
            
            <div class="faq-container">
                <?php if (isset($current_seo['faq']) && !empty($current_seo['faq'])): ?>
                    <?php $faq_index = 0; ?>
                    <?php foreach ($current_seo['faq'] as $question => $answer): ?>
                        <div class="faq-item <?php echo $faq_index === 0 ? 'active' : ''; ?>">
                            <div class="faq-question">
                                <h3><?php echo esc_html($question); ?></h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <p><?php echo esc_html($answer); ?></p>
                            </div>
                        </div>
                        <?php $faq_index++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
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
