<?php
/**
 * History View
 */
global $wpdb;

// Pagination
$page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Filtres
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Construire la requête
$table_articles = $wpdb->prefix . 'acsp_articles';
$where = ["1=1"];
$params = [];

if ($status_filter) {
    $where[] = "a.status = %s";
    $params[] = $status_filter;
}

if ($type_filter) {
    $where[] = "a.type = %s";
    $params[] = $type_filter;
}

if ($date_filter) {
    switch ($date_filter) {
        case 'week':
            $where[] = "a.created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            break;
        case 'month':
            $where[] = "a.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            break;
        case 'year':
            $where[] = "a.created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
    }
}

$where_clause = implode(' AND ', $where);

// Compter le total
$total_query = "SELECT COUNT(*) FROM $table_articles a WHERE $where_clause";
$total = $wpdb->get_var($wpdb->prepare($total_query, $params));

// Récupérer les articles
$query = "SELECT a.*, p.post_title, p.post_status, p.post_name 
          FROM $table_articles a 
          LEFT JOIN {$wpdb->posts} p ON a.post_id = p.ID 
          WHERE $where_clause 
          ORDER BY a.created_at DESC 
          LIMIT %d OFFSET %d";

$params[] = $per_page;
$params[] = $offset;

$articles = $wpdb->get_results($wpdb->prepare($query, $params));

// Types pour le filtre
$types = [
    'guide' => 'Guides',
    'trend' => 'Tendances',
    'tutorial' => 'Tutoriels',
    'case_study' => 'Études de cas',
    'faq' => 'FAQ',
    'comparison' => 'Comparatifs',
    'inspiration' ' => 'Inspirations'
];

// Statuts pour le filtre
$statuses = [
    'scheduled' => 'Planifié',
    'published' => 'Publié',
    'failed' => 'Échoué'
];
?>

