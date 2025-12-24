jQuery(document).ready(function($) {
    // Gestionnaire pour le bouton d'amélioration
    $('.seo-improve-btn').on('click', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        var isTaxonomy = $(this).data('is-taxonomy') === 'true';
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
                            <h3>Améliorations SEO automatiques</h3>
                            <button class="modal-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="loading">
                                <span class="spinner is-active"></span>
                                Analyse des améliorations possibles...
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
        
        $('.modal-body').html(html);
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
    
    // Appliquer les améliorations
    $(document).on('click', '#apply-improvements', function() {
        var modal = $('#seo-improvement-modal');
        var postId = modal.data('post-id');
        var isTaxonomy = modal.data('is-taxonomy');
        var selectedImprovements = [];
        var customValues = {};
        var applyMode = $('input[name="apply_mode"]:checked').val();
        
        // Récupérer les améliorations sélectionnées et leurs valeurs personnalisées
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
        
        if (selectedImprovements.length === 0) {
            showError('Veuillez sélectionner au moins une amélioration');
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
                        $('.modal-body').append(`
                            <div class="improvement-result">
                                <h4>Brouillon créé!</h4>
                                <p>Vous pouvez maintenant vérifier les modifications avant de publier.</p>
                                <a href="${response.data.edit_url}" class="button button-primary" target="_blank">
                                    Voir le brouillon
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
