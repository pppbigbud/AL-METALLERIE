/**
 * City Pages Generator - Public JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initFAQ();
        initContactForm();
        initSmoothScroll();
    });

    /**
     * FAQ Accordion
     */
    function initFAQ() {
        $('.cpg-faq-item h3').on('click', function() {
            var $item = $(this).closest('.cpg-faq-item');
            var $answer = $item.find('p');
            
            // Fermer les autres
            $('.cpg-faq-item').not($item).removeClass('active').find('p').slideUp(200);
            
            // Toggle celui-ci
            $item.toggleClass('active');
            $answer.slideToggle(200);
        });
    }

    /**
     * Contact Form
     */
    function initContactForm() {
        // Afficher les messages de succès/erreur
        var urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('cpg_success')) {
            showMessage('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
        }
        
        if (urlParams.has('cpg_error')) {
            var error = urlParams.get('cpg_error');
            var messages = {
                'required': 'Veuillez remplir tous les champs obligatoires.',
                'email': 'Veuillez entrer une adresse email valide.',
                'send': 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer.'
            };
            showMessage('error', messages[error] || 'Une erreur est survenue.');
        }
        
        // Validation côté client
        $('.cpg-contact-form').on('submit', function(e) {
            var $form = $(this);
            var valid = true;
            
            $form.find('[required]').each(function() {
                if (!$(this).val().trim()) {
                    valid = false;
                    $(this).addClass('cpg-input-error');
                } else {
                    $(this).removeClass('cpg-input-error');
                }
            });
            
            // Validation email
            var $email = $form.find('input[type="email"]');
            if ($email.length && $email.val()) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test($email.val())) {
                    valid = false;
                    $email.addClass('cpg-input-error');
                }
            }
            
            if (!valid) {
                e.preventDefault();
                showMessage('error', 'Veuillez corriger les erreurs dans le formulaire.');
            }
        });
    }

    /**
     * Afficher un message
     */
    function showMessage(type, text) {
        var $message = $('<div class="cpg-form-message cpg-' + type + '">' + text + '</div>');
        
        // Insérer avant le formulaire
        var $form = $('.cpg-contact-form');
        if ($form.length) {
            $form.before($message);
            
            // Scroll vers le message
            $('html, body').animate({
                scrollTop: $message.offset().top - 100
            }, 500);
            
            // Supprimer après 10 secondes
            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 10000);
        }
        
        // Nettoyer l'URL
        if (window.history.replaceState) {
            var url = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, url);
        }
    }

    /**
     * Smooth Scroll
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        });
    }

    // Style pour les erreurs de formulaire
    $('<style>.cpg-input-error { border-color: #e74c3c !important; } .cpg-form-message { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; } .cpg-form-message.cpg-success { background: rgba(39, 174, 96, 0.15); color: #27ae60; border: 1px solid #27ae60; } .cpg-form-message.cpg-error { background: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid #e74c3c; }</style>').appendTo('head');

})(jQuery);