<div class="acsp-wrapper">
    <div class="acsp-header">
        <h1>Auto Content SEO Publisher</h1>
        <span class="version">v<?php echo ACSP_VERSION; ?></span>
    </div>
    
    <div class="acsp-dashboard">
        <h2>Historique des générations</h2>
        
        <!-- Filtres -->
        <div class="acsp-filters" style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <form method="get" action="">
                <input type="hidden" name="page" value="acsp-history">
                
                <div class="acsp-form-row">
                    <div class="acsp-form-group">
                        <label for="status-filter">Statut</label>
                        <select name="status" id="status-filter">
                            <option value="">Tous</option>
                            <?php foreach ($statuses as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php selected($status_filter, $value); ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="acsp-form-group">
                        <label for="type-filter">Type</label>
                        <select name="type" id="type-filter">
                            <option value="">Tous</option>
                            <?php foreach ($types as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php selected($type_filter, $value); ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="acsp-form-group">
                        <label for="date-filter">Période</label>
                        <select name="date" id="date-filter">
                            <option value="">Toutes</option>
                            <option value="week" <?php selected($date_filter, 'week'); ?>>
                                Dernière semaine
                            </option>
                            <option value="month" <?php selected($date_filter, 'month'); ?>>
                                Dernier mois
                            </option>
                            <option value="year" <?php selected($date_filter, 'year'); ?>>
                                Dernière année
                            </option>
                        </select>
                    </div>
                    
                    <div class="acsp-form-group" style="justify-content: flex-end;">
                        <button type="submit" class="button">Filtrer</button>
                        <a href="?page=acsp-history" class="button">Réinitialiser</a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Tableau -->
        <?php if (empty($articles)): ?>
            <div class="acsp-no-results" style="text-align: center; padding: 40px; color: #666;">
                Aucun article trouvé pour les filtres sélectionnés.
            </div>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped" id="acsp-history-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Titre</th>
                        <th style="width: 10%;">Type</th>
                        <th style="width: 10%;">Statut</th>
                        <th style="width: 10%;">Score SEO</th>
                        <th style="width: 15%;">Date de création</th>
                        <th style="width: 15%;">Date de publication</th>
                        <th style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr data-status="<?php echo $article->status; ?>" data-type="<?php echo $article->type; ?>">
                            <td>
                                <strong>
                                    <?php if ($article->post_id): ?>
                                        <a href="<?php echo get_edit_post_link($article->post_id); ?>" target="_blank">
                                            <?php echo esc_html($article->title); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo esc_html($article->title); ?>
                                    <?php endif; ?>
                                </strong>
                                <?php if ($article->post_name): ?>
                                    <br><small><code>/<?php echo $article->post_name; ?>/</code></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="acsp-badge" style="background: #e7f3e7; color: #2e7d32;">
                                    <?php echo $types[$article->type] ?? $article->type; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($article->post_id): ?>
                                    <span class="acsp-badge published">
                                        <?php 
                                        $status_map = [
                                            'draft' => 'Brouillon',
                                            'publish' => 'Publié',
                                            'private' => 'Privé'
                                        ];
                                        echo $status_map[$article->post_status] ?? ucfirst($article->post_status);
                                        ?>
                                    </span>
                                <?php else: ?>
                                    <span class="acsp-badge <?php echo $article->status; ?>">
                                        <?php echo $statuses[$article->status] ?? ucfirst($article->status); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="acsp-seo-score <?php 
                                    echo $article->seo_score >= 70 ? 'high' : 
                                         ($article->seo_score >= 50 ? 'medium' : 'low'); 
                                ?>">
                                    <?php echo $article->seo_score; ?>/100
                                </span>
                                <?php if ($article->seo_score < 70): ?>
                                    <br><small>À améliorer</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $date = new DateTime($article->created_at);
                                echo $date->format('d/m/Y H:i'); 
                                ?>
                            </td>
                            <td>
                                <?php 
                                if ($article->published_date) {
                                    $date = new DateTime($article->published_date);
                                    echo $date->format('d/m/Y H:i');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($article->post_id): ?>
                                    <a href="<?php echo get_permalink($article->post_id); ?>" 
                                       class="button button-small" target="_blank" title="Voir">
                                        <span class="dashicons dashicons-visibility"></span>
                                    </a>
                                    <a href="<?php echo get_edit_post_link($article->post_id); ?>" 
                                       class="button button-small" target="_blank" title="Éditer">
                                        <span class="dashicons dashicons-edit"></span>
                                    </a>
                                <?php else: ?>
                                    <span class="acsp-tooltip" data-tooltip="Non publié">
                                        <span class="dashicons dashicons-no"></span>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($total > $per_page): ?>
                <div class="tablenav bottom">
                    <div class="tablenav-pages">
                        <?php
                        $current_url = add_query_arg('paged', '%#%');
                        echo paginate_links([
                            'base' => str_replace('%#%', '%#%', $current_url),
                            'format' => '?paged=%#%',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => ceil($total / $per_page),
                            'current' => $page
                        ]);
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Statistiques -->
            <div class="acsp-history-stats" style="margin-top: 30px; padding: 20px; background: #f0f6fc; border-radius: 8px;">
                <h3>Statistiques de la période</h3>
                <div class="acsp-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                    <div>
                        <strong>Total :</strong> <?php echo $total; ?> articles
                    </div>
                    <div>
                        <strong>Publiés :</strong> 
                        <?php 
                        $published = count(array_filter($articles, function($a) { 
                            return $a->post_id && $a->post_status === 'publish'; 
                        }));
                        echo $published; 
                        ?>
                    </div>
                    <div>
                        <strong>Brouillons :</strong> 
                        <?php 
                        $drafts = count(array_filter($articles, function($a) { 
                            return $a->post_id && $a->post_status === 'draft'; 
                        }));
                        echo $drafts; 
                        ?>
                    </div>
                    <div>
                        <strong>Score SEO moyen :</strong> 
                        <?php 
                        $avg_score = array_sum(array_column($articles, 'seo_score')) / count($articles);
                        echo round($avg_score); ?>/100
                    </div>
                </div>
            </div>
            
            <!-- Export -->
            <div style="margin-top: 20px; text-align: center;">
                <button type="button" id="acsp-export-csv" class="button">
                    <span class="dashicons dashicons-download"></span>
                    Exporter en CSV
                </button>
                <button type="button" id="acsp-export-json" class="button">
                    <span class="dashicons dashicons-download"></span>
                    Exporter en JSON
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Export CSV
    $('#acsp-export-csv').on('click', function() {
        var params = new URLSearchParams(window.location.search);
        params.set('action', 'acsp_export_history');
        params.set('format', 'csv');
        params.set('nonce', '<?php echo wp_create_nonce('acsp_export_nonce'); ?>');
        
        window.open(ajaxurl + '?' + params.toString());
    });
    
    // Export JSON
    $('#acsp-export-json').on('click', function() {
        var params = new URLSearchParams(window.location.search);
        params.set('action', 'acsp_export_history');
        params.set('format', 'json');
        params.set('nonce', '<?php echo wp_create_nonce('acsp_export_nonce'); ?>');
        
        window.open(ajaxurl + '?' + params.toString());
    });
});
</script>
