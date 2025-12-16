<?php
/**
 * Template pour afficher une page Matière
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Récupérer les données de la matière
$matiere_id = get_the_ID();
$matiere_title = get_the_title();
$matiere_slug = get_post_meta($matiere_id, '_almetal_matiere_slug', true) ?: sanitize_title($matiere_title);
$matiere_couleur = get_post_meta($matiere_id, '_almetal_matiere_couleur', true) ?: '#F08B18';
$proprietes = get_post_meta($matiere_id, '_almetal_matiere_proprietes', true);
$avantages = get_post_meta($matiere_id, '_almetal_matiere_avantages', true);
$applications = get_post_meta($matiere_id, '_almetal_matiere_applications', true);
$intro_text = get_post_meta($matiere_id, '_almetal_matiere_intro', true);
$faq_raw = get_post_meta($matiere_id, '_almetal_matiere_faq', true);
$meta_title = get_post_meta($matiere_id, '_almetal_matiere_meta_title', true);
$meta_description = get_post_meta($matiere_id, '_almetal_matiere_meta_description', true);

// Image hero
$hero_image = get_the_post_thumbnail_url($matiere_id, 'full');
if (!$hero_image) {
    $hero_image = get_template_directory_uri() . '/assets/images/hero/hero-1.png';
}

// Récupérer les réalisations liées à cette matière
$realisations = almetal_get_realisations_by_matiere($matiere_slug, 6);

// Parser les propriétés, avantages et applications
$proprietes_array = !empty($proprietes) ? array_filter(array_map('trim', explode("\n", $proprietes))) : array();
$avantages_array = !empty($avantages) ? array_filter(array_map('trim', explode("\n", $avantages))) : array();
$applications_array = !empty($applications) ? array_filter(array_map('trim', explode("\n", $applications))) : array();

// Parser la FAQ
$faq_items = array();
if (!empty($faq_raw)) {
    $faq_lines = array_filter(array_map('trim', explode("\n", $faq_raw)));
    foreach ($faq_lines as $line) {
        if (strpos($line, '|') !== false) {
            list($question, $answer) = explode('|', $line, 2);
            $faq_items[] = array(
                'question' => trim($question),
                'answer' => trim($answer),
            );
        }
    }
}

if (function_exists('almetal_is_mobile') && almetal_is_mobile()) {
    get_template_part('template-parts/header', 'mobile');
}
?>

<style>
    :root {
        --matiere-color: <?php echo esc_attr($matiere_couleur); ?>;
    }
</style>

<article id="matiere-<?php echo esc_attr($matiere_slug); ?>" class="single-matiere">
    
    <!-- Hero Section -->
    <section class="matiere-hero" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%), url('<?php echo esc_url($hero_image); ?>');">
        <div class="matiere-hero__container">
            <h1 class="matiere-hero__title">
                <span class="matiere-hero__label">Métallerie</span>
                <?php echo esc_html($matiere_title); ?>
            </h1>
            
            <?php if ($intro_text) : ?>
                <p class="matiere-hero__intro"><?php echo esc_html($intro_text); ?></p>
            <?php else : ?>
                <p class="matiere-hero__intro">
                    Découvrez nos réalisations en <?php echo esc_html(strtolower($matiere_title)); ?> : portails, garde-corps, escaliers, pergolas et bien plus. 
                    Artisan métallier qualifié à Thiers, nous travaillons l'<?php echo esc_html(strtolower($matiere_title)); ?> avec passion et expertise.
                </p>
            <?php endif; ?>
            
            <div class="matiere-hero__stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo count($realisations); ?>+</span>
                    <span class="stat-label">Réalisations</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">15+</span>
                    <span class="stat-label">Années d'expérience</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Sur mesure</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Propriétés & Avantages -->
    <?php if (!empty($proprietes_array) || !empty($avantages_array)) : ?>
    <section class="matiere-properties">
        <div class="container">
            <div class="properties-grid">
                
                <?php if (!empty($proprietes_array)) : ?>
                <div class="properties-card">
                    <div class="properties-card__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                    </div>
                    <h2 class="properties-card__title">Propriétés techniques</h2>
                    <ul class="properties-list">
                        <?php foreach ($proprietes_array as $prop) : ?>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php echo esc_html($prop); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($avantages_array)) : ?>
                <div class="properties-card">
                    <div class="properties-card__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h2 class="properties-card__title">Avantages</h2>
                    <ul class="properties-list">
                        <?php foreach ($avantages_array as $avantage) : ?>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php echo esc_html($avantage); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Applications / Types de réalisations -->
    <?php if (!empty($applications_array)) : ?>
    <section class="matiere-applications">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </span>
                Nos réalisations en <?php echo esc_html(strtolower($matiere_title)); ?>
            </h2>
            
            <div class="applications-grid">
                <?php foreach ($applications_array as $index => $app) : ?>
                    <div class="application-item" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <span class="application-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F08B18" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </span>
                        <span class="application-name"><?php echo esc_html($app); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Galerie des réalisations -->
    <?php if (!empty($realisations)) : ?>
    <section class="matiere-realisations">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </span>
                Réalisations en <?php echo esc_html(strtolower($matiere_title)); ?>
            </h2>
            
            <div class="realisations-grid">
                <?php foreach ($realisations as $realisation) : 
                    $thumb = get_the_post_thumbnail_url($realisation->ID, 'medium_large');
                    if (!$thumb) {
                        $thumb = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
                    }
                    $lieu = get_post_meta($realisation->ID, '_almetal_lieu', true);
                    $terms = get_the_terms($realisation->ID, 'type_realisation');
                    $type_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
                ?>
                    <article class="realisation-card">
                        <a href="<?php echo get_permalink($realisation->ID); ?>" class="realisation-card__link">
                            <div class="realisation-card__image">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($realisation->post_title); ?>" loading="lazy">
                                <div class="realisation-card__overlay">
                                    <span class="view-project">Voir le projet</span>
                                </div>
                            </div>
                            <div class="realisation-card__content">
                                <h3 class="realisation-card__title"><?php echo esc_html($realisation->post_title); ?></h3>
                                <div class="realisation-card__meta">
                                    <?php if ($type_name) : ?>
                                        <span class="meta-type"><?php echo esc_html($type_name); ?></span>
                                    <?php endif; ?>
                                    <?php if ($lieu) : ?>
                                        <span class="meta-lieu">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <?php echo esc_html($lieu); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <div class="realisations-cta">
                <a href="<?php echo home_url('/realisations/'); ?>" class="btn-view-all">
                    <span class="circle" aria-hidden="true">
                        <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none">
                            <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="button-text">Voir toutes nos réalisations</span>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Contenu éditeur -->
    <?php if (get_the_content()) : ?>
    <section class="matiere-content">
        <div class="container">
            <div class="content-wrapper">
                <?php the_content(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- FAQ -->
    <?php if (!empty($faq_items)) : ?>
    <section class="matiere-faq">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </span>
                Questions fréquentes
            </h2>
            
            <div class="faq-list">
                <?php foreach ($faq_items as $index => $faq) : ?>
                    <div class="faq-item" data-index="<?php echo $index; ?>">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php echo esc_html($faq['question']); ?></span>
                            <svg class="faq-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php echo esc_html($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- CTA Contact -->
    <section class="matiere-cta">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Un projet en <?php echo esc_html(strtolower($matiere_title)); ?> ?</h2>
                    <p>Contactez-nous pour discuter de votre projet. Devis gratuit et personnalisé.</p>
                </div>
                <a href="<?php echo home_url('/contact/'); ?>" class="cta-button">
                    <span class="circle" aria-hidden="true">
                        <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none">
                            <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="button-text">Demander un devis</span>
                </a>
            </div>
        </div>
    </section>
    
</article>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        question.addEventListener('click', () => {
            const isOpen = item.classList.contains('active');
            
            // Fermer tous les autres
            faqItems.forEach(other => {
                other.classList.remove('active');
                other.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
            });
            
            // Toggle l'item actuel
            if (!isOpen) {
                item.classList.add('active');
                question.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
</script>

<?php get_footer(); ?>
