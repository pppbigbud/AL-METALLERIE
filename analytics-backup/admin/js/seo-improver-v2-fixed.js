jQuery(document).ready(function($) {
    console.log('SEO Improver V2 initialisé');
    
    // Ouvrir la modal d'amélioration
    $(document).on('click', '.seo-improve-btn', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        var isTaxonomy = $(this).data('is-taxonomy');
        
        // Stocker globalement
        window.currentPostId = postId;
        window.currentIsTaxonomy = isTaxonomy;
        window.seoImprovements = {};
        
        console.log('Bouton améliorer cliqué pour:', postId, 'taxonomy:', isTaxonomy);
        
        // Charger les améliorations avec les commentaires
        loadImprovementsWithComments(postId, isTaxonomy);
    });
    
    // Charger les améliorations et commentaires
    function loadImprovementsWithComments(postId, isTaxonomy) {
        $('#seo-improvement-modal').remove();
        
        var modal = `
            <div id="seo-improvement-modal" class="seo-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 100000; background: rgba(0,0,0,0.5);">
                <div class="seo-modal-backdrop" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7);"></div>
                <div class="seo-modal-content" style="position: relative; background: #fff; margin: 50px auto; width: 90%; max-width: 800px; max-height: 80vh; overflow-y: auto; border-radius: 8px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
                    <div class="seo-modal-header" style="padding: 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 1.5em;">Améliorations SEO avec IA</h3>
                        <button class="seo-modal-close" style="background: none; border: none; font-size: 1.5em; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">&times;</button>
                    </div>
                    <div class="seo-modal-body" style="padding: 20px;">
                        <div class="loading" style="text-align: center; padding: 40px;">
                            <span class="spinner is-active" style="display: inline-block; width: 20px; height: 20px; border: 2px solid #f3f3f3; border-top: 2px solid #0073aa; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 10px;"></span>
                            Analyse en cours...
                        </div>
                    </div>
                    <div class="seo-modal-footer" style="padding: 20px; border-top: 1px solid #ddd; display: flex; justify-content: flex-end; gap: 10px;">
                        <button class="button button-secondary seo-modal-close">Annuler</button>
                        <button id="apply-improvements" class="button button-primary" disabled>Appliquer les améliorations</button>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modal);
        $('#seo-improvement-modal').fadeIn();
        
        // Charger les données
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_get_seo_improvements_with_comments',
                post_id: postId,
                is_taxonomy: isTaxonomy,
                nonce: almetalAnalytics.nonce || almetal_analytics_nonce
            },
            success: function(response) {
                console.log('Réponse AJAX reçue:', response);
                if (response.success) {
                    console.log('Données reçues:', response.data);
                    displayImprovementsWithComments(response.data);
                } else {
                    console.log('Erreur dans la réponse:', response.data);
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                showError('Erreur de communication avec le serveur');
            }
        });
    }
    
    // Fonctions globales
    window.displayImprovementsWithComments = function(data) {
        console.log('displayImprovementsWithComments appelé avec:', data);
        
        var $body = $('.seo-modal-body');
        console.log('Modal body trouvé:', $body.length);
        
        var html = '';
        
        // Onglets
        html += `
            <div class="modal-tabs">
                <button class="tab-button active" data-tab="issues">Problèmes détectés</button>
                <button class="tab-button" data-tab="ai">Génération IA</button>
            </div>
        `;
        
        // Contenu des onglets
        html += '<div class="tab-content active" id="issues-tab">';
        
        if (data.issues && data.issues.length > 0) {
            html += '<div class="issues-list">';
            
            data.issues.forEach(function(issue, index) {
                var issueId = 'issue-' + index;
                
                html += `
                    <div class="issue-item" data-issue-id="${issueId}">
                        <div class="issue-header">
                            <h4>
                                <span class="dashicons dashicons-${issue.type === 'meta_title' || issue.type === 'meta_description' ? 'warning' : 'info'}"></span>
                                ${issue.message}
                            </h4>
                            <div class="issue-actions">
                                <button class="button button-small view-suggestion" data-target="${issueId}">Voir suggestion</button>
                                <button class="button button-small fix-manual" data-target="${issueId}">Corriger manuellement</button>
                            </div>
                        </div>
                        <div class="issue-description">
                            <p>${issue.message}</p>
                            ${issue.current_value ? '<p><strong>Valeur actuelle:</strong> ' + issue.current_value + '</p>' : ''}
                        </div>
                        
                        <div class="ai-suggestion" id="${issueId}-suggestion" style="display: none;">
                            <div class="ai-suggestion-header">
                                <h5><span class="dashicons dashicons-megaphone"></span> Suggestion</h5>
                                <button class="button button-small apply-suggestion" data-issue="${issueId}" data-type="${issue.type}">Appliquer</button>
                            </div>
                            <div class="ai-suggestion-content">
                                <div class="suggestion-text">${issue.suggested_value}</div>
                            </div>
                        </div>
                        
                        <div class="manual-fix" id="${issueId}-manual" style="display: none;">
                            <div class="manual-fix-header">
                                <h5>Correction manuelle</h5>
                            </div>
                            <div class="manual-fix-content">
                                ${generateManualFixField(issue)}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
        } else {
            html += '<p style="text-align: center; color: #46b450;">✅ Aucun problème détecté !</p>';
        }
        
        html += '</div>'; // Fin issues-tab
        
        // Onglet IA
        html += `
            <div class="tab-content" id="ai-tab">
                <div class="ai-options">
                    <h4>Options de génération IA</h4>
                    <p>Générez du contenu unique avec notre système IA intégré.</p>
                    
                    <div class="ai-option">
                        <label>
                            <input type="checkbox" id="ai-generate-meta" checked>
                            Générer une meta description unique
                        </label>
                    </div>
                    
                    <div class="ai-option">
                        <label>
                            <input type="checkbox" id="ai-generate-content" checked>
                            Améliorer le contenu de la page
                        </label>
                    </div>
                    
                    <div class="ai-option">
                        <label>
                            <input type="checkbox" id="ai-generate-missing" checked>
                            Générer le contenu manquant
                        </label>
                    </div>
                    
                    <button id="generate-ai-content" class="button button-primary">
                        <span class="dashicons dashicons-megaphone"></span> Générer avec l'IA
                    </button>
                </div>
                
                <div id="ai-preview" style="display: none; margin-top: 20px;">
                    <h4>Aperçu du contenu généré</h4>
                    <div id="ai-generated-content"></div>
                </div>
            </div>
        `;
        
        console.log('HTML généré, mise à jour du modal...');
        $body.html(html);
        console.log('Modal mis à jour');
        
        // Gérer les onglets
        $('.tab-button').on('click', function() {
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            
            $(this).addClass('active');
            $('#' + $(this).data('tab') + '-tab').addClass('active');
        });
        
        // Gérer les suggestions
        $('.view-suggestion').on('click', function() {
            var target = $(this).data('target');
            $('#' + target + '-suggestion').slideToggle();
        });
        
        // Appliquer une suggestion
        $('.apply-suggestion').on('click', function() {
            var issueId = $(this).data('issue');
            var type = $(this).data('type');
            var suggestion = $('#' + issueId + '-suggestion .suggestion-text').text();
            
            // Stocker pour l'application
            if (!window.seoImprovements) {
                window.seoImprovements = {};
            }
            window.seoImprovements[type] = suggestion;
            
            // Marquer comme appliqué
            $('#' + issueId).addClass('fixed');
            $(this).text('Appliqué').prop('disabled', true);
            
            // Activer le bouton appliquer
            $('#apply-improvements').prop('disabled', false);
        });
        
        // Gérer les corrections manuelles
        $('.fix-manual').on('click', function() {
            var target = $(this).data('target');
            $('#' + target + '-manual').slideToggle();
        });
        
        // Gérer la génération IA
        $('#generate-ai-content').on('click', function() {
            generateAIContent();
        });
        
        // Appliquer les améliorations
        $('#apply-improvements').on('click', function() {
            applyImprovements();
        });
        
        // Fermer la modal
        $('.seo-modal-close').on('click', function() {
            $('#seo-improvement-modal').fadeOut();
        });
    }
    
    // Générer le champ de correction manuel
    function generateManualFixField(issue) {
        switch(issue.type) {
            case 'meta_description':
                return `
                    <textarea class="manual-fix-input" rows="3" maxlength="160" 
                              placeholder="Entrez votre meta description (160 caractères max)">${issue.current_value || ''}</textarea>
                    <small style="color: #666;">Caractères: <span class="char-count">0</span>/160</small>
                `;
            case 'meta_title':
                return `
                    <input type="text" class="manual-fix-input" maxlength="60" 
                           value="${issue.current_value || ''}" 
                           placeholder="Entrez votre titre (60 caractères max)">
                    <small style="color: #666;">Caractères: <span class="char-count">0</span>/60</small>
                `;
            default:
                return `
                    <input type="text" class="manual-fix-input" 
                           value="${issue.current_value || ''}" 
                           placeholder="Corrigez cette valeur">
                `;
        }
    }
    
    // Générer du contenu IA
    function generateAIContent() {
        var $btn = $('#generate-ai-content');
        $btn.prop('disabled', true).html('<span class="spinner is-active"></span> Génération en cours...');
        
        var options = {
            generate_meta: $('#ai-generate-meta').is(':checked'),
            generate_content: $('#ai-generate-content').is(':checked'),
            generate_missing: $('#ai-generate-missing').is(':checked')
        };
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_generate_ai_content_v2',
                post_id: window.currentPostId || 0,
                is_taxonomy: window.currentIsTaxonomy || 'false',
                options: options,
                nonce: almetalAnalytics.nonce || almetal_analytics_nonce
            },
            success: function(response) {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-megaphone"></span> Générer avec l\'IA');
                
                if (response.success) {
                    var html = '<div class="ai-result">';
                    
                    if (response.data.meta_title) {
                        html += '<div class="ai-section"><h5>Meta Titre:</h5><p>' + response.data.meta_title + '</p></div>';
                    }
                    if (response.data.meta_description) {
                        html += '<div class="ai-section"><h5>Meta Description:</h5><p>' + response.data.meta_description + '</p></div>';
                    }
                    if (response.data.content) {
                        html += '<div class="ai-section"><h5>Contenu:</h5><p>' + response.data.content + '</p></div>';
                    }
                    
                    html += '</div>';
                    $('#ai-generated-content').html(html);
                    $('#ai-preview').show();
                } else {
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-megaphone"></span> Générer avec l\'IA');
                showError('Erreur de communication avec le serveur');
            }
        });
    }
    
    // Appliquer les améliorations
    function applyImprovements() {
        if (!window.seoImprovements) {
            showError('Aucune amélioration à appliquer');
            return;
        }
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_apply_seo_improvements_v2',
                post_id: window.currentPostId,
                is_taxonomy: window.currentIsTaxonomy,
                improvements: window.seoImprovements,
                nonce: almetalAnalytics.nonce || almetal_analytics_nonce
            },
            success: function(response) {
                if (response.success) {
                    showSuccess('Améliorations appliquées avec succès !');
                    $('#seo-improvement-modal').fadeOut();
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                showError('Erreur de communication avec le serveur');
            }
        });
    }
    
    // Fonctions utilitaires
    function showError(message) {
        if (window.almetalShowNotice) {
            almetalShowNotice(message, 'error');
        } else {
            alert(message);
        }
    }
    
    function showSuccess(message) {
        if (window.almetalShowNotice) {
            almetalShowNotice(message, 'success');
        } else {
            alert(message);
        }
    }
});
