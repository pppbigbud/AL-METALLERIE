jQuery(document).ready(function($) {
    console.log('SEO Improver JS loaded');
    
    // Vérifier si les boutons existent
    console.log('Boutons améliorer trouvés:', $('.seo-improve-btn').length);
    $('.seo-improve-btn').css('background', 'red'); // Test visuel
    
    // Gestionnaire pour le bouton d'amélioration
    $('.seo-improve-btn').on('click', function(e) {
        console.log('Bouton améliorer cliqué !');
        e.preventDefault();
        var postId = $(this).data('post-id');
        var isTaxonomy = $(this).data('is-taxonomy') === 'true';
        console.log('Post ID:', postId, 'Is taxonomy:', isTaxonomy);
        openImprovementModal(postId, isTaxonomy);
    });
    
    // Alternative : délégation d'événement
    $(document).on('click', '.seo-improve-btn', function(e) {
        console.log('Bouton améliorer cliqué (délégation) !');
        e.preventDefault();
        var postId = $(this).data('post-id');
        var isTaxonomy = $(this).data('is-taxonomy') === 'true';
        console.log('Post ID:', postId, 'Is taxonomy:', isTaxonomy);
        openImprovementModal(postId, isTaxonomy);
    });
    
    // Ouvrir le modal d'amélioration
    function openImprovementModal(postId, isTaxonomy = false) {
        // Créer le modal s'il n'existe pas
        if ($('#seo-improvement-modal').length === 0) {
            $('body').append(`
                <div id="seo-improvement-modal" class="seo-improvement-modal" style="display:none;">
                    <div class="modal-backdrop"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Améliorations SEO</h3>
                            <button class="modal-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-tabs">
                                <button class="tab-button active" data-tab="standard">Améliorations standards</button>
                                <button class="tab-button" data-tab="ai">Génération IA</button>
                            </div>
                            <div class="tab-content active" id="standard-tab">
                                <div class="loading">
                                    <span class="spinner is-active"></span>
                                    Analyse des améliorations possibles...
                                </div>
                            </div>
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
                                    <div class="ai-parameters">
                                        <h5>Paramètres</h5>
                                        <div class="param-row">
                                            <label>Créativité (température):</label>
                                            <input type="range" id="ai-temperature" min="0" max="1" step="0.1" value="0.7">
                                            <span id="temp-value">0.7</span>
                                        </div>
                                        <div class="param-row">
                                            <label>Longueur du contenu:</label>
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
                                                <option value="technical">Technique</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="button button-primary" id="generate-ai-content">
                                        <span class="dashicons dashicons-megaphone"></span>
                                        Générer avec l'IA
                                    </button>
                                    <div id="ai-generated-content" style="display:none;">
                                        <h5>Contenu généré:</h5>
                                        <div id="ai-preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="button button-secondary modal-close">Annuler</button>
                            <button class="button button-primary" id="apply-improvements" disabled>Appliquer</button>
                        </div>
                    </div>
                </div>
            `);
        }
        
        // Stocker les données dans le modal
        $('#seo-improvement-modal').data('post-id', postId);
        $('#seo-improvement-modal').data('is-taxonomy', isTaxonomy);
        
        // Afficher le modal
        $('#seo-improvement-modal').fadeIn();
        
        // Charger les améliorations suggérées
        loadImprovementSuggestions(postId, isTaxonomy);
        
        // Gestion des onglets
        $('.tab-button').on('click', function() {
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            
            $(this).addClass('active');
            $('#' + $(this).data('tab') + '-tab').addClass('active');
        });
        
        // Gestion du slider de température
        $('#ai-temperature').on('input', function() {
            $('#temp-value').text($(this).val());
        });
    }
    
    // Charger les suggestions d'amélioration
    function loadImprovementSuggestions(postId, isTaxonomy) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_get_seo_improvements',
                post_id: postId,
                is_taxonomy: isTaxonomy,
                nonce: almetalAnalytics.nonce
            },
            success: function(response) {
                if (response.success) {
                    displayImprovementOptions(postId, response.data, isTaxonomy);
                } else {
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                showError('Erreur de communication avec le serveur');
            }
        });
    }
    
    // Afficher les options d'amélioration
    function displayImprovementOptions(postId, improvements, isTaxonomy) {
        console.log('displayImprovementOptions called', improvements);
        
        // Vérification défensive
        if (!improvements || !Array.isArray(improvements)) {
            improvements = [];
        }
        
        var html = '<div class="improvements-list">';
        html += '<h4>Sélectionnez et personnalisez les améliorations:</h4>';
        
        if (improvements.length === 0) {
            html += '<p>Aucune amélioration nécessaire pour cette page.</p>';
        } else {
            improvements.forEach(function(improvement) {
                html += `
                    <div class="improvement-item" data-type="${improvement.type}">
                        <label>
                            <input type="checkbox" value="${improvement.type}" checked>
                            <span class="improvement-desc">${improvement.description}</span>
                            <span class="improvement-priority priority-${improvement.priority}">${improvement.priority}</span>
                        </label>
                        <div class="improvement-edit" style="margin-top: 10px;">
                            ${generateEditableField(improvement)}
                        </div>
                    </div>
                `;
            });
        }
        
        html += '</div>';
        
        // Options de création
        html += `
            <div class="improvement-options">
                <h4>Options d'application:</h4>
                <label>
                    <input type="radio" name="apply_mode" value="draft" checked>
                    Créer un brouillon (recommandé)
                </label>
                <label>
                    <input type="radio" name="apply_mode" value="direct">
                    Appliquer directement aux modifications
                </label>
            </div>
        `;
        
        // Vérifier si les onglets existent
        console.log('Tabs exist?', $('.modal-tabs').length);
        console.log('Standard tab exists?', $('#standard-tab').length);
        console.log('AI tab exists?', $('#ai-tab').length);
        
        // Mettre à jour seulement l'onglet standard, en préservant les onglets
        var $standardTab = $('#standard-tab');
        if ($standardTab.length) {
            console.log('Updating standard tab only');
            $standardTab.html(html);
        } else {
            // Si l'onglet n'existe pas, on le crée
            console.log('Creating tabs structure');
            $('.modal-body').html(`
                <div class="modal-tabs">
                    <button class="tab-button active" data-tab="standard">Améliorations standards</button>
                    <button class="tab-button" data-tab="ai">Génération IA</button>
                </div>
                <div class="tab-content active" id="standard-tab">${html}</div>
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
                        <div class="ai-parameters">
                            <h5>Paramètres</h5>
                            <div class="param-row">
                                <label>Créativité (température):</label>
                                <input type="range" id="ai-temperature" min="0" max="1" step="0.1" value="0.7">
                                <span id="temp-value">0.7</span>
                            </div>
                            <div class="param-row">
                                <label>Longueur du contenu:</label>
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
                                    <option value="technical">Technique</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="button button-primary" id="generate-ai-content">
                            <span class="dashicons dashicons-megaphone"></span>
                            Générer avec l'IA
                        </button>
                        <div id="ai-generated-content" style="display:none;">
                            <h5>Contenu généré:</h5>
                            <div id="ai-preview"></div>
                        </div>
                    </div>
                </div>
            `);
            
            // Réattacher les événements des onglets
            $('.tab-button').on('click', function() {
                $('.tab-button').removeClass('active');
                $('.tab-content').removeClass('active');
                
                $(this).addClass('active');
                $('#' + $(this).data('tab') + '-tab').addClass('active');
            });
            
            // Réattacher l'événement du slider
            $('#ai-temperature').on('input', function() {
                $('#temp-value').text($(this).val());
            });
        }
        
        $('#apply-improvements').prop('disabled', improvements.length === 0);
        
        // Initialiser les compteurs de caractères
        initCharacterCounters();
    }
    
    // Initialiser les compteurs de caractères
    function initCharacterCounters() {
        $('.improvement-textarea').each(function() {
            var $textarea = $(this);
            var $counter = $textarea.siblings('.char-count');
            var maxLength = parseInt($textarea.attr('maxlength'));
            
            updateCharCount($textarea, $counter, maxLength);
            
            $textarea.on('input', function() {
                updateCharCount($(this), $counter, maxLength);
            });
        });
        
        $('.improvement-input').each(function() {
            var $input = $(this);
            var $counter = $input.siblings('.char-count');
            var maxLength = parseInt($input.attr('maxlength'));
            
            updateCharCount($input, $counter, maxLength);
            
            $input.on('input', function() {
                updateCharCount($(this), $counter, maxLength);
            });
        });
    }
    
    // Mettre à jour le compteur de caractères
    function updateCharCount($field, $counter, maxLength) {
        var currentLength = $field.val().length;
        $counter.text(currentLength);
        
        if (currentLength > maxLength * 0.9) {
            $counter.css('color', '#d63638');
        } else {
            $counter.css('color', '#666');
        }
    }
    
    // Générer les champs éditables selon le type d'amélioration
    function generateEditableField(improvement) {
        switch(improvement.type) {
            case 'meta_description':
                return `
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Meta description:</label>
                    <textarea class="improvement-textarea" rows="3" maxlength="160" 
                              placeholder="Entrez votre meta description (160 caractères max)">${improvement.suggested || ''}</textarea>
                    <small style="color: #666;">Caractères: <span class="char-count">0</span>/160</small>
                `;
            case 'title_length':
                return `
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Titre SEO:</label>
                    <input type="text" class="improvement-input" maxlength="60" 
                           value="${improvement.suggested || ''}" 
                           placeholder="Entrez votre titre (60 caractères max)">
                    <small style="color: #666;">Caractères: <span class="char-count">0</span>/60</small>
                `;
            default:
                return '<p style="color: #666; font-style: italic;">Amélioration automatique - pas de modification manuelle nécessaire</p>';
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
            temperature: parseFloat($('#ai-temperature').val()),
            length: $('#ai-length').val(),
            tone: $('#ai-tone').val()
        };
        
        if (!options.generate_meta && !options.generate_content) {
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
                action: 'almetal_generate_ai_content',
                post_id: postId,
                is_taxonomy: isTaxonomy,
                options: options,
                nonce: almetalAnalytics.nonce
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
        var selectedImprovements = [];
        var customValues = {};
        var applyMode = $('input[name="apply_mode"]:checked').val();
        
        // Vérifier si on est dans l'onglet IA
        var isAiTab = $('#ai-tab').hasClass('active');
        
        if (isAiTab) {
            // Récupérer le contenu généré par l'IA
            if ($('.ai-meta-desc').length) {
                customValues['meta_description'] = $('.ai-meta-desc').val();
                selectedImprovements.push('meta_description');
            }
            if ($('.ai-content').length) {
                customValues['content_improvement'] = $('.ai-content').val();
                selectedImprovements.push('content_improvement');
            }
        } else {
            // Mode standard
            $('.improvement-item input:checked').each(function() {
                var type = $(this).val();
                selectedImprovements.push(type);
                
                // Récupérer la valeur personnalisée si elle existe
                var item = $(this).closest('.improvement-item');
                if (type === 'meta_description') {
                    customValues[type] = item.find('.improvement-textarea').val();
                } else if (type === 'title_length') {
                    customValues[type] = item.find('.improvement-input').val();
                }
            });
        }
        
        if (selectedImprovements.length === 0) {
            showError('Veuillez sélectionner au moins une amélioration ou générer du contenu avec l\'IA');
            return;
        }
        
        // Désactiver le bouton et afficher le chargement
        $(this).prop('disabled', true).html('<span class="spinner is-active"></span> Application...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_apply_seo_improvements',
                post_id: postId,
                is_taxonomy: isTaxonomy,
                improvements: selectedImprovements,
                custom_values: customValues,
                create_draft: applyMode === 'draft',
                nonce: almetalAnalytics.nonce
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.data.message);
                    if (response.data.edit_url) {
                        // Mode brouillon
                        $('.modal-body').append(`
                            <div class="improvement-result">
                                <h4>Brouillon créé!</h4>
                                <p>Vous pouvez maintenant vérifier les modifications avant de publier.</p>
                                <a href="${response.data.edit_url}" class="button button-primary" target="_blank">
                                    Voir le brouillon
                                </a>
                            </div>
                        `);
                    } else if (response.data.post_url) {
                        // Mode application directe
                        $('.modal-body').append(`
                            <div class="improvement-result">
                                <h4>Modifications appliquées!</h4>
                                <p>Les améliorations SEO ont été appliquées directement.</p>
                                <a href="${response.data.post_url}" class="button button-primary" target="_blank">
                                    Voir la page
                                </a>
                            </div>
                        `);
                    }
                    setTimeout(function() {
                        $('#seo-improvement-modal').fadeOut();
                        // Recharger la page pour voir les changements
                        location.reload();
                    }, 2000);
                } else {
                    showError('Erreur: ' + response.data);
                }
            },
            error: function() {
                showError('Erreur de communication avec le serveur');
            }
        });
    });
    
    // Fermer le modal
    $(document).on('click', '.modal-close, .modal-backdrop', function() {
        $('#seo-improvement-modal').fadeOut();
    });
    
    // Fonctions utilitaires
    function showError(message) {
        $('.modal-body').append(`
            <div class="notice notice-error">
                <p>${message}</p>
            </div>
        `);
    }
    
    function showSuccess(message) {
        $('.modal-body').append(`
            <div class="notice notice-success">
                <p>${message}</p>
            </div>
        `);
    }
});
