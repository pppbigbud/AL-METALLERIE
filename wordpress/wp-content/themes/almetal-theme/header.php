<?php
/**
 * Header du thème
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Couleur du thème pour les navigateurs -->
    <meta name="theme-color" content="#F08B18">
    <meta name="msapplication-TileColor" content="#F08B18">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/android-chrome-512x512.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/site.webmanifest">
    <meta name="msapplication-config" content="<?php echo get_template_directory_uri(); ?>/browserconfig.xml">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="header-container">
            <?php
            // Détecter si on est sur mobile
            $is_mobile = almetal_is_mobile();
            ?>
            
            <?php if (!$is_mobile) : ?>
                <!-- VERSION DESKTOP : Mega Menu -->
                
                <div class="header-mega">
                    <nav class="header-mega__nav">
                        <!-- Liens gauche -->
                        <ul class="header-mega__list header-mega__list--left">
                            <!-- Accueil -->
                            <li class="header-mega__item">
                                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Accueil', 'almetal'); ?></a>
                            </li>
                            
                            <!-- Réalisations avec mega menu -->
                            <li class="header-mega__item has-megamenu">
                                <a href="<?php echo esc_url(home_url('/?post_type=realisation')); ?>">
                                    <span><?php esc_html_e('Réalisations', 'almetal'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="5" viewBox="0 0 9 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.612712 0.200408C0.916245 -0.081444 1.39079 -0.0638681 1.67265 0.239665L4.37305 3.14779L7.07345 0.239665C7.35531 -0.0638681 7.82986 -0.081444 8.13339 0.200408C8.43692 0.48226 8.4545 0.956809 8.17265 1.26034L4.92265 4.76034C4.78074 4.91317 4.5816 5 4.37305 5C4.1645 5 3.96536 4.91317 3.82345 4.76034L0.573455 1.26034C0.291603 0.956809 0.309179 0.48226 0.612712 0.200408Z" fill="currentColor" />
                                    </svg>
                                </a>
                                <div class="megamenu-wrapper">
                                    <div class="megamenu-categories">
                                        <div class="megamenu-categories__title"><?php esc_html_e('Catégories', 'almetal'); ?></div>
                                        <ul class="megamenu-categories__list">
                                            <?php
                                            $categories = get_terms(array(
                                                'taxonomy' => 'type_realisation',
                                                'hide_empty' => true,
                                                'orderby' => 'count',
                                                'order' => 'DESC',
                                                'number' => 4,
                                            ));
                                            if ($categories && !is_wp_error($categories)) :
                                                foreach ($categories as $index => $category) :
                                                    $active_class = ($index === 0) ? ' active' : '';
                                                    // Classes responsive pour masquer les catégories sur petits écrans
                                                    $responsive_class = '';
                                                    if ($index >= 3) {
                                                        $responsive_class = ' hide-on-laptop'; // Masquer 4ème catégorie sur laptop
                                                    }
                                                    if ($index >= 2) {
                                                        $responsive_class .= ' hide-on-tablet'; // Masquer 3ème et 4ème sur tablet
                                                    }
                                                    ?>
                                                    <li class="megamenu-categories__item<?php echo $active_class . $responsive_class; ?>" data-category="<?php echo esc_attr($category->slug); ?>" data-category-id="<?php echo esc_attr($category->term_id); ?>" data-icon-slug="<?php echo esc_attr($category->slug); ?>">
                                                        <?php
                                                        // Icônes par catégorie
                                                        $category_icons = array(
                                                            'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                                                            'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
                                                            'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                                                            'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
                                                            'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                                                            'ferronnerie-art' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
                                                        );
                                                        
                                                        $icon = isset($category_icons[$category->slug]) ? $category_icons[$category->slug] : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>';
                                                        ?>
                                                        <div class="megamenu-categories__icon">
                                                            <?php echo $icon; ?>
                                                        </div>
                                                        <a href="<?php echo esc_url(get_term_link($category)); ?>">
                                                            <span class="megamenu-categories__name"><?php echo esc_html($category->name); ?></span>
                                                            <span class="megamenu-categories__count"><?php echo esc_html($category->count); ?> réalisations</span>
                                                        </a>
                                                        <svg class="megamenu-categories__arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none">
                                                            <path d="M0.5 6.99996H15.5M15.5 6.99996L9.66667 1.16663M15.5 6.99996L9.66667 12.8333" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        
                                                        <!-- Contenu de cette catégorie (caché par défaut) -->
                                                        <div class="megamenu-category-content" data-category-content="<?php echo esc_attr($category->slug); ?>">
                                                            <div class="megamenu-content__grid">
                                                                <?php
                                                                $category_realisations = new WP_Query(array(
                                                                    'post_type' => 'realisation',
                                                                    'posts_per_page' => 3,
                                                                    'tax_query' => array(
                                                                        array(
                                                                            'taxonomy' => 'type_realisation',
                                                                            'field' => 'term_id',
                                                                            'terms' => $category->term_id,
                                                                        ),
                                                                    ),
                                                                ));
                                                                if ($category_realisations->have_posts()) :
                                                                    $card_index = 0;
                                                                    while ($category_realisations->have_posts()) : $category_realisations->the_post();
                                                                        // Classes responsive pour masquer les cartes supplémentaires
                                                                        $card_responsive_class = '';
                                                                        if ($card_index >= 2) {
                                                                            $card_responsive_class = ' hide-on-laptop hide-on-tablet'; // Masquer 3ème carte sur laptop et tablet
                                                                        }
                                                                        $card_index++;
                                                                        ?>
                                                                        <a href="<?php the_permalink(); ?>" class="megamenu-card<?php echo $card_responsive_class; ?>">
                                                                            <div class="megamenu-card__image">
                                                                                <?php if (has_post_thumbnail()) : ?>
                                                                                    <?php the_post_thumbnail('medium'); ?>
                                                                                <?php else : ?>
                                                                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg'); ?>" alt="<?php the_title(); ?>">
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="megamenu-card__content">
                                                                                <div class="megamenu-card__title"><?php the_title(); ?></div>
                                                                                <div class="megamenu-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></div>
                                                                                <span class="megamenu-card__btn">
                                                                                    <span class="circle" aria-hidden="true">
                                                                                        <svg class="icon arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                    <span class="button-text">En savoir +</span>
                                                                                </span>
                                                                            </div>
                                                                        </a>
                                                                        <?php
                                                                    endwhile;
                                                                    wp_reset_postdata();
                                                                else :
                                                                    ?>
                                                                    <p style="color: rgba(255, 255, 255, 0.7);">Aucune réalisation dans cette catégorie.</p>
                                                                    <?php
                                                                endif;
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php
                                                endforeach;
                                            else :
                                                // Fallback si pas de catégories
                                                ?>
                                                <li class="megamenu-categories__item active">
                                                    <a href="<?php echo esc_url(home_url('/?post_type=realisation')); ?>">
                                                        <span class="megamenu-categories__name">Toutes les réalisations</span>
                                                        <span class="megamenu-categories__count">Voir tout</span>
                                                    </a>
                                                </li>
                                                <?php
                                            endif;
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="megamenu-content">
                                        <div class="megamenu-content__title"><?php esc_html_e('Réalisations', 'almetal'); ?></div>
                                        <!-- Le contenu sera affiché dynamiquement selon la catégorie survolée -->
                                    </div>
                                </div>
                            </li>
                        </ul>
                        
                        <!-- Logo centré -->
                        <div class="header-mega__logo-center">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home" aria-label="<?php bloginfo('name'); ?> - Accueil">
                                <?php
                                if (has_custom_logo()) {
                                    the_custom_logo();
                                } else {
                                    $logo_path = get_template_directory_uri() . '/assets/images/logo.png';
                                    ?>
                                    <img src="<?php echo esc_url($logo_path); ?>" 
                                         alt="<?php bloginfo('name'); ?> - Logo" 
                                         class="site-logo-img"
                                         width="120"
                                         height="40">
                                    <?php
                                }
                                ?>
                            </a>
                        </div>
                        
                        <!-- Liens droite -->
                        <ul class="header-mega__list header-mega__list--right">
                            <!-- Formations avec mega menu -->
                            <li class="header-mega__item has-megamenu">
                                <a href="<?php echo esc_url(home_url('/formations/')); ?>">
                                    <span><?php esc_html_e('Formations', 'almetal'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="5" viewBox="0 0 9 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.612712 0.200408C0.916245 -0.081444 1.39079 -0.0638681 1.67265 0.239665L4.37305 3.14779L7.07345 0.239665C7.35531 -0.0638681 7.82986 -0.081444 8.13339 0.200408C8.43692 0.48226 8.4545 0.956809 8.17265 1.26034L4.92265 4.76034C4.78074 4.91317 4.5816 5 4.37305 5C4.1645 5 3.96536 4.91317 3.82345 4.76034L0.573455 1.26034C0.291603 0.956809 0.309179 0.48226 0.612712 0.200408Z" fill="currentColor" />
                                    </svg>
                                </a>
                                <div class="megamenu-wrapper megamenu-wrapper--formations">
                                    <div class="megamenu-formations">
                                        <a href="<?php echo esc_url(home_url('/formations-particuliers/')); ?>" class="megamenu-formation-card">
                                            <div class="megamenu-formation-card__image">
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero/hero-1.png'); ?>" alt="Formations Particuliers">
                                            </div>
                                            <div class="megamenu-formation-card__title"><?php esc_html_e('Formations Particuliers', 'almetal'); ?></div>
                                            <div class="megamenu-formation-card__text"><?php esc_html_e('Apprenez les bases de la métallerie pour vos projets personnels', 'almetal'); ?></div>
                                        </a>
                                        <a href="<?php echo esc_url(home_url('/formations-professionnels/')); ?>" class="megamenu-formation-card">
                                            <div class="megamenu-formation-card__image">
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero/hero-2.png'); ?>" alt="Formations Professionnels">
                                            </div>
                                            <div class="megamenu-formation-card__title"><?php esc_html_e('Formations Professionnels', 'almetal'); ?></div>
                                            <div class="megamenu-formation-card__text"><?php esc_html_e('Perfectionnez vos compétences en métallerie professionnelle', 'almetal'); ?></div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            
                            <!-- Contact -->
                            <li class="header-mega__item">
                                <a href="<?php echo esc_url(home_url('/?page_id=10')); ?>"><?php esc_html_e('Contact', 'almetal'); ?></a>
                            </li>
                        </ul>
                    </nav>
                </div>
                
            <?php else : ?>
                <!-- VERSION MOBILE : Nouveau Header Mobile -->
                <?php get_template_part('template-parts/header', 'mobile'); ?>
            <?php endif; ?>
        </div>
    </header>

    <main id="primary" class="site-main">
