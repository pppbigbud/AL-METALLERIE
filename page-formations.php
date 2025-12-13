<?php
/**
 * Template Name: Formations
 * Description: Page parente des formations en ferronnerie - Intégration Training Manager
 * Design cohérent avec archive-pages
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// SEO Meta Tags pour la page Formations
if (!function_exists('almetal_formations_seo_meta')) {
    function almetal_formations_seo_meta() {
    ?>
    <title>Formations Soudure et Ferronnerie | Stages Particuliers &amp; Professionnels | Thiers (63)</title>
    <meta name="description" content="Formations en soudure et ferronnerie d'art à Thiers (63). Stages découverte pour particuliers, formations certifiantes pour professionnels. Atelier équipé, formateurs experts. Devis gratuit.">
    <link rel="canonical" href="<?php echo esc_url(home_url('/formations/')); ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Formations Soudure et Ferronnerie | AL Métallerie Thiers (63)">
    <meta property="og:description" content="Formations en soudure et ferronnerie d'art. Stages découverte pour particuliers, formations certifiantes pour professionnels. Atelier équipé à Thiers.">
    <meta property="og:url" content="<?php echo esc_url(home_url('/formations/')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="AL Métallerie & Soudure">
    
    <!-- Schema.org Course -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": "Formations Soudure et Ferronnerie - AL Métallerie",
        "description": "Formations en soudure et ferronnerie d'art à Thiers (63). Stages pour particuliers et professionnels.",
        "url": "<?php echo esc_url(home_url('/formations/')); ?>",
        "numberOfItems": 2,
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "item": {
                    "@type": "Course",
                    "name": "Formations Particuliers - Stages Découverte Ferronnerie",
                    "description": "Stages découverte et perfectionnement en ferronnerie d'art pour les passionnés. De 1 à 5 jours, tous niveaux.",
                    "provider": {
                        "@type": "Organization",
                        "name": "AL Métallerie & Soudure",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "Lieu-dit Les Parrons",
                            "addressLocality": "Peschadoires",
                            "postalCode": "63920",
                            "addressCountry": "FR"
                        }
                    },
                    "url": "<?php echo esc_url(home_url('/formations-particuliers/')); ?>"
                }
            },
            {
                "@type": "ListItem",
                "position": 2,
                "item": {
                    "@type": "Course",
                    "name": "Formations Professionnels - Certifications Métallerie",
                    "description": "Formations certifiantes et qualifiantes en métallerie pour professionnels du bâtiment et artisans en reconversion. Financement CPF/Pôle Emploi.",
                    "provider": {
                        "@type": "Organization",
                        "name": "AL Métallerie & Soudure",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "Lieu-dit Les Parrons",
                            "addressLocality": "Peschadoires",
                            "postalCode": "63920",
                            "addressCountry": "FR"
                        }
                    },
                    "url": "<?php echo esc_url(home_url('/formations-professionnels/')); ?>"
                }
            }
        ]
    }
    </script>
    <?php
    }
    add_action('wp_head', 'almetal_formations_seo_meta', 1);
}

// Désactiver le titre WordPress par défaut pour cette page
add_filter('pre_get_document_title', function($title) {
    return 'Formations Soudure et Ferronnerie | Stages Particuliers & Professionnels | Thiers (63)';
}, 999);

get_header();
?>

<div class="archive-page formations-archive">
    <!-- Hero Section -->
    <div class="archive-hero">
        <div class="container">
            <h1 class="archive-title">
                <svg class="archive-icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <?php _e('Nos Formations en Ferronnerie', 'almetal'); ?>
            </h1>
            <p class="archive-subtitle">
                Découvrez nos <strong>formations professionnelles en ferronnerie d'art</strong> et métallerie. 
                Que vous soyez <strong>particulier passionné</strong> ou <strong>professionnel en reconversion</strong>, 
                nous vous accompagnons dans l'apprentissage des <em>techniques traditionnelles</em> et <em>modernes</em>.
            </p>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="archive-content">
        <div class="container">
            
            <!-- Section Prochaines Formations -->
            <section class="formations-upcoming-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Prochaines sessions disponibles
                </h2>
                <div class="formations-upcoming-wrapper">
                    <?php 
                    // Shortcode du plugin Training Manager
                    if (shortcode_exists('training_upcoming')) {
                        echo do_shortcode('[training_upcoming count="4"]');
                    } else {
                        echo '<p class="no-formations">Aucune formation programmée pour le moment. Contactez-nous pour connaître les prochaines dates.</p>';
                    }
                    ?>
                </div>
            </section>

            <!-- Grille des catégories de formations -->
            <section class="formations-categories-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Nos types de formations
                </h2>
                
                <div class="formations-categories-grid">
                    <!-- Carte Particuliers -->
                    <a href="<?php echo esc_url(home_url('/formations-particuliers')); ?>" class="formation-category-card">
                        <div class="formation-category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="formation-category-content">
                            <h3>Formations Particuliers</h3>
                            <p>Stages découverte et perfectionnement pour les passionnés. Initiez-vous à la ferronnerie d'art dans notre atelier.</p>
                            <ul class="formation-category-features">
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Stages de 1 à 5 jours
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Petits groupes (max 6)
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Tous niveaux acceptés
                                </li>
                            </ul>
                            <span class="formation-category-cta">
                                Voir les formations
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>

                    <!-- Carte Professionnels -->
                    <a href="<?php echo esc_url(home_url('/formations-professionnels')); ?>" class="formation-category-card">
                        <div class="formation-category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                        </div>
                        <div class="formation-category-content">
                            <h3>Formations Professionnels</h3>
                            <p>Formations certifiantes et qualifiantes pour les professionnels du bâtiment et artisans en reconversion.</p>
                            <ul class="formation-category-features">
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Certification professionnelle
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Formation de 3 à 12 mois
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Financement CPF/Pôle Emploi
                                </li>
                            </ul>
                            <span class="formation-category-cta">
                                Voir les formations
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Calendrier des formations -->
            <section class="formations-calendar-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Calendrier des formations
                </h2>
                <div class="formations-calendar-wrapper">
                    <?php 
                    // Calendrier du plugin Training Manager
                    if (shortcode_exists('training_calendar')) {
                        echo do_shortcode('[training_calendar]');
                    } else {
                        echo '<p class="no-calendar">Le calendrier des formations sera bientôt disponible.</p>';
                    }
                    ?>
                </div>
            </section>

            <!-- Section avantages -->
            <section class="formations-advantages-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Pourquoi choisir nos formations ?
                </h2>
                <div class="formations-advantages-grid">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            </svg>
                        </div>
                        <h3>Atelier équipé</h3>
                        <p>Accès à un atelier professionnel avec tout le matériel nécessaire : forge, enclume, outils de découpe et de soudure.</p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <h3>Formateurs experts</h3>
                        <p>Apprenez auprès d'artisans ferronniers expérimentés, passionnés par la transmission de leur savoir-faire.</p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                        </div>
                        <h3>Pratique intensive</h3>
                        <p>80% de pratique pour maîtriser rapidement les gestes techniques et créer vos propres réalisations.</p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <h3>Suivi personnalisé</h3>
                        <p>Groupes restreints pour un accompagnement individualisé et adapté à votre niveau et vos objectifs.</p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        </div>
                        <h3>Repartez avec votre création</h3>
                        <p>À la fin de chaque stage, repartez avec l'objet que vous avez fabriqué : un souvenir unique.</p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <h3>Horaires flexibles</h3>
                        <p>Des créneaux adaptés à vos disponibilités : stages en semaine, week-end ou vacances scolaires.</p>
                    </div>
                </div>
            </section>

            <!-- CTA Contact -->
            <section class="formations-cta-section">
                <div class="formations-cta-content">
                    <h2>Une question sur nos formations ?</h2>
                    <p>Contactez-nous pour obtenir plus d'informations ou pour réserver votre place.</p>
                    <div class="formations-cta-buttons">
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            Nous contacter
                        </a>
                        <a href="tel:+33673333532" class="btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            06 73 33 35 32
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<?php
get_footer();
