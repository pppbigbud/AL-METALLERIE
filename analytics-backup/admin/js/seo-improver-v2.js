jQuery(document).ready(function($) {
    console.log('SEO Improver V2 initialisé');
    
    // Ouvrir la modal d'amélioration
    $(document).on('click', '.seo-improve-btn', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        var isTaxonomy = $(this).data('is-taxonomy');
        
        console.log('Bouton améliorer cliqué pour:', postId, 'taxonomy:', isTaxonomy);
        
        // Charger les améliorations avec les commentaires
        loadImprovementsWithComments(postId, isTaxonomy);
    });
    
    // Charger les améliorations et commentaires
    function loadImprovementsWithComments(postId, isTaxonomy) {
        $('#seo-improvement-modal').remove();
        
        var modal = `
            <div id="seo-improvement-modal" class="seo-modal" style="display: none;">
                <div class="seo-modal-backdrop"></div>
                <div class="seo-modal-content">
                    <div class="seo-modal-header">
                        <h3>Améliorations SEO avec IA</h3>
                        <button class="seo-modal-close">&times;</button>
                    </div>
                    <div class="seo-modal-body">
                        <div class="loading">
                            <span class="spinner is-active"></span>
                            Analyse en cours...
                        </div>
                    </div>
                    <div class="seo-modal-footer">
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
                if (response.success) {
                    displayImprovementsWithComments(response.data);
                } else {
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
        var $body = $('.seo-modal-body');
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
                var hasAiSuggestion = issue.ai_suggestion ? true : false;
                var issueId = 'issue-' + index;
                
                html += `
                    <div class="issue-item ${issue.severity}" data-issue-id="${issueId}">
                        <div class="issue-header">
                            <h4>
                                <span class="dashicons dashicons-${issue.severity === 'high' ? 'warning' : 'info'}"></span>
                                ${issue.title}
                            </h4>
                            <div class="issue-actions">
                                ${hasAiSuggestion ? '<button class="button button-small toggle-ai-suggestion" data-target="' + issueId + '">Voir suggestion IA</button>' : ''}
                                <button class="button button-small fix-manual" data-target="' + issueId + '">Corriger manuellement</button>
                            </div>
                        </div>
                        <div class="issue-description">
                            <p>${issue.description}</p>
                            ${issue.current_value ? '<p><strong>Valeur actuelle:</strong> ' + issue.current_value + '</p>' : ''}
                        </div>
                        
                        ${hasAiSuggestion ? `
                        <div class="ai-suggestion" id="${issueId}-suggestion" style="display: none;">
                            <div class="ai-suggestion-header">
                                <h5><span class="dashicons dashicons-megaphone"></span> Suggestion de l'IA</h5>
                                <button class="button button-small apply-ai-suggestion" data-issue="${issueId}">Appliquer cette suggestion</button>
                            </div>
                            <div class="ai-suggestion-content">
                                <div class="suggestion-text">${issue.ai_suggestion}</div>
                            </div>
                        </div>
                        ` : ''}
                        
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
                    
                    <div class="ai-parameters">
                        <h5>Paramètres</h5>
                        <div class="param-row">
                            <label>Créativité (température):</label>
                            <input type="range" id="ai-temperature" min="0" max="1" step="0.1" value="0.7">
                            <span id="temp-value">0.7</span>
                        </div>
                        <div class="param-row">
                            <label>Longueur:</label>
                            <select id="ai-length">
                                <option value="short">Court (100 mots)</option>
                                <option value="medium" selected>Moyen (200 mots)</option>
                                <option value="long">Long (300 mots)</option>
                            </select>
                        </div>
                        <div class="param-row">
                            <label>Ton:</label>
                            <select id="ai-tone">
                                <option value="professional" selected>Professionnel</option>
                                <option value="friendly">Amical</option>
                                <option value="persuasive">Persuasif</option>
                            </select>
                        </div>
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
        
        $body.html(html);
        
        // Gérer les onglets
        $('.tab-button').on('click', function() {
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            
            $(this).addClass('active');
            $('#' + $(this).data('tab') + '-tab').addClass('active');
        });
        
        // Gérer les suggestions IA
        $('.toggle-ai-suggestion').on('click', function() {
            var target = $(this).data('target');
            $('#' + target + '-suggestion').slideToggle();
        });
        
        // Appliquer une suggestion IA
        $('.apply-ai-suggestion').on('click', function() {
            var issueId = $(this).data('issue');
            var suggestion = $('#' + issueId + '-suggestion .suggestion-text').text();
            
            // Appliquer la suggestion au champ manuel
            var $manualField = $('#' + issueId + '-manual input, #' + issueId + '-manual textarea');
            if ($manualField.length) {
                $manualField.val(suggestion);
                $('#' + issueId + '-manual').show();
                $('#' + issueId + '-suggestion').hide();
                
                // Marquer comme corrigé
                $('#' + issueId).addClass('fixed');
                checkAllFixed();
            }
        });
        
        // Gérer les corrections manuelles
        $('.fix-manual').on('click', function() {
            var target = $(this).data('target');
            $('#' + target + '-manual').slideToggle();
        });
        
        // Gérer la température
        $('#ai-temperature').on('input', function() {
            $('#temp-value').text($(this).val());
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
            case 'title_length':
                return `
                    <input type="text" class="manual-fix-input" maxlength="60" 
                           value="${issue.current_value || ''}" 
                           placeholder="Entrez votre titre (60 caractères max)">
                    <small style="color: #666;">Caractères: <span class="char-count">0</span>/60</small>
                `;
            case 'content_length':
                return `
                    <textarea class="manual-fix-input" rows="10" 
                              placeholder="Ajoutez du contenu pour atteindre la longueur recommandée (minimum 300 mots)">${issue.current_value || ''}</textarea>
                    <small style="color: #666;">Mots: <span class="word-count">0</span> (minimum 300 recommandés)</small>
                `;
            case 'h1_missing':
                return `
                    <input type="text" class="manual-fix-input" 
                           placeholder="Entrez le titre H1" value="${issue.suggested || ''}">
                `;
            case 'h2_missing':
                return `
                    <input type="text" class="manual-fix-input" 
                           placeholder="Entrez un sous-titre H2">
                `;
            default:
                return `
                    <textarea class="manual-fix-input" rows="3" 
                              placeholder="Corrigez ce problème">${issue.current_value || ''}</textarea>
                `;
        }
    }
    
    // Vérifier si tous les problèmes sont corrigés
    function checkAllFixed() {
        var totalIssues = $('.issue-item').length;
        var fixedIssues = $('.issue-item.fixed').length;
        
        if (fixedIssues > 0) {
            $('#apply-improvements').prop('disabled', false).text('Appliquer (' + fixedIssues + '/' + totalIssues + ')');
        }
    }
    
    // Génération de contenu IA
    $(document).on('click', '#generate-ai-content', function() {
        var $btn = $(this);
        var $preview = $('#ai-generated-content');
        var postId = $('#seo-improvement-modal').data('post-id');
        var isTaxonomy = $('#seo-improvement-modal').data('is-taxonomy');
        
        // Récupérer les options
        var options = {
            generate_meta: $('#ai-generate-meta').is(':checked'),
            generate_content: $('#ai-generate-content').is(':checked'),
            generate_missing: $('#ai-generate-missing').is(':checked'),
            temperature: parseFloat($('#ai-temperature').val()),
            length: $('#ai-length').val(),
            tone: $('#ai-tone').val()
        };
        
        if (!options.generate_meta && !options.generate_content && !options.generate_missing) {
            showError('Veuillez sélectionner au moins une option de génération');
            return;
        }
        
        // État loading
        $btn.prop('disabled', true).html('<span class="spinner is-active"></span> Génération en cours...');
        
        // Appel AJAX
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_generate_ai_content_v2',
                post_id: postId,
                is_taxonomy: isTaxonomy,
                options: options,
                nonce: almetalAnalytics.nonce || almetal_analytics_nonce
            },
            success: function(response) {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-megaphone"></span> Générer avec l\'IA');
                
                if (response.success) {
                    // Afficher le contenu généré
                    var html = '<div class="ai-result">';
                    
                    if (response.data.meta_description) {
                        html += '<div class="ai-section">';
                        html += '<h6>Meta Description:</h6>';
                        html += '<textarea class="ai-meta-desc" rows="3">' + response.data.meta_description + '</textarea>';
                        html += '</div>';
                    }
                    
                    if (response.data.content) {
                        html += '<div class="ai-section">';
                        html += '<h6>Contenu amélioré:</h6>';
                        html += '<textarea class="ai-content" rows="10">' + response.data.content + '</textarea>';
                        html += '</div>';
                    }
                    
                    if (response.data.missing_content) {
                        html += '<div class="ai-section">';
                        html += '<h6>Contenu manquant généré:</h6>';
                        html += '<textarea class="ai-missing-content" rows="10">' + response.data.missing_content + '</textarea>';
                        html += '</div>';
                    }
                    
                    html += '</div>';
                    
                    // Ajouter l'info sur le générateur utilisé
                    if (response.data.generator) {
                        html += '<div class="ai-generator-info" style="margin-top: 15px; padding: 10px; background: #e7f3ff; border-radius: 4px; font-size: 13px; border-left: 4px solid #0073aa;">';
                        html += '<strong>⚡ Générateur utilisé :</strong> ' + response.data.generator;
                        html += '</div>';
                    }
                    
                    $('#ai-preview').html(html);
                    $preview.show();
                    
                    // Activer le bouton appliquer
                    $('#apply-improvements').prop('disabled', false);
                } else {
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-megaphone"></span> Générer avec l\'IA');
                showError('Erreur de communication avec le serveur');
            }
        });
    });
    
    // Appliquer les améliorations
    $(document).on('click', '#apply-improvements', function() {
        var modal = $('#seo-improvement-modal');
        var postId = modal.data('post-id');
        var isTaxonomy = modal.data('is-taxonomy');
        var improvements = [];
        
        // Collecter les corrections manuelles
        $('.issue-item.fixed').each(function() {
            var $issue = $(this);
            var $input = $issue.find('.manual-fix-input');
            
            if ($input.length && $input.val()) {
                improvements.push({
                    type: $issue.data('type') || 'manual',
                    value: $input.val()
                });
            }
        });
        
        // Collecter le contenu IA généré
        if ($('#ai-preview').is(':visible')) {
            var aiMeta = $('.ai-meta-desc').val();
            var aiContent = $('.ai-content').val();
            var aiMissing = $('.ai-missing-content').val();
            
            if (aiMeta) improvements.push({ type: 'meta_description', value: aiMeta });
            if (aiContent) improvements.push({ type: 'content', value: aiContent });
            if (aiMissing) improvements.push({ type: 'missing_content', value: aiMissing });
        }
        
        if (improvements.length === 0) {
            showError('Aucune amélioration à appliquer');
            return;
        }
        
        // Appliquer
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_apply_seo_improvements_v2',
                post_id: postId,
                is_taxonomy: isTaxonomy,
                improvements: improvements,
                nonce: almetalAnalytics.nonce || almetal_analytics_nonce
            },
            success: function(response) {
                if (response.success) {
                    showSuccess('Améliorations appliquées avec succès !');
                    modal.fadeOut();
                    // Recharger la page après un court délai
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                showError('Erreur lors de l\'application des améliorations');
            }
        });
    });
    
    // Fermer la modal
    $(document).on('click', '.seo-modal-close, .seo-modal-backdrop', function() {
        $('#seo-improvement-modal').fadeOut();
    });
    
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
