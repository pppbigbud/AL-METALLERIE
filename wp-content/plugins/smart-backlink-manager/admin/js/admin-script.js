jQuery(document).ready(function($) {
    'use strict';
    
    // Vérifier que sbmData est disponible
    if (typeof sbmData === 'undefined') {
        console.error('sbmData n\'est pas disponible');
        return;
    }
    
    console.log('SBM Admin Script chargé');
    
    // Gestion des formulaires AJAX
    $('.sbm-ajax-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const action = $form.data('action');
        
        // Désactiver le bouton
        $submitBtn.prop('disabled', true).text('Chargement...');
        
        // Envoyer la requête AJAX
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: $form.serialize() + '&action=' + action + '&nonce=' + sbmData.nonce,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Afficher le message de succès
                    showNotice(response.data.message, 'success');
                    
                    // Réinitialiser le formulaire
                    $form[0].reset();
                    
                    // Masquer le formulaire
                    $form.closest('.sbm-form-card').hide();
                    
                    // Recharger la liste si nécessaire
                    if (typeof reloadList === 'function') {
                        reloadList();
                    }
                } else {
                    showNotice(response.data.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', error);
                showNotice(sbmData.i18n.error, 'error');
            },
            complete: function() {
                // Réactiver le bouton
                $submitBtn.prop('disabled', false).text($submitBtn.data('original-text') || 'Envoyer');
            }
        });
    });
    
    // Boutons d'ajout
    $('#sbm-add-link-btn, #sbm-add-backlink-btn, #sbm-add-opportunity-btn').on('click', function(e) {
        e.preventDefault();
        const formId = $(this).attr('id').replace('-btn', '-form');
        $('#' + formId).slideToggle();
    });
    
    // Boutons d'annulation
    $('#sbm-cancel-add').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.sbm-form-card').slideUp();
    });
    
    // Bouton "Tout vérifier" pour les backlinks
    $('#sbm-check-all-btn').on('click', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> Vérification...');
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'sbm_check_all_backlinks',
                nonce: sbmData.nonce
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    // Recharger la page après un court délai
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showNotice(response.data.message, 'error');
                }
            },
            error: function() {
                showNotice(sbmData.i18n.error, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Boutons de suppression
    $(document).on('click', '.sbm-delete-link, .sbm-delete-backlink, .sbm-delete-opportunity', function(e) {
        e.preventDefault();
        
        if (!confirm(sbmData.i18n.confirmDelete)) {
            return;
        }
        
        const $btn = $(this);
        const id = $btn.data('id');
        let action = '';
        
        if ($btn.hasClass('sbm-delete-link')) {
            action = 'sbm_delete_internal_link';
        } else if ($btn.hasClass('sbm-delete-backlink')) {
            action = 'sbm_delete_backlink';
        } else if ($btn.hasClass('sbm-delete-opportunity')) {
            action = 'sbm_delete_opportunity';
        }
        
        $btn.prop('disabled', true).text('Suppression...');
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: {
                action: action,
                [action.includes('link') ? 'link_id' : 'opportunity_id']: id,
                nonce: sbmData.nonce
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    $btn.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    showNotice(response.data.message, 'error');
                }
            },
            error: function() {
                showNotice(sbmData.i18n.error, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Supprimer');
            }
        });
    });
    
    // Boutons de vérification individuelle
    $(document).on('click', '.sbm-check-backlink', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const id = $btn.data('id');
        const originalText = $btn.text();
        
        $btn.prop('disabled', true).text('Vérification...');
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'sbm_check_backlink',
                backlink_id: id,
                nonce: sbmData.nonce
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    // Mettre à jour le statut visuellement
                    const statusCell = $btn.closest('tr').find('.sbm-status');
                    if (statusCell.length) {
                        statusCell.removeClass('sbm-status-active sbm-status-inactive sbm-status-dead sbm-status-lost')
                                  .addClass('sbm-status-' + response.data.status)
                                  .text(response.data.status === 'active' ? 'Actif' : 'Inactif');
                    }
                } else {
                    showNotice(response.data.message, 'error');
                }
            },
            error: function() {
                showNotice(sbmData.i18n.error, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Filtres
    $('#sbm-filter-status, #sbm-filter-type, #sbm-filter-source, #sbm-filter-target, #sbm-filter-priority').on('change', function() {
        filterTable();
    });
    
    // Recherche
    $('#sbm-search-links').on('keyup', function() {
        filterTable();
    });
    
    function filterTable() {
        const status = $('#sbm-filter-status').val();
        const type = $('#sbm-filter-type').val();
        const source = $('#sbm-filter-source').val();
        const target = $('#sbm-filter-target').val();
        const priority = $('#sbm-filter-priority').val();
        const search = $('#sbm-search-links').val().toLowerCase();
        
        $('.sbm-links-table tbody tr, .sbm-backlinks-table tbody tr, .sbm-opportunities-table tbody tr').each(function() {
            const $row = $(this);
            let show = true;
            
            // Filtrer par statut
            if (status && $row.find('.sbm-status').length && !$row.find('.sbm-status').hasClass('sbm-status-' + status)) {
                show = false;
            }
            
            // Filtrer par type
            if (type && $row.find('td:eq(2)').length && $row.find('td:eq(2)').text().toLowerCase() !== type.toLowerCase()) {
                show = false;
            }
            
            // Filtrer par priorité
            if (priority && $row.find('.sbm-priority').length && !$row.find('.sbm-priority').hasClass('sbm-priority-' + priority)) {
                show = false;
            }
            
            // Filtrer par recherche
            if (search && $row.text().toLowerCase().indexOf(search) === -1) {
                show = false;
            }
            
            $row.toggle(show);
        });
    }
    
    // Export
    $('#sbm-export-links, #sbm-export-backlinks, #sbm-export-opportunities').on('click', function(e) {
        e.preventDefault();
        
        let exportType = '';
        if ($(this).hasClass('sbm-export-links')) {
            exportType = 'internal_links';
        } else if ($(this).hasClass('sbm-export-backlinks')) {
            exportType = 'backlinks';
        } else if ($(this).hasClass('sbm-export-opportunities')) {
            exportType = 'opportunities';
        }
        
        // Créer un formulaire temporaire pour l'export
        const $form = $('<form>', {
            method: 'POST',
            action: sbmData.ajaxUrl
        });
        
        $form.append($('<input>', {
            type: 'hidden',
            name: 'action',
            value: 'sbm_export_' + exportType
        }));
        
        $form.append($('<input>', {
            type: 'hidden',
            name: 'nonce',
            value: sbmData.nonce
        }));
        
        $('body').append($form);
        $form.submit();
        $form.remove();
    });
    
    // Import CSV
    $('#sbm-import-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'sbm_bulk_import_links');
        formData.append('nonce', sbmData.nonce);
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    // Recharger la page après un court délai
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showNotice(response.data.message, 'error');
                }
            },
            error: function() {
                showNotice(sbmData.i18n.error, 'error');
            }
        });
    });
    
    // Tabs dans les réglages
    $('.sbm-tab-nav a').on('click', function(e) {
        e.preventDefault();
        
        const target = $(this).attr('href');
        
        // Mettre à jour les tabs
        $('.sbm-tab-nav li').removeClass('active');
        $(this).parent().addClass('active');
        
        // Afficher le contenu correspondant
        $('.sbm-tab-content').removeClass('active');
        $(target).addClass('active');
    });
    
    // Initialisation des onglets - s'assurer que le premier onglet est visible
    if ($('.sbm-tab-content').length > 0) {
        // Masquer tous les contenus sauf le premier
        $('.sbm-tab-content:not(.active)').hide();
        
        // S'assurer que le premier onglet est actif
        if (!$('.sbm-tab-nav li.active').length) {
            $('.sbm-tab-nav li:first').addClass('active');
            $('.sbm-tab-content:first').addClass('active').show();
        }
    }
    
    // Ajouter/Supprimer des mots-clés
    $('#sbm-add-keyword').on('click', function() {
        const $container = $('#sbm-keywords-container');
        const $newItem = $('<div class="sbm-keyword-item">' +
            '<input type="text" name="sbm_custom_keywords[]" class="regular-text">' +
            '<button type="button" class="button sbm-remove-keyword">Supprimer</button>' +
            '</div>');
        
        $container.append($newItem);
    });
    
    $(document).on('click', '.sbm-remove-keyword', function() {
        $(this).closest('.sbm-keyword-item').remove();
    });
    
    // Sauvegarder les réglages
    $('#sbm-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: $(this).serialize() + '&action=sbm_save_settings&nonce=' + $('#sbm_settings_nonce').val(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                } else {
                    showNotice(response.data.message, 'error');
                }
            },
            error: function() {
                showNotice(sbmData.i18n.error, 'error');
            },
            complete: function() {
                $submitBtn.prop('disabled', false).text('Enregistrer les modifications');
            }
        });
    });
    
    // Fonction utilitaire pour afficher des notices
    function showNotice(message, type) {
        const $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        
        // Insérer en haut de la page
        $('.wrap h1').after($notice);
        
        // Auto-suppression après 5 secondes
        setTimeout(function() {
            $notice.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Bouton de dismiss
        $notice.on('click', '.notice-dismiss', function() {
            $notice.fadeOut(300, function() {
                $(this).remove();
            });
        });
    }
    
    // Animation pour l'icône de chargement
    $('<style>').text('.dashicons-update.spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }').appendTo('head');
});
