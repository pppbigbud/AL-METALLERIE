<?php
/**
 * Dashboard View
 */
global $wpdb;

// Obtenir les statistiques
$scheduler = new ACSP_Scheduler();
$stats = $scheduler->get_generation_stats('month');
$next_generation = $scheduler->get_next_generation_date();

// Obtenir les articles récents
$table_articles = $wpdb->prefix . 'acsp_articles';
$recent_articles = $wpdb->get_results("
    SELECT a.*, p.post_title, p.post_status 
    FROM $table_articles a 
    LEFT JOIN {$wpdb->posts} p ON a.post_id = p.ID 
    ORDER BY a.created_at DESC 
    LIMIT 5
");
?>

<div class="acsp-wrapper">
    <div class="acsp-header">
        <h1>Auto Content SEO Publisher</h1>
        <span class="version">v<?php echo ACSP_VERSION; ?></span>
    </div>
    
    <div class="acsp-dashboard">
        <!-- Statistiques -->
        <div class="acsp-stats-grid">
            <div class="acsp-stat-card">
                <div class="number"><?php echo $stats['total']; ?></div>
                <div class="label">Articles générés (30j)</div>
                <div class="change positive">
                    <?php if ($stats['total'] > 0): ?>
                        +<?php echo round($stats['published'] / $stats['total'] * 100); ?>% publiés
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="acsp-stat-card">
                <div class="number"><?php echo $stats['published']; ?></div>
                <div class="label">Articles publiés</div>
                <div class="change positive">
                    <?php echo $stats['success_rate']; ?>% de succès
                </div>
            </div>
            
            <div class="acsp-stat-card">
                <div class="number"><?php echo $stats['avg_seo_score']; ?></div>
                <div class="label">Score SEO moyen</div>
                <div class="change <?php echo $stats['avg_seo_score'] >= 70 ? 'positive' : 'negative'; ?>">
                    <?php echo $stats['avg_seo_score'] >= 70 ? 'Optimal' : 'À améliorer'; ?>
                </div>
            </div>
            
            <div class="acsp-stat-card">
                <div class="number">
                    <?php 
                    $table_topics = $wpdb->prefix . 'acsp_topics';
                    $topics_left = $wpdb->get_var("SELECT COUNT(*) FROM $table_topics WHERE used = FALSE");
                    echo $topics_left;
                    ?>
                </div>
                <div class="label">Sujets disponibles</div>
                <div class="change">
                    <?php 
                    $total_topics = $wpdb->get_var("SELECT COUNT(*) FROM $table_topics");
                    echo round(($topics_left / $total_topics) * 100); ?>% restants
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="acsp-quick-actions">
            <h2>Actions rapides</h2>
            <div class="acsp-action-buttons">
                <button id="acsp-force-generate" class="acsp-btn acsp-btn-primary">
                    <span class="dashicons dashicons-edit-page"></span>
                    Générer un article maintenant
                </button>
                
                <a href="?page=acsp-settings" class="acsp-btn acsp-btn-secondary">
                    <span class="dashicons dashicons-admin-settings"></span>
                    Configurer la génération
                </a>
                
                <a href="?page=acsp-history" class="acsp-btn acsp-btn-secondary">
                    <span class="dashicons dashicons-clock"></span>
                    Voir l'historique
                </a>
                
                <button id="acsp-test-generation" class="acsp-btn acsp-btn-success">
                    <span class="dashicons dashicons-visibility"></span>
                    Tester un article (brouillon)
                </button>
            </div>
        </div>
        
        <!-- Prochaine génération -->
        <?php if ($next_generation): ?>
        <div class="acsp-next-generation" style="background: #f0f6fc; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="margin-top: 0; color: #2271b1;">
                <span class="dashicons dashicons-clock"></span>
                Prochaine génération automatique
            </h3>
            <p style="margin-bottom: 0;">
                <strong>Date :</strong> <?php echo $next_generation['date']; ?><br>
                <strong>Dans :</strong> <?php echo $next_generation['relative']; ?>
            </p>
        </div>
        <?php endif; ?>
        
        <!-- Articles récents -->
        <div class="acsp-recent-articles">
            <h2>Articles générés récemment</h2>
            
            <?php if (empty($recent_articles)): ?>
                <p style="text-align: center; padding: 40px; color: #666;">
                    Aucun article généré pour le moment.<br>
                    <a href="?page=acsp-settings">Configurez le plugin</a> pour commencer.
                </p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Score SEO</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_articles as $article): ?>
                            <tr>
                                <td>
                                    <?php if ($article->post_id): ?>
                                        <a href="<?php echo get_edit_post_link($article->post_id); ?>" target="_blank">
                                            <?php echo esc_html($article->title); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo esc_html($article->title); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="acsp-badge" style="background: #e7f3e7; color: #2e7d32;">
                                        <?php 
                                        $types = [
                                            'guide' => 'Guide',
                                            'trend' => 'Tendance',
                                            'tutorial' => 'Tutoriel',
                                            'case_study' => 'Étude de cas',
                                            'faq' => 'FAQ',
                                            'comparison' => 'Comparatif',
                                            'inspiration' => 'Inspiration'
                                        ];
                                        echo $types[$article->type] ?? $article->type;
                                        ?>
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
                                            <?php 
                                            $status_map = [
                                                'scheduled' => 'Planifié',
                                                'published' => 'Publié',
                                                'failed' => 'Échoué'
                                            ];
                                            echo $status_map[$article->status] ?? ucfirst($article->status);
                                            ?>
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
                                </td>
                                <td>
                                    <?php 
                                    $date = new DateTime($article->created_at);
                                    echo $date->format('d/m/Y H:i'); 
                                    ?>
                                </td>
                                <td>
                                    <?php if ($article->post_id): ?>
                                        <a href="<?php echo get_permalink($article->post_id); ?>" 
                                           class="button button-small" target="_blank">
                                            Voir
                                        </a>
                                        <a href="<?php echo get_edit_post_link($article->post_id); ?>" 
                                           class="button button-small" target="_blank">
                                            Éditer
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p style="text-align: center; margin-top: 20px;">
                    <a href="?page=acsp-history" class="button">Voir tout l'historique</a>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Informations système -->
        <div class="acsp-system-info" style="margin-top: 40px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
            <h3 style="margin-top: 0;">Informations système</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Version WordPress</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo get_bloginfo('version'); ?></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Version PHP</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo PHP_VERSION; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Génération auto</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                        <?php echo get_option('acsp_enable_auto_generation', 0) ? '✅ Activée' : '❌ Désactivée'; ?>
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Fréquence</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                        <?php 
                        $frequency = get_option('acsp_frequency', 'weekly');
                        $freq_map = ['weekly' => 'Hebdomadaire', 'biweekly' => 'Bi-hebdomadaire', 'monthly' => 'Mensuelle'];
                        echo $freq_map[$frequency] ?? $frequency;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Prochain CRON</strong></td>
                    <td style="padding: 8px;">
                        <?php 
                        $timestamp = wp_next_scheduled('acsp_generate_weekly_article');
                        echo $timestamp ? date('d/m/Y H:i', $timestamp) : 'Non planifié';
                        ?>
                    </td>
                    <td style="padding: 8px;"><strong>Statut publication</strong></td>
                    <td style="padding: 8px;">
                        <?php echo get_option('acsp_post_status', 'draft') === 'publish' ? 'Directe' : 'Brouillon'; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Test de génération en brouillon
    $('#acsp-test-generation').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Générer un article de test en brouillon ?')) {
            return;
        }
        
        var $button = $(this);
        var originalText = $button.html();
        
        $button.prop('disabled', true).html('<span class="spinner is-active"></span> Génération...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'acsp_test_generation',
                nonce: '<?php echo wp_create_nonce('acsp_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('Article de test généré avec succès !\n\nTitre : ' + response.data.title);
                    location.reload();
                } else {
                    alert('Erreur : ' + response.data);
                }
            },
            error: function() {
                alert('Une erreur est survenue');
            },
            complete: function() {
                $button.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
