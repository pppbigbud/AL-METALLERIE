/**
 * JavaScript pour l'interface d'administration du slideshow
 * Avec conversion automatique WebP et support multi-slides
 * 
 * @package ALMetallerie
 * @since 1.0.0
 * @updated 2.0.0 - Ajout conversion WebP automatique
 */

(function($) {
    'use strict';

    // Compteur pour les nouveaux slides
    let slideCounter = $('.slide-editor').length;

    $(document).ready(function() {
        console.log('🎞️ Admin Slideshow - Initialisation v2.0');

        /**
         * Upload d'image avec WordPress Media Uploader + Conversion WebP
         */
        function initMediaUploader() {
            $(document).on('click', '.upload-image-button', function(e) {
                e.preventDefault();

                const button = $(this);
                const slideEditor = button.closest('.slide-editor');
                const imagePreview = slideEditor.find('.image-preview');
                const imageInput = slideEditor.find('.image-url-input');

                // Créer le media uploader
                const mediaUploader = wp.media({
                    title: 'Choisir une image pour le slide',
                    button: {
                        text: 'Utiliser cette image'
                    },
                    multiple: false,
                    library: {
                        type: 'image'
                    }
                });

                // Quand une image est sélectionnée
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    const attachmentId = attachment.id;
                    
                    // Afficher un loader pendant la conversion
                    imagePreview.addClass('has-image is-converting');
                    imagePreview.find('img').remove();
                    imagePreview.prepend(
                        '<div class="conversion-loader">' +
                        '<span class="dashicons dashicons-update spinning"></span>' +
                        '<span>Conversion WebP en cours...</span>' +
                        '</div>'
                    );
                    
                    console.log('🔄 Conversion WebP en cours pour attachment #' + attachmentId);
                    
                    // Appeler l'AJAX pour convertir en WebP
                    $.ajax({
                        url: almetalSlideshow.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'convert_slideshow_image',
                            attachment_id: attachmentId,
                            nonce: almetalSlideshow.nonce
                        },
                        success: function(response) {
                            imagePreview.removeClass('is-converting');
                            imagePreview.find('.conversion-loader').remove();
                            
                            if (response.success) {
                                const webpUrl = response.data.url;
                                
                                // Mettre à jour l'aperçu avec l'image WebP
                                imagePreview.prepend('<img src="' + webpUrl + '" alt="Aperçu">');
                                
                                // Mettre à jour l'input caché
                                imageInput.val(webpUrl);
                                
                                // Afficher les stats d'optimisation
                                showOptimizationStats(slideEditor, response.data);
                                
                                console.log('✅ Image convertie en WebP:', webpUrl);
                                console.log('📊 Économie:', response.data.savings);
                            } else {
                                // Fallback : utiliser l'image originale
                                const imageUrl = attachment.sizes.full ? attachment.sizes.full.url : attachment.url;
                                imagePreview.prepend('<img src="' + imageUrl + '" alt="Aperçu">');
                                imageInput.val(imageUrl);
                                
                                console.warn('⚠️ Conversion WebP échouée, utilisation de l\'original:', response.data);
                                showConversionError(slideEditor, response.data);
                            }
                            
                            // Mettre à jour le bouton de suppression
                            updateRemoveButton(slideEditor);
                        },
                        error: function(xhr, status, error) {
                            imagePreview.removeClass('is-converting');
                            imagePreview.find('.conversion-loader').remove();
                            
                            // Fallback : utiliser l'image originale
                            const imageUrl = attachment.sizes.full ? attachment.sizes.full.url : attachment.url;
                            imagePreview.prepend('<img src="' + imageUrl + '" alt="Aperçu">');
                            imageInput.val(imageUrl);
                            
                            console.error('❌ Erreur AJAX:', error);
                            showConversionError(slideEditor, 'Erreur de connexion');
                            
                            updateRemoveButton(slideEditor);
                        }
                    });
                });

                // Ouvrir le media uploader
                mediaUploader.open();
            });
        }
        
        /**
         * Afficher les statistiques d'optimisation et SEO
         */
        function showOptimizationStats(slideEditor, data) {
            // Supprimer les anciens stats
            slideEditor.find('.optimization-stats, .seo-stats').remove();
            
            // Stats d'optimisation image
            const statsHtml = 
                '<div class="optimization-stats">' +
                '<span class="dashicons dashicons-yes-alt"></span> ' +
                '<strong>WebP optimisé</strong> - ' +
                data.dimensions + ' - ' +
                '<span class="size-original">' + data.original_size + '</span> → ' +
                '<span class="size-webp">' + data.webp_size + '</span> ' +
                '<span class="savings">(-' + data.savings + ')</span>' +
                '</div>';
            
            slideEditor.find('.form-help').first().after(statsHtml);
            
            // Stats SEO si disponibles
            if (data.seo) {
                const seoHtml = 
                    '<div class="seo-stats">' +
                    '<div class="seo-stats-header">' +
                    '<span class="dashicons dashicons-search"></span> ' +
                    '<strong>SEO Local optimisé</strong>' +
                    '</div>' +
                    '<div class="seo-stats-content">' +
                    '<div class="seo-item"><span class="seo-label">Alt Text:</span> <span class="seo-value">' + data.seo.alt_text + '</span></div>' +
                    '<div class="seo-item"><span class="seo-label">Titre:</span> <span class="seo-value">' + data.seo.title + '</span></div>' +
                    '<div class="seo-item"><span class="seo-label">Caption:</span> <span class="seo-value">' + data.seo.caption + '</span></div>' +
                    '<div class="seo-item seo-keywords"><span class="seo-label">Mots-clés:</span> <span class="seo-value">' + data.seo.keywords + '</span></div>' +
                    '</div>' +
                    '</div>';
                
                slideEditor.find('.optimization-stats').after(seoHtml);
                
                console.log('🔍 SEO appliqué:', data.seo);
            }
        }
        
        /**
         * Afficher une erreur de conversion
         */
        function showConversionError(slideEditor, message) {
            slideEditor.find('.optimization-stats').remove();
            
            const errorHtml = 
                '<div class="optimization-stats error">' +
                '<span class="dashicons dashicons-warning"></span> ' +
                'Conversion WebP non disponible: ' + message + ' (image originale utilisée)' +
                '</div>';
            
            slideEditor.find('.form-help').first().after(errorHtml);
        }

        /**
         * Suppression d'image
         */
        function initImageRemoval() {
            $(document).on('click', '.remove-image-button', function(e) {
                e.preventDefault();

                const button = $(this);
                const slideEditor = button.closest('.slide-editor');
                const imagePreview = slideEditor.find('.image-preview');
                const imageInput = slideEditor.find('.image-url-input');

                // Confirmer la suppression
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                    return;
                }

                // Supprimer l'image
                imagePreview.removeClass('has-image');
                imagePreview.find('img').remove();
                imageInput.val('');

                // Mettre à jour le bouton
                updateRemoveButton(slideEditor);

                console.log('🗑️ Image supprimée');
            });
        }

        /**
         * Mettre à jour le bouton de suppression
         */
        function updateRemoveButton(slideEditor) {
            const imagePreview = slideEditor.find('.image-preview');
            const removeButton = slideEditor.find('.remove-image-button');
            const uploadButton = slideEditor.find('.upload-image-button');

            if (imagePreview.hasClass('has-image')) {
                if (removeButton.length === 0) {
                    uploadButton.after(
                        '<button type="button" class="button button-link-delete remove-image-button">' +
                        '<span class="dashicons dashicons-no"></span> Supprimer' +
                        '</button>'
                    );
                }
                uploadButton.html('<span class="dashicons dashicons-upload"></span> Changer l\'image');
            } else {
                removeButton.remove();
                uploadButton.html('<span class="dashicons dashicons-upload"></span> Choisir une image');
            }
        }

        /**
         * Toggle actif/inactif
         */
        function initSlideToggle() {
            $('.slide-active-toggle').on('change', function() {
                const slideEditor = $(this).closest('.slide-editor');
                const toggleLabel = $(this).closest('.slide-toggle').find('.toggle-label');
                const isActive = $(this).is(':checked');

                if (isActive) {
                    slideEditor.removeClass('slide-inactive');
                    toggleLabel.text('Activé');
                } else {
                    slideEditor.addClass('slide-inactive');
                    toggleLabel.text('Désactivé');
                }

                console.log('🔄 Slide ' + (isActive ? 'activé' : 'désactivé'));
            });
        }

        /**
         * Drag & Drop pour réorganiser les slides
         */
        function initSortable() {
            $('#slides-container').sortable({
                handle: '.slide-drag-handle',
                placeholder: 'slide-placeholder',
                cursor: 'move',
                opacity: 0.8,
                tolerance: 'pointer',
                start: function(e, ui) {
                    ui.placeholder.height(ui.item.height());
                    console.log('🎯 Début du drag');
                },
                stop: function(e, ui) {
                    console.log('✅ Fin du drag');
                    updateSlideOrder();
                }
            });

            // Style du placeholder
            $('<style>')
                .prop('type', 'text/css')
                .html('.slide-placeholder { background: #f0f0f0; border: 2px dashed #F08B18; border-radius: 8px; margin-bottom: 20px; }')
                .appendTo('head');
        }

        /**
         * Mettre à jour l'ordre des slides
         */
        function updateSlideOrder() {
            $('#slides-container .slide-editor').each(function(index) {
                $(this).find('.slide-order-input').val(index);
                $(this).find('.slide-title-header').text('Slide ' + (index + 1));
                console.log('📊 Slide ' + (index + 1) + ' - Ordre mis à jour');
            });
        }

        /**
         * Validation du formulaire
         */
        function initFormValidation() {
            $('.almetal-slideshow-form').on('submit', function(e) {
                let isValid = true;
                const errors = [];

                // Vérifier que chaque slide actif a une image
                $('.slide-editor').each(function(index) {
                    const slideEditor = $(this);
                    const isActive = slideEditor.find('.slide-active-toggle').is(':checked');
                    const imageUrl = slideEditor.find('.image-url-input').val();
                    const title = slideEditor.find('input[name*="[title]"]').val();

                    if (isActive) {
                        if (!imageUrl) {
                            isValid = false;
                            errors.push('Slide ' + (index + 1) + ' : Image manquante');
                            slideEditor.find('.image-preview').css('border-color', '#dc3232');
                        }

                        if (!title) {
                            isValid = false;
                            errors.push('Slide ' + (index + 1) + ' : Titre manquant');
                            slideEditor.find('input[name*="[title]"]').css('border-color', '#dc3232');
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('⚠️ Erreurs de validation :\n\n' + errors.join('\n'));
                    return false;
                }

                // Animation de sauvegarde
                $('.slide-editor').addClass('is-saving');
                console.log('💾 Sauvegarde en cours...');
            });

            // Retirer les bordures rouges lors de la modification
            $('input, textarea').on('input', function() {
                $(this).css('border-color', '');
            });
        }

        /**
         * Réinitialiser aux valeurs par défaut
         */
        function initResetButton() {
            $('#reset-slides').on('click', function(e) {
                e.preventDefault();

                if (!confirm('⚠️ Êtes-vous sûr de vouloir réinitialiser le slideshow aux valeurs par défaut ?\n\nToutes vos modifications seront perdues.')) {
                    return;
                }

                // Afficher un loader
                $(this).prop('disabled', true).html('<span class="dashicons dashicons-update"></span> Réinitialisation...');

                // Recharger la page avec un paramètre pour déclencher la réinitialisation
                window.location.href = window.location.href.split('?')[0] + '?page=almetal-slideshow&reset=true';
            });
        }

        /**
         * Compteur de caractères pour les champs
         */
        function initCharacterCounter() {
            $('input[type="text"], textarea').each(function() {
                const maxLength = $(this).attr('maxlength');
                if (maxLength) {
                    const counter = $('<span class="character-counter"></span>');
                    $(this).after(counter);
                    updateCounter($(this), counter, maxLength);

                    $(this).on('input', function() {
                        updateCounter($(this), counter, maxLength);
                    });
                }
            });
        }

        function updateCounter(input, counter, maxLength) {
            const currentLength = input.val().length;
            counter.text(currentLength + ' / ' + maxLength);

            if (currentLength > maxLength * 0.9) {
                counter.css('color', '#dc3232');
            } else {
                counter.css('color', '#666');
            }
        }

        /**
         * Ajouter un nouveau slide
         */
        function initAddSlide() {
            $('#add-new-slide').on('click', function(e) {
                e.preventDefault();
                
                const maxSlides = almetalSlideshow.maxSlides || 10;
                const currentSlides = $('.slide-editor').length;
                
                if (currentSlides >= maxSlides) {
                    alert('⚠️ Nombre maximum de slides atteint (' + maxSlides + ')');
                    return;
                }
                
                slideCounter = currentSlides;
                const newIndex = slideCounter;
                
                // Template HTML pour un nouveau slide
                const slideHtml = `
                <div class="slide-editor" data-slide-index="${newIndex}">
                    <div class="slide-header">
                        <div class="slide-drag-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        <h2 class="slide-title-header">
                            Slide ${newIndex + 1} <span class="new-badge">Nouveau</span>
                        </h2>
                        <div class="slide-actions">
                            <button type="button" class="button button-link-delete delete-slide-button">
                                <span class="dashicons dashicons-trash"></span>
                                Supprimer
                            </button>
                        </div>
                        <div class="slide-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       name="slides[${newIndex}][active]" 
                                       value="1" 
                                       checked
                                       class="slide-active-toggle">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Activé</span>
                        </div>
                    </div>
                    
                    <div class="slide-content">
                        <input type="hidden" name="slides[${newIndex}][order]" value="${newIndex}" class="slide-order-input">
                        
                        <!-- Image -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-format-image"></span>
                                Image de fond
                                <span class="required">*</span>
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
                                <input type="hidden" 
                                       name="slides[${newIndex}][image]" 
                                       value="" 
                                       class="image-url-input"
                                       required>
                            </div>
                            <p class="form-help">
                                <span class="dashicons dashicons-info"></span>
                                L'image sera automatiquement convertie en <strong>WebP</strong> et redimensionnée à <strong>1920x800px</strong>
                            </p>
                        </div>
                        
                        <!-- Titre -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-text"></span>
                                Titre principal
                                <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="slides[${newIndex}][title]" 
                                   value="" 
                                   class="form-control"
                                   placeholder="Ex: Bienvenue chez AL Métallerie"
                                   required>
                        </div>
                        
                        <!-- Sous-titre -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="dashicons dashicons-editor-alignleft"></span>
                                Sous-titre / Description
                            </label>
                            <textarea name="slides[${newIndex}][subtitle]" 
                                      class="form-control"
                                      rows="2"
                                      placeholder="Ex: Expert en métallerie à Clermont-Ferrand"></textarea>
                        </div>
                        
                        <!-- Bouton CTA -->
                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label class="form-label">
                                    <span class="dashicons dashicons-admin-links"></span>
                                    Texte du bouton
                                </label>
                                <input type="text" 
                                       name="slides[${newIndex}][cta_text]" 
                                       value="" 
                                       class="form-control"
                                       placeholder="Ex: Demander un devis">
                            </div>
                            
                            <div class="form-group form-group-half">
                                <label class="form-label">
                                    <span class="dashicons dashicons-admin-site"></span>
                                    URL du bouton
                                </label>
                                <input type="text" 
                                       name="slides[${newIndex}][cta_url]" 
                                       value="" 
                                       class="form-control"
                                       placeholder="Ex: #contact ou /contact">
                                <p class="form-help">Utilisez # pour les ancres (ex: #contact) ou une URL complète</p>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                // Ajouter le nouveau slide
                $('#slides-container').append(slideHtml);
                
                // Scroll vers le nouveau slide
                $('html, body').animate({
                    scrollTop: $('.slide-editor').last().offset().top - 100
                }, 500);
                
                // Mettre à jour le compteur
                slideCounter++;
                
                console.log('➕ Nouveau slide ajouté (#' + newIndex + ')');
            });
        }
        
        /**
         * Supprimer un slide
         */
        function initDeleteSlide() {
            $(document).on('click', '.delete-slide-button', function(e) {
                e.preventDefault();
                
                const slideEditor = $(this).closest('.slide-editor');
                const slideIndex = slideEditor.data('slide-index');
                
                if (!confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce slide ?\n\nCette action est irréversible.')) {
                    return;
                }
                
                // Animation de suppression
                slideEditor.fadeOut(300, function() {
                    $(this).remove();
                    updateSlideOrder();
                    console.log('🗑️ Slide #' + slideIndex + ' supprimé');
                });
            });
        }
        
        /**
         * Toggle Mode Commercial/Promotionnel
         */
        function initPromoToggle() {
            $(document).on('change', '.slide-promo-toggle', function() {
                const slideEditor = $(this).closest('.slide-editor');
                const promoSection = slideEditor.find('.promo-section');
                const titleHeader = slideEditor.find('.slide-title-header');
                const isPromo = $(this).is(':checked');
                
                if (isPromo) {
                    // Activer le mode promo
                    slideEditor.addClass('slide-promo');
                    promoSection.removeClass('promo-hidden').addClass('promo-visible');
                    
                    // Ajouter le badge si pas déjà présent
                    if (titleHeader.find('.slide-type-badge').length === 0) {
                        titleHeader.append('<span class="slide-type-badge promo-badge">🛒 Commercial</span>');
                    }
                    
                    console.log('🛒 Mode commercial activé');
                } else {
                    // Désactiver le mode promo
                    slideEditor.removeClass('slide-promo');
                    promoSection.removeClass('promo-visible').addClass('promo-hidden');
                    titleHeader.find('.slide-type-badge').remove();
                    
                    console.log('📄 Mode standard activé');
                }
            });
        }

        /**
         * Gestion des messages de succès
         */
        function initSuccessMessage() {
            // Auto-dismiss des notices après 5 secondes
            setTimeout(function() {
                $('.notice.is-dismissible').fadeOut();
            }, 5000);
        }

        /**
         * Raccourcis clavier
         */
        function initKeyboardShortcuts() {
            $(document).on('keydown', function(e) {
                // Ctrl/Cmd + S pour sauvegarder
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    $('.almetal-slideshow-form').submit();
                    console.log('⌨️ Raccourci clavier : Sauvegarde');
                }
            });
        }

        /**
         * Sélecteur d'icônes
         */
        function initIconSelector() {
            // Toggle dropdown
            $(document).on('click', '.icon-selector-preview, .icon-selector-toggle', function(e) {
                e.stopPropagation();
                var $selector = $(this).closest('.almetal-icon-selector');
                
                // Fermer les autres
                $('.almetal-icon-selector').not($selector).removeClass('open');
                
                // Toggle celui-ci
                $selector.toggleClass('open');
            });
            
            // Sélection d'une icône
            $(document).on('click', '.icon-option', function() {
                var $option = $(this);
                var $selector = $option.closest('.almetal-icon-selector');
                var value = $option.data('value');
                var name = $option.data('name') || 'Aucune icône';
                var $svg = $option.find('.icon-svg').html();
                
                // Mettre à jour la valeur
                $selector.find('.icon-selector-value').val(value);
                
                // Mettre à jour l'aperçu
                var $preview = $selector.find('.icon-selector-preview');
                if (value) {
                    $preview.find('.icon-preview').removeClass('no-icon').html($svg);
                    $preview.find('.icon-name').text(name);
                } else {
                    $preview.find('.icon-preview').addClass('no-icon').html('—');
                    $preview.find('.icon-name').text('Aucune icône');
                }
                
                // Marquer comme sélectionné
                $selector.find('.icon-option').removeClass('selected');
                $option.addClass('selected');
                
                // Fermer le dropdown
                $selector.removeClass('open');
                
                console.log('🎨 Icône sélectionnée:', value || 'aucune');
            });
            
            // Recherche d'icônes
            $(document).on('input', '.icon-search-input', function() {
                var query = $(this).val().toLowerCase();
                var $options = $(this).closest('.icon-selector-dropdown').find('.icon-option');
                
                $options.each(function() {
                    var $opt = $(this);
                    var name = ($opt.data('name') || '').toLowerCase();
                    var value = ($opt.data('value') || '').toLowerCase();
                    
                    if (name.indexOf(query) > -1 || value.indexOf(query) > -1 || query === '') {
                        $opt.removeClass('hidden');
                    } else {
                        $opt.addClass('hidden');
                    }
                });
                
                // Cacher les groupes vides
                $(this).closest('.icon-selector-dropdown').find('.icon-group').each(function() {
                    var $group = $(this);
                    var visibleOptions = $group.find('.icon-option:not(.hidden)').length;
                    $group.toggle(visibleOptions > 0);
                });
            });
            
            // Fermer au clic extérieur
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.almetal-icon-selector').length) {
                    $('.almetal-icon-selector').removeClass('open');
                }
            });
            
            console.log('🎨 Sélecteur d\'icônes initialisé');
        }

        /**
         * Initialisation
         */
        function init() {
            // Initialiser le compteur
            slideCounter = $('.slide-editor').length;
            
            initMediaUploader();
            initImageRemoval();
            initSlideToggle();
            initPromoToggle();
            initSortable();
            initFormValidation();
            initResetButton();
            initCharacterCounter();
            initAddSlide();
            initDeleteSlide();
            initSuccessMessage();
            initKeyboardShortcuts();
            initIconSelector();

            console.log('✅ Admin Slideshow v2.0 - Prêt (' + slideCounter + ' slides)');
        }

        // Démarrer
        init();
    });

})(jQuery);
