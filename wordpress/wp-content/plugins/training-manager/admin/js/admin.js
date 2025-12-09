/**
 * Training Manager - Admin Scripts
 *
 * @package TrainingManager
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialisation
     */
    $(document).ready(function() {
        initTimeSlots();
        initDocuments();
        initStatusChange();
        initColorPickers();
        initTabs();
    });

    /**
     * Gestion des créneaux horaires
     */
    function initTimeSlots() {
        let slotIndex = $('#tm-time-slots-container .tm-time-slot-row').length;

        // Ajouter un créneau
        $('#tm-add-time-slot').on('click', function() {
            const html = `
                <div class="tm-time-slot-row">
                    <input type="time" name="tm_time_slots[${slotIndex}][start]" value="09:00">
                    <span>-</span>
                    <input type="time" name="tm_time_slots[${slotIndex}][end]" value="12:00">
                    <button type="button" class="button tm-remove-slot">&times;</button>
                </div>
            `;
            $('#tm-time-slots-container').append(html);
            slotIndex++;
        });

        // Supprimer un créneau
        $(document).on('click', '.tm-remove-slot', function() {
            $(this).closest('.tm-time-slot-row').remove();
        });
    }

    /**
     * Gestion des documents
     */
    function initDocuments() {
        let mediaUploader;

        // Ajouter un document
        $('#tm-add-document').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: 'Sélectionner un document',
                button: {
                    text: 'Ajouter'
                },
                multiple: true,
                library: {
                    type: ['application/pdf', 'image']
                }
            });

            mediaUploader.on('select', function() {
                const attachments = mediaUploader.state().get('selection').toJSON();
                
                attachments.forEach(function(attachment) {
                    const html = `
                        <div class="tm-document-item">
                            <span class="tm-doc-name">${attachment.filename}</span>
                            <input type="hidden" name="tm_documents[]" value="${attachment.url}">
                            <button type="button" class="button tm-remove-doc">&times;</button>
                        </div>
                    `;
                    $('#tm-documents-container').append(html);
                });
            });

            mediaUploader.open();
        });

        // Supprimer un document
        $(document).on('click', '.tm-remove-doc', function() {
            $(this).closest('.tm-document-item').remove();
        });
    }

    /**
     * Changement de statut
     */
    function initStatusChange() {
        $('#tm_status').on('change', function() {
            const status = $(this).val();
            const $indicator = $('.tm-status-indicator');
            
            // Supprimer les anciennes classes
            $indicator.removeClass('tm-status-open tm-status-full tm-status-waitlist tm-status-cancelled tm-status-completed');
            
            // Ajouter la nouvelle classe
            $indicator.addClass('tm-status-' + status);
            
            // Mettre à jour le texte
            const labels = {
                'open': 'Ouvert aux inscriptions',
                'full': 'Complet',
                'waitlist': 'Liste d\'attente',
                'cancelled': 'Annulé',
                'completed': 'Terminé'
            };
            
            $indicator.find('.tm-status-text').text(labels[status] || status);
        });
    }

    /**
     * Color Pickers
     */
    function initColorPickers() {
        if ($.fn.wpColorPicker) {
            $('input[type="color"]').each(function() {
                // Convertir en input text pour wpColorPicker
                const $input = $(this);
                const value = $input.val();
                
                $input.attr('type', 'text').wpColorPicker();
            });
        }
    }

    /**
     * Tabs de paramètres
     */
    function initTabs() {
        $('.tm-settings-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            const target = $(this).attr('href');
            
            // Activer l'onglet
            $('.tm-settings-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Afficher le contenu
            $('.tm-tab-content').hide();
            $(target).show();
        });
    }

    /**
     * Confirmation de suppression
     */
    $(document).on('click', '.tm-delete-action', function(e) {
        if (!confirm(tmAdmin.strings.confirmDelete)) {
            e.preventDefault();
        }
    });

    /**
     * Mise à jour automatique des places restantes
     */
    $('#tm_total_places, #tm_reserved_places').on('change', function() {
        const total = parseInt($('#tm_total_places').val()) || 0;
        const reserved = parseInt($('[name="tm_reserved_places"]').val()) || 0;
        const remaining = total - reserved;
        
        $('.tm-remaining').text(remaining);
        
        // Mettre à jour la barre
        const percentage = total > 0 ? (reserved / total * 100) : 0;
        $('.tm-capacity-fill').css('width', percentage + '%');
        
        // Avertissement si peu de places
        if (remaining <= 2 && remaining > 0) {
            $('.tm-remaining').addClass('tm-warning');
        } else {
            $('.tm-remaining').removeClass('tm-warning');
        }
    });

    /**
     * Validation des dates
     */
    $('#tm_start_date').on('change', function() {
        const startDate = $(this).val();
        const $endDate = $('#tm_end_date');
        
        // La date de fin doit être >= date de début
        $endDate.attr('min', startDate);
        
        if ($endDate.val() && $endDate.val() < startDate) {
            $endDate.val(startDate);
        }
    });

    /**
     * Duplication de session
     */
    $(document).on('click', '.tm-duplicate-session', function(e) {
        e.preventDefault();
        
        const postId = $(this).data('post-id');
        
        $.ajax({
            url: tmAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'tm_duplicate_session',
                post_id: postId,
                nonce: tmAdmin.nonce
            },
            beforeSend: function() {
                $(this).text(tmAdmin.strings.loading);
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.edit_url;
                } else {
                    alert(response.data.message || tmAdmin.strings.error);
                }
            },
            error: function() {
                alert(tmAdmin.strings.error);
            }
        });
    });

})(jQuery);
