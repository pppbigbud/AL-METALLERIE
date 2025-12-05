/**
 * Script Admin - Publication Sociale
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        /**
         * Gestion des images - Upload
         */
        $('#almetal-add-images').on('click', function(e) {
            e.preventDefault();
            
            var postId = $('#post_ID').val();
            
            // Créer un nouvel uploader à chaque fois pour éviter les problèmes de cache
            var mediaUploader = wp.media({
                title: 'Ajouter des images',
                button: {
                    text: 'Ajouter à la réalisation'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });
            
            // Quand des images sont sélectionnées
            mediaUploader.on('select', function() {
                var selection = mediaUploader.state().get('selection');
                
                // Parcourir chaque image sélectionnée
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    
                    console.log('Image sélectionnée:', attachment.id, attachment.filename);
                    
                    // Vérifier que l'image n'est pas déjà affichée
                    if ($('.almetal-image-item[data-id="' + attachment.id + '"]').length > 0) {
                        console.log('Image déjà présente, ignorée:', attachment.id);
                        return; // Image déjà présente, passer à la suivante
                    }
                    
                    // Attacher l'image au post
                    $.ajax({
                        url: almetalSocial.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'attach_image_to_post',
                            nonce: almetalSocial.nonce_generate,
                            post_id: postId,
                            attachment_id: attachment.id
                        },
                        success: function(response) {
                            console.log('Réponse AJAX pour image', attachment.id, ':', response);
                            
                            // Récupérer l'URL de la miniature depuis la réponse
                            var thumbnailUrl = '';
                            var actualAttachmentId = attachment.id;
                            
                            if (response.success && response.data) {
                                if (response.data.thumbnail_url) {
                                    thumbnailUrl = response.data.thumbnail_url;
                                }
                                if (response.data.attachment_id) {
                                    actualAttachmentId = response.data.attachment_id;
                                }
                            }
                            
                            // Fallback si pas d'URL dans la réponse
                            if (!thumbnailUrl) {
                                if (attachment.sizes && attachment.sizes.thumbnail) {
                                    thumbnailUrl = attachment.sizes.thumbnail.url;
                                } else {
                                    thumbnailUrl = attachment.url;
                                }
                            }
                            
                            console.log('URL miniature finale:', thumbnailUrl);
                            
                            // Ajouter l'image à la preview
                            var html = '<div class="almetal-image-item" data-id="' + actualAttachmentId + '" style="position: relative;">';
                            html += '<img src="' + thumbnailUrl + '" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">';
                            html += '<button type="button" class="almetal-remove-image" data-id="' + actualAttachmentId + '" style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>';
                            html += '<button type="button" class="almetal-set-featured" data-id="' + actualAttachmentId + '" style="position: absolute; bottom: 5px; left: 5px; background: #F08B18; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer; font-size: 11px;">⭐ Image à la une</button>';
                            html += '</div>';
                            
                            $('#almetal-images-preview').append(html);
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur AJAX pour image', attachment.id, ':', error, xhr.responseText);
                        }
                    });
                });
            });
            
            // Ouvrir l'uploader
            mediaUploader.open();
        });
        
        /**
         * Gestion des images - Suppression
         */
        $(document).on('click', '.almetal-remove-image', function(e) {
            e.preventDefault();
            
            if (!confirm('Voulez-vous vraiment supprimer cette image ?')) {
                return;
            }
            
            var $button = $(this);
            var attachmentId = $button.data('id');
            var $item = $button.closest('.almetal-image-item');
            
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                data: {
                    action: 'detach_image_from_post',
                    nonce: almetalSocial.nonce_generate,
                    attachment_id: attachmentId
                },
                success: function() {
                    $item.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });
        });
        
        /**
         * Gestion des images - Définir comme image à la une
         */
        $(document).on('click', '.almetal-set-featured', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var attachmentId = $button.data('id');
            var postId = $('#post_ID').val();
            
            $button.prop('disabled', true).text('⏳ En cours...');
            
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_featured_image',
                    nonce: almetalSocial.nonce_generate,
                    post_id: postId,
                    attachment_id: attachmentId
                },
                success: function(response) {
                    if (response.success) {
                        // Recharger la page pour mettre à jour l'affichage
                        location.reload();
                    } else {
                        alert('❌ Erreur : ' + response.data);
                        $button.prop('disabled', false).text('⭐ Image à la une');
                    }
                },
                error: function() {
                    alert('❌ Erreur de connexion');
                    $button.prop('disabled', false).text('⭐ Image à la une');
                }
            });
        });
        
        /**
         * Rafraîchir les informations détectées
         */
        $('#refresh-detected-info').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $content = $('#detected-info-content');
            var postId = $('#post_ID').val();
            
            // Désactiver le bouton
            $button.prop('disabled', true).text('🔄 Chargement...');
            
            // Debug: vérifier que les éléments existent
            console.log('🔍 Debug - Éléments trouvés:');
            console.log('  - #almetal_client_type:', $('#almetal_client_type').length, 'val:', $('#almetal_client_type').val());
            console.log('  - #almetal_client_nom:', $('#almetal_client_nom').length, 'val:', $('#almetal_client_nom').val());
            console.log('  - #almetal_matiere:', $('#almetal_matiere').length, 'val:', $('#almetal_matiere').val());
            console.log('  - #almetal_peinture:', $('#almetal_peinture').length, 'val:', $('#almetal_peinture').val());
            console.log('  - #almetal_pose:', $('#almetal_pose').length, 'checked:', $('#almetal_pose').is(':checked'));
            
            // Récupérer les valeurs des champs directement depuis le formulaire (sans sauvegarde préalable)
            // Utiliser document.getElementById pour être sûr de trouver les éléments
            var clientTypeEl = document.getElementById('almetal_client_type');
            var clientNomEl = document.getElementById('almetal_client_nom');
            var clientUrlEl = document.getElementById('almetal_client_url');
            var matiereEl = document.getElementById('almetal_matiere');
            var peintureEl = document.getElementById('almetal_peinture');
            var poseEl = document.getElementById('almetal_pose');
            var lieuEl = document.getElementById('almetal_lieu');
            var dateEl = document.getElementById('almetal_date_realisation');
            var dureeEl = document.getElementById('almetal_duree');
            
            var fields = {
                // Anciens champs
                lieu: lieuEl ? lieuEl.value : '',
                date: dateEl ? dateEl.value : '',
                duree: dureeEl ? dureeEl.value : '',
                // Nouveaux champs
                client_type: clientTypeEl ? clientTypeEl.value : '',
                client_nom: clientNomEl ? clientNomEl.value : '',
                client_url: clientUrlEl ? clientUrlEl.value : '',
                matiere: matiereEl ? matiereEl.value : '',
                peinture: peintureEl ? peintureEl.value : '',
                pose: poseEl && poseEl.checked ? '1' : '0'
            };
            
            console.log('🔍 Champs détectés:', fields);
            console.log('🆔 Post ID:', postId);
            
            // Appel AJAX
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                data: {
                    action: 'refresh_detected_info',
                    nonce: almetalSocial.nonce_refresh,
                    post_id: postId,
                    fields: fields
                },
                success: function(response) {
                    console.log('✅ Réponse AJAX:', response);
                    
                    if (response.success) {
                        // Mettre à jour le contenu
                        $content.html(response.data.html);
                        
                        // Animation de succès
                        $content.css('background', '#d4edda');
                        setTimeout(function() {
                            $content.css('background', '');
                        }, 1000);
                        
                        console.log('✅ Informations rafraîchies avec succès');
                    } else {
                        console.error('❌ Erreur:', response.data);
                        alert('❌ Erreur : ' + response.data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Erreur AJAX:', {xhr: xhr, status: status, error: error});
                    alert('❌ Erreur de connexion. Veuillez réessayer.');
                },
                complete: function() {
                    $button.prop('disabled', false).text('🔄 Rafraîchir');
                }
            });
        });
        
        /**
         * Générer le texte SEO (réseaux sociaux)
         */
        $('#generate-seo-text').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $spinner = $button.next('.spinner');
            var $status = $('#seo-generation-status');
            var postId = $('#post_ID').val();
            
            // Désactiver le bouton
            $button.prop('disabled', true);
            $spinner.addClass('is-active');
            $status.html('');
            
            // Appel AJAX
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                data: {
                    action: 'generate_seo_text',
                    nonce: almetalSocial.nonce_generate,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success) {
                        // Remplir les champs avec les textes générés
                        $('#almetal_facebook_text').val(response.data.facebook);
                        $('#almetal_instagram_text').val(response.data.instagram);
                        $('#almetal_linkedin_text').val(response.data.linkedin);
                        
                        // Mettre à jour l'extrait
                        if (response.data.excerpt) {
                            // Mettre à jour notre champ personnalisé
                            $('#almetal_excerpt').val(response.data.excerpt);
                            
                            // Animation de succès
                            $('#almetal_excerpt').css('background', '#d4edda');
                            setTimeout(function() {
                                $('#almetal_excerpt').css('background', '');
                            }, 2000);
                            
                            // Essayer aussi avec l'API Gutenberg
                            if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch('core/editor')) {
                                wp.data.dispatch('core/editor').editPost({ excerpt: response.data.excerpt });
                            }
                            
                            console.log('✅ Extrait généré:', response.data.excerpt);
                        }
                        
                        // Afficher le message de succès
                        $status.html('<div class="seo-success">✅ Textes générés avec succès ! Le sous-titre a été ajouté sous le titre. Vous pouvez modifier les textes avant de publier.</div>');
                        
                        // Scroll vers les textes réseaux sociaux
                        $('html, body').animate({
                            scrollTop: $('#almetal_facebook_text').offset().top - 100
                        }, 500);
                    } else {
                        $status.html('<div class="seo-error">❌ Erreur : ' + response.data + '</div>');
                    }
                },
                error: function() {
                    $status.html('<div class="seo-error">❌ Erreur de connexion. Veuillez réessayer.</div>');
                },
                complete: function() {
                    $button.prop('disabled', false);
                    $spinner.removeClass('is-active');
                }
            });
        });
        
        /**
         * Générer la description SEO longue (contenu de la page)
         */
        $('#generate-seo-description').on('click', function(e) {
            e.preventDefault();
            
            console.log('🤖 Bouton Générer description SEO cliqué');
            
            var $button = $(this);
            var $spinner = $('#seo-desc-spinner');
            var $status = $('#seo-description-status');
            var $textarea = $('#almetal_seo_description');
            var postId = $('#post_ID').val();
            
            console.log('📝 Post ID:', postId);
            console.log('🔑 Nonce disponible:', almetalSocial.nonce_generate_desc ? 'Oui' : 'Non');
            
            // Confirmation si le champ n'est pas vide
            if ($textarea.val().trim() !== '') {
                if (!confirm('⚠️ Une description existe déjà. Voulez-vous la remplacer ?')) {
                    return;
                }
            }
            
            // Désactiver le bouton
            $button.prop('disabled', true).text('🤖 Génération en cours...');
            $spinner.addClass('is-active');
            $status.html('<div class="seo-info">⏳ Génération de la description SEO en cours... Cela peut prendre quelques secondes.</div>');
            
            // Appel AJAX
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                timeout: 90000, // 90 secondes pour l'IA
                data: {
                    action: 'generate_seo_description',
                    nonce: almetalSocial.nonce_generate_desc,
                    post_id: postId
                },
                success: function(response) {
                    console.log('📨 Réponse AJAX reçue:', response);
                    if (response.success) {
                        // Remplir le textarea avec la description générée
                        $textarea.val(response.data.description);
                        
                        // Afficher le message de succès
                        $status.html('<div class="seo-success">✅ Description SEO générée avec succès ! Vous pouvez la modifier avant de publier.</div>');
                        
                        // Animation de succès sur le textarea
                        $textarea.css('background', '#d4edda');
                        setTimeout(function() {
                            $textarea.css('background', '');
                        }, 2000);
                        
                        // Scroll vers le textarea
                        $('html, body').animate({
                            scrollTop: $textarea.offset().top - 100
                        }, 500);
                    } else {
                        console.error('❌ Erreur serveur:', response.data);
                        $status.html('<div class="seo-error">❌ Erreur : ' + response.data + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Erreur AJAX:', {xhr: xhr, status: status, error: error});
                    console.error('❌ Réponse brute:', xhr.responseText);
                    $status.html('<div class="seo-error">❌ Erreur de connexion ou timeout. Veuillez réessayer.</div>');
                },
                complete: function() {
                    $button.prop('disabled', false).text('🤖 Générer la description SEO');
                    $spinner.removeClass('is-active');
                }
            });
        });
        
        /**
         * Copier le texte SEO dans le contenu Gutenberg
         */
        $('#copy-to-description').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var seoText = $('#almetal_generated_seo_text').val();
            
            // Vérifier qu'il y a du texte à copier
            if (!seoText || seoText.trim() === '') {
                alert('⚠️ Veuillez d\'abord générer le texte SEO avant de le copier dans le contenu.');
                return;
            }
            
            // Désactiver le bouton temporairement
            $button.prop('disabled', true).text('📝 Copie en cours...');
            
            // Copier dans l'éditeur Gutenberg
            setTimeout(function() {
                var copied = false;
                
                // Essayer Gutenberg (éditeur de blocs)
                if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/editor')) {
                    // Créer un bloc paragraphe avec le texte
                    var blocks = wp.blocks.parse('<!-- wp:paragraph -->\n<p>' + seoText + '</p>\n<!-- /wp:paragraph -->');
                    
                    // Remplacer tout le contenu par le nouveau bloc
                    wp.data.dispatch('core/block-editor').resetBlocks(blocks);
                    copied = true;
                }
                // Essayer TinyMCE (éditeur classique)
                else if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                    tinymce.get('content').setContent(seoText);
                    copied = true;
                }
                // Essayer l'éditeur texte
                else if ($('#content').length) {
                    $('#content').val(seoText);
                    copied = true;
                }
                
                if (copied) {
                    // Copier aussi dans l'extrait (version raccourcie à 150 caractères)
                    var excerptText = seoText.substring(0, 150);
                    if (seoText.length > 150) {
                        excerptText += '...';
                    }
                    
                    // Essayer de mettre à jour l'extrait avec l'API Gutenberg
                    if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/editor')) {
                        wp.data.dispatch('core/editor').editPost({ excerpt: excerptText });
                    }
                    // Fallback pour l'éditeur classique
                    else {
                        var $excerpt = $('#excerpt');
                        if ($excerpt.length) {
                            $excerpt.val(excerptText);
                        }
                    }
                    
                    // Message de succès
                    $button.text('✅ Copié !').css('background', '#46b450');
                    
                    // Scroll vers l'éditeur
                    $('html, body').animate({
                        scrollTop: $('.editor-styles-wrapper').offset().top - 100
                    }, 500);
                    
                    // Réinitialiser le bouton après 2 secondes
                    setTimeout(function() {
                        $button.prop('disabled', false).text('📝 Copier dans le contenu').css('background', '');
                    }, 2000);
                } else {
                    alert('❌ Impossible de copier dans l\'éditeur. Veuillez copier manuellement le texte.');
                    $button.prop('disabled', false).text('📝 Copier dans le contenu');
                }
            }, 100);
        });
        
        /**
         * Tester les connexions aux réseaux sociaux
         */
        $('#test-social-connection').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $status = $('#social-connection-status');
            
            $button.prop('disabled', true);
            $status.html('<p>🔄 Test en cours...</p>');
            
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                data: {
                    action: 'test_social_connection',
                    nonce: almetalSocial.nonce_test
                },
                success: function(response) {
                    if (response.success) {
                        var html = '<div style="background: #f0f0f1; padding: 10px; border-radius: 4px;">';
                        
                        // Facebook
                        html += '<p><strong>Facebook:</strong> ';
                        if (response.data.facebook.status === 'connected') {
                            html += '✅ Connecté';
                        } else {
                            html += '❌ ' + response.data.facebook.message;
                        }
                        html += '</p>';
                        
                        // Instagram
                        html += '<p><strong>Instagram:</strong> ';
                        if (response.data.instagram.status === 'connected') {
                            html += '✅ Connecté';
                        } else {
                            html += '❌ ' + response.data.instagram.message;
                        }
                        html += '</p>';
                        
                        // LinkedIn
                        html += '<p><strong>LinkedIn:</strong> ';
                        if (response.data.linkedin.status === 'connected') {
                            html += '✅ Connecté';
                        } else {
                            html += '❌ ' + response.data.linkedin.message;
                        }
                        html += '</p>';
                        
                        html += '<p style="margin-top: 10px;"><small>💡 Configurez les API dans <a href="' + almetalSocial.settings_url + '">Réglages → Publication Sociale</a></small></p>';
                        html += '</div>';
                        
                        $status.html(html);
                    }
                },
                error: function() {
                    $status.html('<p style="color: red;">❌ Erreur de connexion</p>');
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        });
        
        /**
         * Republier sur les réseaux sociaux
         */
        $('#republish-to-social').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm('Voulez-vous vraiment republier cette réalisation sur les réseaux sociaux sélectionnés ?')) {
                return;
            }
            
            var $button = $(this);
            var postId = $('#post_ID').val();
            
            $button.prop('disabled', true).text('🔄 Publication en cours...');
            
            $.ajax({
                url: almetalSocial.ajax_url,
                type: 'POST',
                data: {
                    action: 'republish_to_social',
                    nonce: almetalSocial.nonce_generate,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success) {
                        alert('✅ Publication réussie !');
                    } else {
                        alert('❌ Erreur : ' + response.data);
                    }
                },
                error: function() {
                    alert('❌ Erreur de connexion');
                },
                complete: function() {
                    $button.prop('disabled', false).text('🔄 Republier maintenant');
                }
            });
        });
        
        /**
         * Compteur de caractères pour les textes
         */
        function updateCharCount($textarea, maxChars, label) {
            var $counter = $textarea.next('.char-counter');
            if (!$counter.length) {
                $counter = $('<p class="char-counter" style="margin: 5px 0; color: #666; font-size: 12px;"></p>');
                $textarea.after($counter);
            }
            
            var count = $textarea.val().length;
            var color = count > maxChars ? 'red' : '#666';
            $counter.html(label + ' : <span style="color: ' + color + ';">' + count + ' / ' + maxChars + '</span>');
        }
        
        // Ajouter les compteurs
        $('#almetal_facebook_text').on('input', function() {
            updateCharCount($(this), 63206, 'Facebook');
        }).trigger('input');
        
        $('#almetal_instagram_text').on('input', function() {
            updateCharCount($(this), 2200, 'Instagram');
        }).trigger('input');
        
        $('#almetal_linkedin_text').on('input', function() {
            updateCharCount($(this), 3000, 'LinkedIn');
        }).trigger('input');
        
    });
    
})(jQuery);
