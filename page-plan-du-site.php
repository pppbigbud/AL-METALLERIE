<?php
/**
 * Template Name: Plan du site
 * Description: Page affichant le plan du site de manière visuelle
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// SEO Meta Tags
add_action('wp_head', 'almetal_plan_site_seo_meta', 1);
function almetal_plan_site_seo_meta() {
    ?>
    <title>Plan du site | AL Métallerie & Soudure | Thiers (63)</title>
    <meta name="description" content="Plan du site AL Métallerie & Soudure. Retrouvez toutes nos pages : réalisations, formations, matières, contact. Métallier serrurier à Thiers (63).">
    <link rel="canonical" href="<?php echo esc_url(home_url('/plan-du-site/')); ?>">
    <meta name="robots" content="noindex, follow">
    <?php
}

add_filter('pre_get_document_title', function($title) {
    return 'Plan du site | AL Métallerie & Soudure | Thiers (63)';
}, 999);

get_header();
?>

<style>
.plan-du-site {
    background: #191919;
    min-height: 100vh;
    padding: 120px 0 60px;
}
.plan-du-site .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}
.plan-du-site__header {
    text-align: center;
    margin-bottom: 50px;
}
.plan-du-site__title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 15px;
}
.plan-du-site__title svg {
    color: #F08B18;
    margin-right: 15px;
    vertical-align: middle;
}
.plan-du-site__subtitle {
    color: rgba(255,255,255,0.7);
    font-size: 1.1rem;
}
.plan-du-site__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}
.plan-du-site__section {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 30px;
}
.plan-du-site__section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.25rem;
    font-weight: 600;
    color: #F08B18;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(240,139,24,0.3);
}
.plan-du-site__section-title svg {
    width: 24px;
    height: 24px;
}
.plan-du-site__list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.plan-du-site__list li {
    margin-bottom: 12px;
}
.plan-du-site__list a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}
.plan-du-site__list a:hover {
    background: rgba(240,139,24,0.1);
    color: #F08B18;
}
.plan-du-site__list a svg {
    width: 16px;
    height: 16px;
    color: #F08B18;
    flex-shrink: 0;
}
.plan-du-site__count {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.5);
    margin-left: auto;
}
.plan-du-site__stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 50px;
    padding: 30px;
    background: rgba(240,139,24,0.1);
    border-radius: 16px;
    border: 1px solid rgba(240,139,24,0.2);
}
.plan-du-site__stat {
    text-align: center;
}
.plan-du-site__stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #F08B18;
}
.plan-du-site__stat-label {
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
}
@media (max-width: 768px) {
    .plan-du-site {
        padding: 100px 0 40px;
    }
    .plan-du-site__title {
        font-size: 1.75rem;
    }
    .plan-du-site__stats {
        flex-direction: column;
        gap: 20px;
    }
}
</style>

<div class="plan-du-site">
    <div class="container">
        <header class="plan-du-site__header">
            <h1 class="plan-du-site__title">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Plan du site
            </h1>
            <p class="plan-du-site__subtitle">Retrouvez toutes les pages de notre site</p>
        </header>

        <div class="plan-du-site__grid">
            <!-- Pages principales -->
            <section class="plan-du-site__section">
                <h2 class="plan-du-site__section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    </svg>
                    Pages principales
                </h2>
                <ul class="plan-du-site__list">
                    <li>
                        <a href="<?php echo home_url('/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo home_url('/realisations/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Nos réalisations
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo home_url('/formations/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Formations
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo home_url('/contact/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo home_url('/mentions-legales/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Mentions légales
                        </a>
                    </li>
                </ul>
            </section>

            <!-- Catégories de réalisations -->
            <section class="plan-du-site__section">
                <h2 class="plan-du-site__section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Catégories de réalisations
                </h2>
                <ul class="plan-du-site__list">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'type_realisation',
                        'hide_empty' => true,
                        'orderby' => 'count',
                        'order' => 'DESC',
                    ));
                    if ($categories && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                    ?>
                    <li>
                        <a href="<?php echo get_term_link($category); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            <?php echo esc_html($category->name); ?>
                            <span class="plan-du-site__count"><?php echo $category->count; ?></span>
                        </a>
                    </li>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </section>

            <!-- Matières -->
            <section class="plan-du-site__section">
                <h2 class="plan-du-site__section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    Nos matières
                </h2>
                <ul class="plan-du-site__list">
                    <?php
                    $matieres = get_posts(array(
                        'post_type' => 'matiere',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ));
                    if ($matieres) :
                        foreach ($matieres as $matiere) :
                    ?>
                    <li>
                        <a href="<?php echo get_permalink($matiere); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            <?php echo esc_html(get_the_title($matiere)); ?>
                        </a>
                    </li>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </section>

            <!-- Formations -->
            <section class="plan-du-site__section">
                <h2 class="plan-du-site__section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    Formations
                </h2>
                <ul class="plan-du-site__list">
                    <li>
                        <a href="<?php echo home_url('/formations/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Toutes les formations
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo home_url('/formations-particuliers/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Formations Particuliers
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo home_url('/formations-professionnels/'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            Formations Professionnels
                        </a>
                    </li>
                </ul>
            </section>

            <!-- Zones d'intervention (Pages villes) -->
            <section class="plan-du-site__section">
                <h2 class="plan-du-site__section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    Zones d'intervention
                </h2>
                <ul class="plan-du-site__list">
                    <?php
                    $city_pages = get_posts(array(
                        'post_type' => 'city_page',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ));
                    if ($city_pages) :
                        foreach ($city_pages as $city_page) :
                    ?>
                    <li>
                        <a href="<?php echo get_permalink($city_page); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            <?php echo esc_html(get_the_title($city_page)); ?>
                        </a>
                    </li>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </section>
        </div>

        <!-- Statistiques -->
        <?php
        $total_realisations = wp_count_posts('realisation')->publish;
        $total_matieres = wp_count_posts('matiere')->publish;
        $total_categories = count($categories);
        $total_villes = wp_count_posts('city_page')->publish;
        ?>
        <div class="plan-du-site__stats">
            <div class="plan-du-site__stat">
                <div class="plan-du-site__stat-number"><?php echo $total_realisations; ?></div>
                <div class="plan-du-site__stat-label">Réalisations</div>
            </div>
            <div class="plan-du-site__stat">
                <div class="plan-du-site__stat-number"><?php echo $total_categories; ?></div>
                <div class="plan-du-site__stat-label">Catégories</div>
            </div>
            <div class="plan-du-site__stat">
                <div class="plan-du-site__stat-number"><?php echo $total_matieres; ?></div>
                <div class="plan-du-site__stat-label">Matières</div>
            </div>
            <div class="plan-du-site__stat">
                <div class="plan-du-site__stat-number"><?php echo $total_villes; ?></div>
                <div class="plan-du-site__stat-label">Villes</div>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
