<?php
/**
 * Vue SEO - Audit et analyse SEO
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

// Charger le CSS de la modal SEO
wp_enqueue_style('almetal-seo-modal', plugins_url('../assets/css/seo-modal.css', __FILE__), array(), ALMETAL_ANALYTICS_VERSION);

// Récupérer les données SEO si demandé
$seo_summary = null;
$single_analysis = null;
$robots_check = null;
$sitemap_check = null;

if (isset($_GET['action']) && $_GET['action'] === 'analyze_all') {
    $seo_summary = Almetal_Analytics_SEO::get_seo_summary();
}

if (isset($_GET['action']) && $_GET['action'] === 'analyze_page' && isset($_GET['post_id'])) {
    $single_analysis = Almetal_Analytics_SEO::analyze_page(intval($_GET['post_id']));
}

// Analyse d'une page de taxonomie
if (isset($_GET['action']) && $_GET['action'] === 'analyze_term' && isset($_GET['term_id'])) {
    $term = get_term(intval($_GET['term_id']), 'type_realisation');
    if ($term && !is_wp_error($term)) {
        $single_analysis = Almetal_Analytics_SEO::analyze_taxonomy_term($term);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'check_technical') {
    $robots_check = Almetal_Analytics_SEO::check_robots_txt();
    $sitemap_check = Almetal_Analytics_SEO::check_sitemap();
}
?>

<div class="wrap almetal-analytics-wrap">
    <div class="almetal-analytics-header">
        <h1>
            <span class="dashicons dashicons-search"></span>
            <?php _e('Audit SEO', 'almetal-analytics'); ?>
        </h1>
    </div>
    
    <!-- Actions rapides -->
    <div class="almetal-seo-actions" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="<?php echo admin_url('admin.php?page=almetal-analytics-seo&action=analyze_all'); ?>" 
           class="button button-primary button-large">
            <span class="dashicons dashicons-chart-bar" style="margin-top: 4px;"></span>
            <?php _e('Analyser toutes les pages', 'almetal-analytics'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=almetal-analytics-seo&action=check_technical'); ?>" 
           class="button button-secondary button-large">
            <span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span>
            <?php _e('Vérifications techniques', 'almetal-analytics'); ?>
        </a>
    </div>
    
    <?php if ($seo_summary) : ?>
    <!-- Résumé global -->
    <div class="almetal-seo-summary">
        <div class="almetal-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Score moyen -->
            <div class="almetal-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div class="almetal-score-circle" style="width: 100px; height: 100px; margin: 0 auto 15px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; color: white;
                    background: <?php 
                        if ($seo_summary['average_score'] >= 80) echo '#4CAF50';
                        elseif ($seo_summary['average_score'] >= 60) echo '#FF9800';
                        else echo '#F44336';
                    ?>;">
                    <?php echo $seo_summary['average_score']; ?>
                </div>
                <h3 style="margin: 0; color: #333;"><?php _e('Score SEO Moyen', 'almetal-analytics'); ?></h3>
                <p style="color: #666; margin: 5px 0 0;"><?php echo $seo_summary['total_pages']; ?> pages analysées</p>
            </div>
            
            <!-- Répartition -->
            <div class="almetal-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 15px; color: #333;"><?php _e('Répartition', 'almetal-analytics'); ?></h3>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; background: #4CAF50; border-radius: 50%;"></span>
                            Excellent (90-100)
                        </span>
                        <strong><?php echo $seo_summary['excellent']; ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; background: #8BC34A; border-radius: 50%;"></span>
                            Bon (70-89)
                        </span>
                        <strong><?php echo $seo_summary['good']; ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; background: #FF9800; border-radius: 50%;"></span>
                            À améliorer (50-69)
                        </span>
                        <strong><?php echo $seo_summary['needs_work']; ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; background: #F44336; border-radius: 50%;"></span>
                            Critique (0-49)
                        </span>
                        <strong><?php echo $seo_summary['poor']; ?></strong>
                    </div>
                </div>
            </div>
            
            <!-- Problèmes fréquents -->
            <div class="almetal-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 15px; color: #333;"><?php _e('Problèmes fréquents', 'almetal-analytics'); ?></h3>
                <?php if (!empty($seo_summary['common_issues'])) : ?>
                <ul style="margin: 0; padding: 0; list-style: none;">
                    <?php foreach ($seo_summary['common_issues'] as $issue => $data) : ?>
                    <li style="padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: <?php echo $data['priority'] === 'high' ? '#F44336' : '#FF9800'; ?>;">
                            <?php echo esc_html(mb_substr($issue, 0, 40)) . (mb_strlen($issue) > 40 ? '...' : ''); ?>
                        </span>
                        <span class="almetal-badge" style="background: #f0f0f0; padding: 2px 8px; border-radius: 10px; font-size: 12px;">
                            <?php echo $data['count']; ?>x
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else : ?>
                <p style="color: #4CAF50; margin: 0;">✓ Aucun problème majeur détecté</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Liste des pages -->
        <div class="almetal-table-card" style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <div class="almetal-table-header" style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0;"><?php _e('Détail par page', 'almetal-analytics'); ?></h3>
                <span style="color: #666; font-size: 13px;">Triées par score (du plus bas au plus haut)</span>
            </div>
            <div class="almetal-table-body" style="overflow-x: auto;">
                <table class="wp-list-table widefat fixed striped" style="margin: 0;">
                    <thead>
                        <tr>
                            <th style="width: 60px;"><?php _e('Score', 'almetal-analytics'); ?></th>
                            <th><?php _e('Page', 'almetal-analytics'); ?></th>
                            <th style="width: 100px;"><?php _e('Titre', 'almetal-analytics'); ?></th>
                            <th style="width: 100px;"><?php _e('Meta Desc.', 'almetal-analytics'); ?></th>
                            <th style="width: 80px;"><?php _e('H1', 'almetal-analytics'); ?></th>
                            <th style="width: 80px;"><?php _e('Images', 'almetal-analytics'); ?></th>
                            <th style="width: 150px;"><?php _e('Actions', 'almetal-analytics'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($seo_summary['pages'] as $page) : 
                            // Determiner si c'est une page de taxonomie
                            $is_taxonomy = isset($page['type']) && $page['type'] === 'taxonomy';
                            $type_badge = $is_taxonomy ? '<span style="background:#9C27B0;color:white;padding:2px 6px;border-radius:3px;font-size:10px;margin-left:5px;">Categorie</span>' : '';
                        ?>
                        <tr>
                            <td>
                                <span class="almetal-score-badge" style="display: inline-block; padding: 4px 10px; border-radius: 4px; font-weight: bold; color: white;
                                    background: <?php 
                                        if ($page['score'] >= 80) echo '#4CAF50';
                                        elseif ($page['score'] >= 60) echo '#FF9800';
                                        else echo '#F44336';
                                    ?>;">
                                    <?php echo $page['score']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($page['url']); ?>" target="_blank" style="text-decoration: none;">
                                    <?php echo esc_html($page['title']); ?>
                                </a>
                                <?php echo $type_badge; ?>
                                <br>
                                <small style="color: #666;"><?php echo esc_html($page['url']); ?></small>
                            </td>
                            <td>
                                <?php echo render_status_icon($page['checks']['title']['status']); ?>
                                <small><?php echo $page['checks']['title']['length']; ?> car.</small>
                            </td>
                            <td>
                                <?php echo render_status_icon($page['checks']['meta_description']['status']); ?>
                                <small><?php echo $page['checks']['meta_description']['length']; ?> car.</small>
                            </td>
                            <td>
                                <?php echo render_status_icon($page['checks']['h1']['status']); ?>
                                <small><?php echo $page['checks']['h1']['count']; ?> H1</small>
                            </td>
                            <td>
                                <?php 
                                $img_status = $page['checks']['images']['without_alt'] > 0 ? 'warning' : 'good';
                                echo render_status_icon($img_status); 
                                ?>
                                <small><?php echo $page['checks']['images']['with_alt']; ?>/<?php echo $page['checks']['images']['total']; ?></small>
                            </td>
                            <td>
                                <div class="action-buttons" style="display: flex; flex-direction: column; gap: 5px; align-items: stretch;">
                                    <?php if ($is_taxonomy) : ?>
                                    <a href="<?php echo admin_url('admin.php?page=almetal-analytics-seo&action=analyze_term&term_id=' . $page['term_id']); ?>" 
                                       class="button button-small" style="width: 100%; justify-content: center;">
                                        <?php _e('Details', 'almetal-analytics'); ?>
                                    </a>
                                    <?php if ($page['score'] < 95) : ?>
                                    <button class="button button-small button-primary seo-improve-btn" 
                                            data-post-id="<?php echo $page['term_id']; ?>"
                                            data-is-taxonomy="true"
                                            style="width: 100%; justify-content: center;">
                                        <span class="dashicons dashicons-update-alt" style="margin-right: 5px;"></span>
                                        <?php _e('Améliorer', 'almetal-analytics'); ?>
                                    </button>
                                    <?php endif; ?>
                                    <?php else : ?>
                                    <a href="<?php echo admin_url('admin.php?page=almetal-analytics-seo&action=analyze_page&post_id=' . $page['post_id']); ?>" 
                                       class="button button-small" style="width: 100%; justify-content: center;">
                                        <?php _e('Details', 'almetal-analytics'); ?>
                                    </a>
                                    <?php if ($page['score'] < 95) : ?>
                                    <button class="button button-small button-primary seo-improve-btn" 
                                            data-post-id="<?php echo $page['post_id']; ?>"
                                            data-is-taxonomy="false"
                                            style="width: 100%; justify-content: center;">
                                        <span class="dashicons dashicons-update-alt" style="margin-right: 5px;"></span>
                                        <?php _e('Améliorer', 'almetal-analytics'); ?>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($single_analysis) : ?>
    <!-- Analyse détaillée d'une page -->
    <div class="almetal-single-analysis">
        <a href="<?php echo admin_url('admin.php?page=almetal-analytics-seo&action=analyze_all'); ?>" class="button" style="margin-bottom: 20px;">
            ← <?php _e('Retour à la liste', 'almetal-analytics'); ?>
        </a>
        
        <div class="almetal-page-header" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
            <div class="almetal-score-circle" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: bold; color: white; flex-shrink: 0;
                background: <?php 
                    if ($single_analysis['score'] >= 80) echo '#4CAF50';
                    elseif ($single_analysis['score'] >= 60) echo '#FF9800';
                    else echo '#F44336';
                ?>;">
                <?php echo $single_analysis['score']; ?>
            </div>
            <div>
                <h2 style="margin: 0 0 5px;"><?php echo esc_html($single_analysis['title']); ?></h2>
                <a href="<?php echo esc_url($single_analysis['url']); ?>" target="_blank" style="color: #666;">
                    <?php echo esc_html($single_analysis['url']); ?>
                </a>
            </div>
        </div>
        
        <!-- Recommandations -->
        <?php if (!empty($single_analysis['recommendations'])) : ?>
        <div class="almetal-recommendations" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="margin: 0 0 15px;">
                <span class="dashicons dashicons-lightbulb" style="color: #FF9800;"></span>
                <?php _e('Recommandations', 'almetal-analytics'); ?>
            </h3>
            <ul style="margin: 0; padding: 0; list-style: none;">
                <?php foreach ($single_analysis['recommendations'] as $rec) : ?>
                <li style="padding: 12px; margin-bottom: 8px; border-radius: 4px; display: flex; align-items: flex-start; gap: 10px;
                    background: <?php echo $rec['priority'] === 'high' ? '#FFEBEE' : '#FFF8E1'; ?>;">
                    <span style="color: <?php echo $rec['priority'] === 'high' ? '#F44336' : '#FF9800'; ?>; font-weight: bold;">
                        <?php echo $rec['priority'] === 'high' ? '⚠️' : '💡'; ?>
                    </span>
                    <span><?php echo esc_html($rec['message']); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php else : ?>
        <div class="almetal-success" style="background: #E8F5E9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <span class="dashicons dashicons-yes-alt" style="color: #4CAF50;"></span>
            <?php _e('Excellent ! Aucun problème SEO majeur détecté sur cette page.', 'almetal-analytics'); ?>
        </div>
        <?php endif; ?>
        
        <!-- Détails des vérifications -->
        <div class="almetal-checks-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Titre -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['title']['status']); ?>
                    <?php _e('Titre', 'almetal-analytics'); ?>
                </h4>
                <p style="margin: 0 0 5px; word-break: break-word;"><strong><?php echo esc_html($single_analysis['checks']['title']['title']); ?></strong></p>
                <p style="margin: 0; color: #666; font-size: 13px;">Longueur: <?php echo $single_analysis['checks']['title']['length']; ?> caractères (idéal: 50-60)</p>
            </div>
            
            <!-- Meta Description -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['meta_description']['status']); ?>
                    <?php _e('Meta Description', 'almetal-analytics'); ?>
                </h4>
                <p style="margin: 0 0 5px; word-break: break-word; font-size: 13px;">
                    <?php echo esc_html($single_analysis['checks']['meta_description']['description'] ?: 'Non définie'); ?>
                </p>
                <p style="margin: 0; color: #666; font-size: 13px;">Longueur: <?php echo $single_analysis['checks']['meta_description']['length']; ?> caractères (idéal: 150-160)</p>
            </div>
            
            <!-- H1 -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['h1']['status']); ?>
                    <?php _e('Balise H1', 'almetal-analytics'); ?>
                </h4>
                <p style="margin: 0 0 5px;"><?php echo $single_analysis['checks']['h1']['count']; ?> balise(s) H1 trouvée(s)</p>
                <?php if (!empty($single_analysis['checks']['h1']['h1_tags'])) : ?>
                <ul style="margin: 5px 0 0; padding-left: 20px; font-size: 13px; color: #666;">
                    <?php foreach ($single_analysis['checks']['h1']['h1_tags'] as $h1) : ?>
                    <li><?php echo esc_html($h1); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            
            <!-- Images -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['images']['status']); ?>
                    <?php _e('Images', 'almetal-analytics'); ?>
                </h4>
                <p style="margin: 0 0 5px;">
                    <?php echo $single_analysis['checks']['images']['total']; ?> image(s) • 
                    <?php echo $single_analysis['checks']['images']['with_alt']; ?> avec alt • 
                    <?php echo $single_analysis['checks']['images']['without_alt']; ?> sans alt
                </p>
                <?php if (!empty($single_analysis['checks']['images']['missing_alt'])) : ?>
                <details style="margin-top: 10px;">
                    <summary style="cursor: pointer; color: #F44336; font-size: 13px;">Images sans alt</summary>
                    <ul style="margin: 5px 0 0; padding-left: 20px; font-size: 12px; color: #666;">
                        <?php foreach (array_slice($single_analysis['checks']['images']['missing_alt'], 0, 5) as $img) : ?>
                        <li><?php echo esc_html($img); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
                <?php endif; ?>
            </div>
            
            <!-- Liens -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['links']['status']); ?>
                    <?php _e('Liens', 'almetal-analytics'); ?>
                </h4>
                <p style="margin: 0;">
                    <span style="color: #2196F3;"><?php echo $single_analysis['checks']['links']['internal']; ?> internes</span> • 
                    <span style="color: #9C27B0;"><?php echo $single_analysis['checks']['links']['external']; ?> externes</span>
                </p>
            </div>
            
            <!-- Contenu -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['content']['status']); ?>
                    <?php _e('Contenu', 'almetal-analytics'); ?>
                </h4>
                <p style="margin: 0;">
                    <?php echo number_format_i18n($single_analysis['checks']['content']['word_count']); ?> mots • 
                    <?php echo $single_analysis['checks']['content']['paragraph_count']; ?> paragraphes
                </p>
                <p style="margin: 5px 0 0; color: #666; font-size: 13px;">Recommandé: 600+ mots</p>
            </div>
            
            <!-- Technique -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                    <?php echo self::render_status_icon($single_analysis['checks']['technical']['status']); ?>
                    <?php _e('Technique', 'almetal-analytics'); ?>
                </h4>
                <ul style="margin: 0; padding: 0; list-style: none; font-size: 13px;">
                    <li><?php echo $single_analysis['checks']['technical']['https'] ? '✅' : '❌'; ?> HTTPS</li>
                    <li><?php echo $single_analysis['checks']['technical']['viewport'] ? '✅' : '❌'; ?> Mobile-friendly</li>
                    <li><?php echo !empty($single_analysis['checks']['technical']['canonical']) ? '✅' : '❌'; ?> URL canonique</li>
                    <li><?php echo $single_analysis['checks']['technical']['structured_data'] ? '✅' : '❌'; ?> Données structurées</li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($robots_check || $sitemap_check) : ?>
    <!-- Vérifications techniques -->
    <div class="almetal-technical-checks">
        <div class="almetal-checks-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
            <!-- Robots.txt -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 15px; display: flex; align-items: center; gap: 8px;">
                    <span class="dashicons dashicons-media-text"></span>
                    <?php _e('Fichier robots.txt', 'almetal-analytics'); ?>
                    <?php echo $robots_check['exists'] ? '<span style="color: #4CAF50;">✓</span>' : '<span style="color: #F44336;">✗</span>'; ?>
                </h3>
                <?php if ($robots_check['exists']) : ?>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 12px; max-height: 200px;"><?php echo esc_html($robots_check['content']); ?></pre>
                <?php endif; ?>
                <?php if (!empty($robots_check['issues'])) : ?>
                <div style="margin-top: 10px; padding: 10px; background: #FFEBEE; border-radius: 4px;">
                    <?php foreach ($robots_check['issues'] as $issue) : ?>
                    <p style="margin: 0; color: #F44336;">⚠️ <?php echo esc_html($issue); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($robots_check['recommendations'])) : ?>
                <div style="margin-top: 10px; padding: 10px; background: #FFF8E1; border-radius: 4px;">
                    <?php foreach ($robots_check['recommendations'] as $rec) : ?>
                    <p style="margin: 0; color: #FF9800;">💡 <?php echo esc_html($rec); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sitemap -->
            <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 15px; display: flex; align-items: center; gap: 8px;">
                    <span class="dashicons dashicons-networking"></span>
                    <?php _e('Sitemap XML', 'almetal-analytics'); ?>
                    <?php echo $sitemap_check['exists'] ? '<span style="color: #4CAF50;">✓</span>' : '<span style="color: #F44336;">✗</span>'; ?>
                </h3>
                <?php if ($sitemap_check['exists']) : ?>
                <p style="margin: 0 0 10px;">
                    <strong>URL:</strong> 
                    <a href="<?php echo esc_url($sitemap_check['url']); ?>" target="_blank"><?php echo esc_html($sitemap_check['url']); ?></a>
                </p>
                <p style="margin: 0; color: #666;">
                    <?php echo $sitemap_check['pages_count']; ?> URL(s) dans le sitemap
                </p>
                <?php endif; ?>
                <?php if (!empty($sitemap_check['issues'])) : ?>
                <div style="margin-top: 10px; padding: 10px; background: #FFEBEE; border-radius: 4px;">
                    <?php foreach ($sitemap_check['issues'] as $issue) : ?>
                    <p style="margin: 0; color: #F44336;">⚠️ <?php echo esc_html($issue); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Google Search Console -->
        <div class="almetal-check-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
            <h3 style="margin: 0 0 15px; display: flex; align-items: center; gap: 8px;">
                <span class="dashicons dashicons-google"></span>
                <?php _e('Google Search Console', 'almetal-analytics'); ?>
            </h3>
            <?php 
            $gsc_api_key = get_option('almetal_analytics_gsc_api_key', '');
            if (empty($gsc_api_key)) : 
            ?>
            <div style="padding: 20px; background: #E3F2FD; border-radius: 8px; text-align: center;">
                <p style="margin: 0 0 15px; color: #1976D2;">
                    <span class="dashicons dashicons-info" style="font-size: 24px;"></span><br>
                    <?php _e('Connectez Google Search Console pour voir les données d\'indexation et de ranking.', 'almetal-analytics'); ?>
                </p>
                <a href="<?php echo admin_url('admin.php?page=almetal-analytics-settings'); ?>" class="button button-primary">
                    <?php _e('Configurer l\'API', 'almetal-analytics'); ?>
                </a>
            </div>
            <?php else : ?>
            <p style="color: #4CAF50;">✓ <?php _e('API Google Search Console configurée', 'almetal-analytics'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (!$seo_summary && !$single_analysis && !$robots_check) : ?>
    <!-- Page d'accueil du module SEO -->
    <div class="almetal-seo-welcome" style="text-align: center; padding: 60px 20px;">
        <div style="max-width: 600px; margin: 0 auto;">
            <span class="dashicons dashicons-search" style="font-size: 64px; color: #F08B18; margin-bottom: 20px;"></span>
            <h2 style="margin: 0 0 15px;"><?php _e('Audit SEO de votre site', 'almetal-analytics'); ?></h2>
            <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                <?php _e('Analysez toutes vos pages pour identifier les problèmes SEO et améliorer votre référencement sur Google.', 'almetal-analytics'); ?>
            </p>
            <a href="<?php echo admin_url('admin.php?page=almetal-analytics-seo&action=analyze_all'); ?>" 
               class="button button-primary button-hero">
                <?php _e('Lancer l\'analyse SEO', 'almetal-analytics'); ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
/**
 * Helper pour afficher les icônes de statut
 */
function render_status_icon($status) {
    switch ($status) {
        case 'good':
            return '<span style="color: #4CAF50;">✓</span>';
        case 'warning':
            return '<span style="color: #FF9800;">⚠</span>';
        case 'error':
            return '<span style="color: #F44336;">✗</span>';
        default:
            return '<span style="color: #9E9E9E;">?</span>';
    }
}
?>

<!-- Test pour vérifier que le JavaScript se charge -->
<script>
console.log('SEO page loaded - test');
if (typeof jQuery !== 'undefined') {
    console.log('jQuery is available');
    jQuery('.seo-improve-btn').css('background', 'red');
} else {
    console.error('jQuery not loaded');
}
</script>
