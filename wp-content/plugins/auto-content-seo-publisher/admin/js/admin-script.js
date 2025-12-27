/**
 * Auto Content SEO Publisher - Admin Script
 */
jQuery(document).ready(function($) {
    'use strict';
    
    // Variables globales
    var acsp = {
        ajaxUrl: acspData.ajaxUrl,
        nonce: acspData.nonce,
        i18n: acspData.i18n
    };
    
    // Initialisation
    init();
    
    function init() {
        initTabs();
        initForceGeneration();
        initSettingsSave();
        initKeywordManager();
        initImageImporter();
        initHistoryFilters();
        initTooltips();
    }
    
    /**
     * Initialisation des onglets
     */
    function initTabs() {
        $('.acsp-tab').on('click', function() {
            var $tab = $(this);
            var target = $tab.data('tab');
            
            // Activer l'onglet
            $('.acsp-tab').removeClass('active');
            $tab.addClass('active');
            
            // Afficher le contenu
            $('.acsp-tab-content').removeClass('active');
            $('#' + target).addClass('active');
            
            // Sauvegarder l'onglet actif
            localStorage.setItem('acsp_active_tab', target);
        });
        
        // Restaurer le dernier onglet actif
        var activeTab = localStorage.getItem('acsp_active_tab');
        if (activeTab && $('#' + activeTab).length) {
            $('.acsp-tab[data-tab="' + activeTab + '"]').trigger('click');
        }
    }
    
    /**
     * Initialisation de la génération forcée
     */
    function initForceGeneration() {
        $('#acsp-force-generate').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm(acsp.i18n.confirm)) {
                return;
            }
            
            var $button = $(this);
            var originalText = $button.html();
            
            // Désactiver le bouton
            $button.prop('disabled', true).html('<span class="acsp-spinner"></span> ' + acsp.i18n.loading);
            
            // Envoyer la requête AJAX
            $.ajax({
                url: acsp.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'acsp_force_generation',
                    nonce: acsp.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('success', 'Article généré avec succès !');
                        
                        // Mettre à jour les statistiques
                        if (typeof updateStats === 'function') {
                            updateStats();
                        }
                        
                        // Ajouter à l'historique
                        if ($('#acsp-history-table').length) {
                            addHistoryItem(response.data);
                        }
                    } else {
                        showNotification('error', response.data || acsp.i18n.error);
                    }
                },
                error: function() {
                    showNotification('error', acsp.i18n.error);
                },
                complete: function() {
                    $button.prop('disabled', false).html(originalText);
                }
            });
        });
    }
    
    /**
     * Initialisation de la sauvegarde des réglages
     */
    function initSettingsSave() {
        $('#acsp-settings-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var originalText = $button.html();
            
            // Désactiver le bouton
            $button.prop('disabled', true).html('<span class="acsp-spinner"></span> ' + acsp.i18n.loading);
            
            // Collecter les données du formulaire
            var formData = new FormData($form[0]);
            formData.append('action', 'acsp_save_settings');
            formData.append('nonce', acsp.nonce);
            
            // Envoyer la requête AJAX
            $.ajax({
                url: acsp.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showNotification('success', 'Réglages sauvegardés avec succès !');
                    } else {
                        showNotification('error', response.data || acsp.i18n.error);
                    }
                },
                error: function() {
                    showNotification('error', acsp.i18n.error);
                },
                complete: function() {
                    $button.prop('disabled', false).html(originalText);
                }
            });
        });
    }
    
    /**
     * Initialisation du gestionnaire de mots-clés
     */
    function initKeywordManager() {
        // Ajouter un mot-clé
        $('#acsp-add-keyword').on('click', function() {
            var keyword = $('#acsp-new-keyword').val().trim();
            var category = $('#acsp-keyword-category').val();
            
            if (!keyword) {
                showNotification('error', 'Veuillez entrer un mot-clé');
                return;
            }
            
            $.ajax({
                url: acsp.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'acsp_add_keyword',
                    nonce: acsp.nonce,
                    keyword: keyword,
                    category: category
                },
                success: function(response) {
                    if (response.success) {
                        addKeywordToList(response.data);
                        $('#acsp-new-keyword').val('');
                        showNotification('success', 'Mot-clé ajouté avec succès');
                    } else {
                        showNotification('error', response.data || acsp.i18n.error);
                    }
                },
                error: function() {
                    showNotification('error', acsp.i18n.error);
                }
            });
        });
        
        // Supprimer un mot-clé
        $(document).on('click', '.acsp-delete-keyword', function() {
            var $button = $(this);
            var keywordId = $button.data('id');
            
            if (!confirm(acsp.i18n.confirm)) {
                return;
            }
            
            $.ajax({
                url: acsp.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'acsp_delete_keyword',
                    nonce: acsp.nonce,
                    id: keywordId
                },
                success: function(response) {
                    if (response.success) {
                        $button.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                        });
                        showNotification('success', 'Mot-clé supprimé');
                    } else {
                        showNotification('error', response.data || acsp.i18n.error);
                    }
                },
                error: function() {
                    showNotification('error', acsp.i18n.error);
                }
            });
        });
    }
    
    /**
     * Initialisation de l'importateur d'images
     */
    function initImageImporter() {
        $('#acsp-import-images').on('click', function() {
            var $button = $(this);
            var query = $('#acsp-image-query').val().trim();
            var count = $('#acsp-image-count').val() || 5;
            
            if (!query) {
                showNotification('error', 'Veuillez entrer un terme de recherche');
                return;
            }
            
            $button.prop('disabled', true).html('<span class="acsp-spinner"></span> Import...');
            
            $.ajax({
                url: acsp.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'acsp_import_images',
                    nonce: acsp.nonce,
                    query: query,
                    count: count
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('success', response.data.count + ' images importées');
                        // Mettre à jour la galerie si nécessaire
                        if (typeof updateImageGallery === 'function') {
                            updateImageGallery();
                        }
                    } else {
                        showNotification('error', response.data || acsp.i18n.error);
                    }
                },
                error: function() {
                    showNotification('error', acsp.i18n.error);
                },
                complete: function() {
                    $button.prop('disabled', false).html('Importer');
                }
            });
        });
    }
    
    /**
     * Initialisation des filtres d'historique
     */
    function initHistoryFilters() {
        $('#acsp-filter-status, #acsp-filter-type, #acsp-filter-date').on('change', function() {
            var status = $('#acsp-filter-status').val();
            var type = $('#acsp-filter-type').val();
            var date = $('#acsp-filter-date').val();
            
            // Filtrer les lignes du tableau
            $('#acsp-history-table tbody tr').each(function() {
                var $row = $(this);
                var show = true;
                
                if (status && $row.data('status') !== status) {
                    show = false;
                }
                
                if (type && $row.data('type') !== type) {
                    show = false;
                }
                
                if (date && !$row.hasClass('date-' + date)) {
                    show = false;
                }
                
                $row.toggle(show);
            });
            
            // Afficher un message si aucun résultat
            var visibleRows = $('#acsp-history-table tbody tr:visible').length;
            $('.acsp-no-results').toggle(visibleRows === 0);
        });
    }
    
    /**
     * Initialisation des tooltips
     */
    function initTooltips() {
        $('.acsp-tooltip').each(function() {
            var $element = $(this);
            var tooltip = $element.data('tooltip');
            
            $element.on('mouseenter', function() {
                var $tooltip = $('<div class="acsp-tooltip-popup">' + tooltip + '</div>');
                $('body').append($tooltip);
                
                var position = $element.offset();
                $tooltip.css({
                    top: position.top - $tooltip.outerHeight() - 10,
                    left: position.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
                }).fadeIn(200);
            });
            
            $element.on('mouseleave', function() {
                $('.acsp-tooltip-popup').fadeOut(200, function() {
                    $(this).remove();
                });
            });
        });
    }
    
    /**
     * Afficher une notification
     */
    function showNotification(type, message) {
        var $notification = $('<div class="acsp-notification ' + type + '">' +
            '<span class="acsp-notification-message">' + message + '</span>' +
            '<button class="acsp-notification-close">&times;</button>' +
            '</div>');
        
        // Ajouter au début de la page
        $('.wrap h1').after($notification);
        
        // Animation d'entrée
        $notification.hide().slideDown(300);
        
        // Fermeture automatique après 5 secondes
        setTimeout(function() {
            $notification.slideUp(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Fermeture manuelle
        $notification.find('.acsp-notification-close').on('click', function() {
            $notification.slideUp(300, function() {
                $(this).remove();
            });
        });
    }
    
    /**
     * Ajouter un mot-clé à la liste
     */
    function addKeywordToList(keyword) {
        var $row = $('<tr>' +
            '<td>' + keyword.keyword + '</td>' +
            '<td>' + keyword.category + '</td>' +
            '<td>' + keyword.times_used + '</td>' +
            '<td>' +
                '<button type="button" class="button button-small acsp-delete-keyword" data-id="' + keyword.id + '">' +
                    'Supprimer' +
                '</button>' +
            '</td>' +
        '</tr>');
        
        $('#acsp-keywords-list tbody').append($row);
        $row.hide().fadeIn(300);
    }
    
    /**
     * Ajouter un élément à l'historique
     */
    function addHistoryItem(item) {
        var $row = $('<tr data-status="' + item.status + '" data-type="' + item.type + '">' +
            '<td><a href="' + item.edit_link + '">' + item.title + '</a></td>' +
            '<td><span class="acsp-badge ' + item.status + '">' + item.status_label + '</span></td>' +
            '<td>' + item.type_label + '</td>' +
            '<td><span class="acsp-seo-score ' + item.seo_class + '">' + item.seo_score + '/100</span></td>' +
            '<td>' + item.date + '</td>' +
        '</tr>');
        
        $('#acsp-history-table tbody').prepend($row);
        $row.hide().fadeIn(300);
    }
    
    /**
     * Mettre à jour les statistiques du dashboard
     */
    window.updateStats = function() {
        $.ajax({
            url: acsp.ajaxUrl,
            type: 'POST',
            data: {
                action: 'acsp_get_stats',
                nonce: acsp.nonce
            },
            success: function(response) {
                if (response.success) {
                    var stats = response.data;
                    $('.acsp-stat-articles .number').text(stats.total);
                    $('.acsp-stat-published .number').text(stats.published);
                    $('.acsp-stat-success .number').text(stats.success_rate + '%');
                    $('.acsp-stat-seo .number').text(stats.avg_seo_score);
                }
            }
        });
    };
    
    /**
     * Mettre à jour la galerie d'images
     */
    window.updateImageGallery = function() {
        // Implémenter si nécessaire
    };
});
