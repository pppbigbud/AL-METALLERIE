/**
 * City Pages Generator - Admin JavaScript
 */

(function($) {
    'use strict';

    // Initialisation
    $(document).ready(function() {
        initTabs();
        initAddCityForm();
        initListActions();
        initExportImport();
        initMetaboxActions();
    });

    /**
     * Gestion des onglets
     */
    function initTabs() {
        $('.cpg-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            var target = $(this).attr('href');
            
            // Activer l'onglet
            $('.cpg-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Afficher le contenu
            $('.cpg-tab-content').removeClass('cpg-tab-active');
            $(target).addClass('cpg-tab-active');
        });
    }

    /**
     * Formulaire d'ajout de ville
     */
    function initAddCityForm() {
        $('#cpg-add-city-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $btn = $('#cpg-generate-btn');
            var $spinner = $('#cpg-spinner');
            var $message = $('#cpg-result-message');
            
            // Désactiver le bouton
            $btn.prop('disabled', true);
            $spinner.addClass('is-active');
            $message.hide();
            
            // Collecter les données
            var data = {
                action: 'cpg_create_city',
                nonce: cpgAdmin.nonce,
                city_name: $('#city_name').val(),
                postal_code: $('#postal_code').val(),
                department: $('#department').val(),
                priority: $('#priority').val(),
                distance_km: $('#distance_km').val(),
                travel_time: $('#travel_time').val(),
                local_specifics: $('#local_specifics').val(),
                nearby_cities: $('#nearby_cities').val(),
                post_status: $('#post_status').val()
            };
            
            // Envoyer la requête
            $.post(cpgAdmin.ajaxUrl, data, function(response) {
                $btn.prop('disabled', false);
                $spinner.removeClass('is-active');
                
                if (response.success) {
                    $message
                        .removeClass('cpg-error')
                        .addClass('cpg-success')
                        .html(
                            response.data.message + 
                            ' <a href="' + response.data.edit_url + '">Modifier</a> | ' +
                            '<a href="' + response.data.view_url + '" target="_blank">Voir</a>'
                        )
                        .show();
                    
                    // Réinitialiser le formulaire
                    $form[0].reset();
                } else {
                    $message
                        .removeClass('cpg-success')
                        .addClass('cpg-error')
                        .text(response.data.message)
                        .show();
                }
            }).fail(function() {
                $btn.prop('disabled', false);
                $spinner.removeClass('is-active');
                $message
                    .removeClass('cpg-success')
                    .addClass('cpg-error')
                    .text(cpgAdmin.strings.error)
                    .show();
            });
        });
    }

    /**
     * Actions dans la liste des villes
     */
    function initListActions() {
        // Regénérer le contenu
        $(document).on('click', '.cpg-regenerate-link', function(e) {
            e.preventDefault();
            
            if (!confirm(cpgAdmin.strings.confirmRegenerate)) {
                return;
            }
            
            var $link = $(this);
            var postId = $link.data('post-id');
            
            $link.text(cpgAdmin.strings.generating);
            
            $.post(cpgAdmin.ajaxUrl, {
                action: 'cpg_regenerate_content',
                nonce: cpgAdmin.nonce,
                post_id: postId
            }, function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                    $link.text('Regénérer');
                }
            });
        });
        
        // Supprimer
        $(document).on('click', '.cpg-delete-link', function(e) {
            e.preventDefault();
            
            if (!confirm(cpgAdmin.strings.confirmDelete)) {
                return;
            }
            
            var $link = $(this);
            var postId = $link.data('post-id');
            
            $.post(cpgAdmin.ajaxUrl, {
                action: 'cpg_delete_city',
                nonce: cpgAdmin.nonce,
                post_id: postId
            }, function(response) {
                if (response.success) {
                    $link.closest('tr').fadeOut(function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.data.message);
                }
            });
        });
    }

    /**
     * Export/Import
     */
    function initExportImport() {
        // Export CSV
        $('#cpg-export-btn').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).text('Export en cours...');
            
            $.post(cpgAdmin.ajaxUrl, {
                action: 'cpg_export_csv',
                nonce: cpgAdmin.nonce
            }, function(response) {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-download"></span> Exporter en CSV');
                
                if (response.success) {
                    // Créer et télécharger le fichier
                    var blob = new Blob([response.data.csv], { type: 'text/csv;charset=utf-8;' });
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = response.data.filename;
                    link.click();
                } else {
                    alert(response.data.message);
                }
            });
        });
        
        // Import CSV
        $('#cpg-import-form').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            formData.append('action', 'cpg_import_csv');
            
            var $result = $('#cpg-import-result');
            $result.hide();
            
            $.ajax({
                url: cpgAdmin.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $result
                            .removeClass('cpg-error')
                            .addClass('cpg-success cpg-message')
                            .text(response.data.message)
                            .show();
                        
                        if (response.data.errors && response.data.errors.length > 0) {
                            $result.append('<br><br><strong>Erreurs :</strong><br>' + response.data.errors.join('<br>'));
                        }
                    } else {
                        $result
                            .removeClass('cpg-success')
                            .addClass('cpg-error cpg-message')
                            .text(response.data.message)
                            .show();
                    }
                }
            });
        });
        
        // Génération en masse
        $('#cpg-bulk-generate-form').on('submit', function(e) {
            e.preventDefault();
            
            var data = {
                action: 'cpg_bulk_generate',
                nonce: cpgAdmin.nonce,
                batch_size: $('#batch_size').val(),
                publish_immediately: $('input[name="publish_immediately"]').is(':checked') ? '1' : '0'
            };
            
            $.post(cpgAdmin.ajaxUrl, data, function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            });
        });
    }

    /**
     * Actions dans les metaboxes
     */
    function initMetaboxActions() {
        // Regénérer depuis la page d'édition
        $('#cpg_regenerate_content').on('click', function() {
            if (!confirm(cpgAdmin.strings.confirmRegenerate)) {
                return;
            }
            
            var $btn = $(this);
            var postId = $btn.data('post-id');
            
            $btn.prop('disabled', true).find('.dashicons').addClass('spin');
            
            $.post(cpgAdmin.ajaxUrl, {
                action: 'cpg_regenerate_content',
                nonce: cpgAdmin.nonce,
                post_id: postId
            }, function(response) {
                $btn.prop('disabled', false).find('.dashicons').removeClass('spin');
                
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            });
        });
        
        // Compteur de caractères SEO
        $('#cpg_meta_title').on('input', function() {
            $('#cpg_title_count').text($(this).val().length);
        });
        
        $('#cpg_meta_description').on('input', function() {
            $('#cpg_desc_count').text($(this).val().length);
        });
    }

    // Animation de rotation pour le spinner
    $('<style>.spin { animation: cpg-spin 1s linear infinite; } @keyframes cpg-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>').appendTo('head');

})(jQuery);
