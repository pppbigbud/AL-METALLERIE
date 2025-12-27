jQuery(document).ready(function($) {
    'use strict';
    
    // Variables globales
    var sbmData = window.sbmData || {};
    
    // Initialisation des tooltips
    $('.sbm-tooltip').tooltip();
    
    // Gestion des boutons d'ajout rapide
    $('#sbm-add-link-btn').on('click', function(e) {
        e.preventDefault();
        $('#sbm-add-link-form').toggle();
    });
    
    $('#sbm-add-backlink-btn').on('click', function(e) {
        e.preventDefault();
        $('#sbm-add-backlink-form').toggle();
    });
    
    $('#sbm-add-opportunity-btn').on('click', function(e) {
        e.preventDefault();
        $('#sbm-add-opportunity-form').toggle();
    });
    
    // Gestion du bouton "Tout vérifier"
    $('#sbm-check-all-btn').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="spinner is-active"></span> Vérification...');
        
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
                    sbmShowNotice(response.data.message || 'Vérification terminée', 'success');
                    location.reload();
                } else {
                    sbmShowNotice(response.data.message || 'Erreur lors de la vérification', 'error');
                }
            },
            error: function() {
                sbmShowNotice('Erreur de communication avec le serveur', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Debug: Vérifier que sbmData est bien défini
    if (typeof sbmData === 'undefined') {
        console.error('sbmData n\'est pas défini. Vérifiez que wp_localize_script fonctionne correctement.');
    } else {
        console.log('sbmData chargé:', sbmData);
    }
    
    // Gestion des formulaires AJAX
    $('.sbm-ajax-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submit = $form.find('button[type="submit"]');
        var originalText = $submit.text();
        
        // Afficher le chargement
        $submit.prop('disabled', true).html('<span class="spinner is-active"></span> ' + (sbmData.i18n && sbmData.i18n.loading ? sbmData.i18n.loading : 'Chargement...'));
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: $form.serialize() + '&action=' + $form.data('action') + '&nonce=' + sbmData.nonce,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Afficher le message de succès
                    sbmShowNotice(response.data.message || 'Opération réussie', 'success');
                    
                    // Actions spécifiques selon le formulaire
                    if ($form.hasClass('sbm-add-backlink-form')) {
                        $form[0].reset();
                        sbmRefreshBacklinksList();
                    } else if ($form.hasClass('sbm-settings-form')) {
                        // Les réglages ont été sauvegardés
                    }
                } else {
                    sbmShowNotice(response.data.message || 'Une erreur est survenue', 'error');
                }
            },
            error: function() {
                sbmShowNotice('Erreur de communication avec le serveur', 'error');
            },
            complete: function() {
                $submit.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Gestion des boutons de suppression
    $(document).on('click', '.sbm-delete-btn', function(e) {
        e.preventDefault();
        
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            return;
        }
        
        var $btn = $(this);
        var itemId = $btn.data('id');
        var itemType = $btn.data('type');
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'sbm_delete_' + itemType,
                id: itemId,
                nonce: sbmData.nonce
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $btn.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                    sbmShowNotice(response.data.message || 'Élément supprimé', 'success');
                } else {
                    sbmShowNotice(response.data.message || 'Erreur lors de la suppression', 'error');
                }
            },
            error: function() {
                sbmShowNotice('Erreur de communication avec le serveur', 'error');
            }
        });
    });
    
    // Gestion des boutons de vérification de backlink
    $(document).on('click', '.sbm-check-backlink-btn', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var backlinkId = $btn.data('id');
        var $statusCell = $btn.closest('tr').find('.sbm-status-cell');
        
        $btn.prop('disabled', true).html('<span class="spinner is-active"></span>');
        
        $.ajax({
            url: sbmData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'sbm_check_backlink',
                id: backlinkId,
                nonce: sbmData.nonce
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $statusCell.html(response.data.status_html);
                    sbmShowNotice('Backlink vérifié', 'success');
                } else {
                    sbmShowNotice(response.data.message || 'Erreur lors de la vérification', 'error');
                }
            },
            error: function() {
                sbmShowNotice('Erreur de communication avec le serveur', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Vérifier');
            }
        });
    });
    
    // Gestion des onglets
    $('.sbm-tab-nav a').on('click', function(e) {
        e.preventDefault();
        
        var $tab = $(this);
        var target = $tab.attr('href');
        
        // Mettre à jour les onglets
        $('.sbm-tab-nav li').removeClass('active');
        $tab.closest('li').addClass('active');
        
        // Afficher le contenu correspondant
        $('.sbm-tab-content').hide();
        $(target).show();
    });
    
    // Gestion des filtres
    $('.sbm-filter-select').on('change', function() {
        var $form = $(this).closest('form');
        $form.submit();
    });
    
    // Gestion de la recherche
    var searchTimeout;
    $('.sbm-search-input').on('keyup', function() {
        var $input = $(this);
        var searchTerm = $input.val();
        
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(function() {
            var $form = $input.closest('form');
            $form.submit();
        }, 500);
    });
    
    // Fonctions utilitaires
    function sbmShowNotice(message, type) {
        type = type || 'info';
        
        var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        
        $('.wrap h1').after($notice);
        
        // Auto-dismiss après 5 secondes
        setTimeout(function() {
            $notice.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Gérer le bouton de dismiss
        $notice.on('click', '.notice-dismiss', function() {
            $notice.remove();
        });
    }
    
    function sbmRefreshBacklinksList() {
        // Recharger la liste des backlinks via AJAX
        var $listContainer = $('.sbm-backlinks-list');
        
        if ($listContainer.length) {
            $.ajax({
                url: sbmData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'sbm_refresh_backlinks_list',
                    nonce: sbmData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $listContainer.html(response.data.html);
                    }
                }
            });
        }
    }
    
    // Initialisation des graphiques si Chart.js est disponible
    if (typeof Chart !== 'undefined') {
        sbmInitCharts();
    }
    
    function sbmInitCharts() {
        // Graphique d'évolution des liens
        var $linkChart = $('#sbm-links-chart');
        if ($linkChart.length) {
            var ctx = $linkChart[0].getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: sbmData.chartLabels || [],
                    datasets: [{
                        label: 'Liens internes',
                        data: sbmData.internalLinksData || [],
                        borderColor: '#0073aa',
                        backgroundColor: 'rgba(0, 115, 170, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Backlinks',
                        data: sbmData.backlinksData || [],
                        borderColor: '#66c6e4',
                        backgroundColor: 'rgba(102, 198, 228, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    // Gestion de l'import CSV
    $('.sbm-import-csv-btn').on('click', function(e) {
        e.preventDefault();
        
        var $fileInput = $('#sbm-import-csv-file');
        $fileInput.click();
    });
    
    $('#sbm-import-csv-file').on('change', function() {
        var file = this.files[0];
        
        if (file) {
            var formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'sbm_import_csv');
            formData.append('nonce', sbmData.nonce);
            
            $.ajax({
                url: sbmData.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('.sbm-import-progress').show();
                },
                success: function(response) {
                    if (response.success) {
                        sbmShowNotice(response.data.message || 'Import réussi', 'success');
                        location.reload();
                    } else {
                        sbmShowNotice(response.data.message || 'Erreur lors de l\'import', 'error');
                    }
                },
                error: function() {
                    sbmShowNotice('Erreur de communication avec le serveur', 'error');
                },
                complete: function() {
                    $('.sbm-import-progress').hide();
                }
            });
        }
    });
    
    // Gestion des mots-clés personnalisés
    $('.sbm-add-keyword-btn').on('click', function(e) {
        e.preventDefault();
        
        var $input = $('.sbm-keyword-input');
        var keyword = $input.val().trim();
        
        if (keyword) {
            var $list = $('.sbm-keywords-list');
            var $keywordTag = $('<span class="sbm-keyword-tag">' + keyword + '<button type="button" class="sbm-remove-keyword">×</button></span>');
            
            $list.append($keywordTag);
            $input.val('');
            sbmUpdateKeywordsField();
        }
    });
    
    $(document).on('click', '.sbm-remove-keyword', function() {
        $(this).closest('.sbm-keyword-tag').remove();
        sbmUpdateKeywordsField();
    });
    
    function sbmUpdateKeywordsField() {
        var keywords = [];
        $('.sbm-keyword-tag').each(function() {
            keywords.push($(this).text().replace('×', '').trim());
        });
        $('#sbm-keywords-field').val(JSON.stringify(keywords));
    }
});
