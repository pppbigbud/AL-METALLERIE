/**
 * Admin Formations Cards - JavaScript
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        var cardCounter = $('.slide-editor').length;

        /**
         * Upload d'image via Media Library
         */
        function initMediaUploader() {
            $(document).on('click', '.upload-image-button', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $wrapper = $button.closest('.image-upload-wrapper');
                var $input = $wrapper.find('.image-url-input');
                var $preview = $wrapper.find('.image-preview');
                
                var mediaUploader = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                    library: { type: 'image' }
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    var imageUrl = attachment.url;
                    
                    // Préférer la version WebP si disponible
                    if (attachment.sizes && attachment.sizes.large) {
                        imageUrl = attachment.sizes.large.url;
                    }
                    
                    $input.val(imageUrl);
                    $preview.addClass('has-image');
                    
                    if ($preview.find('img').length) {
                        $preview.find('img').attr('src', imageUrl);
                    } else {
                        $preview.prepend('<img src="' + imageUrl + '" alt="Aperçu">');
                    }
                    
                    // Mettre à jour le bouton
                    $button.html('<span class="dashicons dashicons-upload"></span> Changer l\'image');
                    
                    // Ajouter le bouton supprimer si pas présent
                    if (!$wrapper.find('.remove-image-button').length) {
                        $button.after('<button type="button" class="button button-link-delete remove-image-button"><span class="dashicons dashicons-no"></span> Supprimer</button>');
                    }
                    
                    console.log('📷 Image sélectionnée:', imageUrl);
                });
                
                mediaUploader.open();
            });
        }

        /**
         * Suppression d'image
         */
        function initImageRemoval() {
            $(document).on('click', '.remove-image-button', function(e) {
                e.preventDefault();
                
                var $wrapper = $(this).closest('.image-upload-wrapper');
                var $input = $wrapper.find('.image-url-input');
                var $preview = $wrapper.find('.image-preview');
                var $uploadBtn = $wrapper.find('.upload-image-button');
                
                $input.val('');
                $preview.removeClass('has-image').find('img').remove();
                $uploadBtn.html('<span class="dashicons dashicons-upload"></span> Choisir une image');
                $(this).remove();
                
                console.log('🗑️ Image supprimée');
            });
        }

        /**
         * Toggle activation card
         */
        function initCardToggle() {
            $(document).on('change', '.slide-active-toggle', function() {
                var $editor = $(this).closest('.slide-editor');
                var $label = $(this).closest('.slide-toggle').find('.toggle-label');
                
                if ($(this).is(':checked')) {
                    $editor.removeClass('slide-inactive');
                    $label.text('Activée');
                } else {
                    $editor.addClass('slide-inactive');
                    $label.text('Désactivée');
                }
            });
        }

        /**
         * Drag & Drop pour réorganiser
         */
        function initSortable() {
            $('#cards-container').sortable({
                handle: '.slide-drag-handle',
                placeholder: 'slide-placeholder',
                opacity: 0.7,
                update: function(event, ui) {
                    updateCardIndexes();
                    console.log('🔄 Ordre des cards mis à jour');
                }
            });
        }

        /**
         * Mettre à jour les index après réorganisation
         */
        function updateCardIndexes() {
            $('.slide-editor').each(function(index) {
                $(this).attr('data-card-index', index);
                $(this).find('.card-order-input').val(index);
                $(this).find('.slide-title-header').html(
                    'Card ' + (index + 1) + ' : ' + $(this).find('input[name*="[title]"]').val()
                );
                
                // Mettre à jour tous les noms de champs
                $(this).find('input, textarea, select').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/cards\[\d+\]/, 'cards[' + index + ']'));
                    }
                });
            });
        }

        /**
         * Ajouter une nouvelle card
         */
        function initAddCard() {
            $('#add-card-btn').on('click', function() {
                var newIndex = cardCounter;
                
                var cardHtml = `
                <div class="slide-editor" data-card-index="${newIndex}">
                    <div class="slide-header">
                        <div class="slide-drag-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        <h2 class="slide-title-header">
                            Card ${newIndex + 1} : Nouvelle card
                        </h2>
                        
                        <div class="slide-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       name="cards[${newIndex}][active]" 
                                       value="1" 
                                       checked
                                       class="slide-active-toggle">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Activée</span>
                        </div>
                        
                        <button type="button" class="button button-link-delete delete-card-btn" title="Supprimer cette card">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                    
                    <div class="slide-content">
                        <input type="hidden" name="cards[${newIndex}][order]" value="${newIndex}" class="card-order-input">
                        
                        <!-- Image -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-format-image"></span>
                                Image de la card
                            </label>
                            <div class="image-upload-wrapper">
                                <div class="image-preview">
                                    <div class="image-preview-overlay">
                                        <button type="button" class="button upload-image-button">
                                            <span class="dashicons dashicons-upload"></span>
                                            Choisir une image
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="cards[${newIndex}][image]" value="" class="image-url-input">
                            </div>
                        </div>
                        
                        <!-- Alt -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-visibility"></span>
                                Texte alternatif (SEO)
                            </label>
                            <input type="text" name="cards[${newIndex}][image_alt]" value="" class="form-control" placeholder="Description de l'image...">
                        </div>
                        
                        <!-- Icône -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-star-filled"></span>
                                Icône de la card
                            </label>
                            <select name="cards[${newIndex}][icon]" class="form-control">
                                <option value="">-- Choisir une icône --</option>
                                <option value="professionnels">👥 Professionnels</option>
                                <option value="particuliers">🏠 Particuliers</option>
                                <option value="entreprise">🏢 Entreprise</option>
                                <option value="formation">🎓 Formation</option>
                                <option value="outils">🔧 Outils</option>
                                <option value="soudure">⚡ Soudure</option>
                                <option value="certificat">📜 Certificat</option>
                                <option value="calendrier">📅 Calendrier</option>
                            </select>
                        </div>
                        
                        <!-- Titre -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-text"></span>
                                Titre de la card <span class="required">*</span>
                            </label>
                            <input type="text" name="cards[${newIndex}][title]" value="" class="form-control" placeholder="Ex: PROFESSIONNELS" required>
                        </div>
                        
                        <!-- Description -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-editor-alignleft"></span>
                                Description
                            </label>
                            <textarea name="cards[${newIndex}][description]" class="form-control" rows="3" placeholder="Description..."></textarea>
                        </div>
                        
                        <!-- Features -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-yes-alt"></span>
                                Points clés (un par ligne)
                            </label>
                            <textarea name="cards[${newIndex}][features]" class="form-control" rows="4" placeholder="Point 1&#10;Point 2&#10;Point 3"></textarea>
                        </div>
                        
                        <!-- CTA -->
                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label class="form-label">Texte du bouton</label>
                                <input type="text" name="cards[${newIndex}][cta_text]" value="En savoir +" class="form-control">
                            </div>
                            <div class="form-group form-group-half">
                                <label class="form-label">URL du bouton</label>
                                <input type="text" name="cards[${newIndex}][cta_url]" value="" class="form-control" placeholder="/ma-page">
                            </div>
                        </div>
                    </div>
                </div>`;
                
                $('#cards-container').append(cardHtml);
                cardCounter++;
                
                // Scroll vers la nouvelle card
                $('html, body').animate({
                    scrollTop: $('.slide-editor').last().offset().top - 100
                }, 500);
                
                console.log('➕ Nouvelle card ajoutée');
            });
        }

        /**
         * Supprimer une card
         */
        function initDeleteCard() {
            $(document).on('click', '.delete-card-btn', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette card ?')) {
                    $(this).closest('.slide-editor').fadeOut(300, function() {
                        $(this).remove();
                        updateCardIndexes();
                        console.log('🗑️ Card supprimée');
                    });
                }
            });
        }

        /**
         * Mise à jour du titre dans le header
         */
        function initTitleUpdate() {
            $(document).on('input', 'input[name*="[title]"]', function() {
                var $editor = $(this).closest('.slide-editor');
                var index = $editor.attr('data-card-index');
                var title = $(this).val() || 'Sans titre';
                $editor.find('.slide-title-header').html('Card ' + (parseInt(index) + 1) + ' : ' + title);
            });
        }

        /**
         * Upload d'image de fond de section
         */
        function initSectionBgUploader() {
            $(document).on('click', '.upload-section-bg-btn', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $wrapper = $button.closest('.image-upload-wrapper');
                var $input = $wrapper.find('.section-bg-url');
                var $removeBtn = $wrapper.find('.remove-section-bg-btn');
                var $previewContainer = $wrapper.siblings('.section-bg-preview');
                
                var mediaUploader = wp.media({
                    title: 'Choisir une image de fond',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                    library: { type: 'image' }
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    var imageUrl = attachment.url;
                    
                    // Préférer la version large si disponible
                    if (attachment.sizes && attachment.sizes.large) {
                        imageUrl = attachment.sizes.large.url;
                    }
                    
                    $input.val(imageUrl);
                    $removeBtn.show();
                    
                    // Mettre à jour ou créer l'aperçu
                    if ($previewContainer.length) {
                        $previewContainer.find('img').attr('src', imageUrl);
                    } else {
                        $wrapper.after('<div class="section-bg-preview" style="margin-top: 10px;"><img src="' + imageUrl + '" alt="Aperçu" style="max-width: 300px; max-height: 150px; border-radius: 8px; border: 2px solid #F08B18;"></div>');
                    }
                    
                    console.log('🖼️ Image de fond sélectionnée:', imageUrl);
                });
                
                mediaUploader.open();
            });
            
            // Suppression de l'image de fond
            $(document).on('click', '.remove-section-bg-btn', function(e) {
                e.preventDefault();
                
                var $wrapper = $(this).closest('.image-upload-wrapper');
                var $input = $wrapper.find('.section-bg-url');
                var $previewContainer = $wrapper.siblings('.section-bg-preview');
                
                $input.val('');
                $(this).hide();
                $previewContainer.remove();
                
                console.log('🗑️ Image de fond supprimée');
            });
        }

        /**
         * Slider opacité overlay
         */
        function initOverlaySlider() {
            $(document).on('input', '.overlay-range', function() {
                $(this).siblings('.overlay-value').text($(this).val());
            });
        }

        /**
         * Initialisation
         */
        function init() {
            initMediaUploader();
            initImageRemoval();
            initCardToggle();
            initSortable();
            initAddCard();
            initDeleteCard();
            initTitleUpdate();
            initSectionBgUploader();
            initOverlaySlider();
            
            console.log('✅ Admin Formations Cards - Prêt (' + cardCounter + ' cards)');
        }

        init();
    });

})(jQuery);
