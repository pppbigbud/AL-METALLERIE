jQuery(document).ready(function($) {
    // Gestionnaire pour le bouton d'amélioration
    $('.seo-improve-btn').on('click', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        openImprovementModal(postId);
    });
    
    // Ouvrir le modal d'amélioration
    function openImprovementModal(postId) {
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
        
        // Afficher le modal
        $('#seo-improvement-modal').fadeIn();
        
        // Charger les améliorations suggérées
        loadImprovementSuggestions(postId);
    }
    
    // Charger les suggestions d'amélioration
    function loadImprovementSuggestions(postId) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'almetal_get_seo_improvements',
                post_id: postId,
                nonce: almetalAnalytics.nonce
            },
            success: function(response) {
                if (response.success) {
                    displayImprovementOptions(postId, response.data);
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
    function displayImprovementOptions(postId, improvements) {
        var html = '<div class="improvements-list">';
        html += '<h4>Sélectionnez les améliorations à appliquer:</h4>';
        
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
    }
    
    // Appliquer les améliorations
    $(document).on('click', '#apply-improvements', function() {
        var postId = $(this).data('post-id');
        var selectedImprovements = [];
        var applyMode = $('input[name="apply_mode"]:checked').val();
        
        $('.improvement-item input:checked').each(function() {
            selectedImprovements.push($(this).val());
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
                improvements: selectedImprovements,
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
