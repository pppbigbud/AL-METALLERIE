jQuery(document).ready(function($) {
    // Initialisation
    var aicg = {
        init: function() {
            this.bindEvents();
            this.checkOllamaStatus();
        },
        
        bindEvents: function() {
            // Formulaire principal
            $('#aicg-generator-form').on('submit', this.handleGenerate);
            
            // Type de contenu changement
            $('#template_type').on('change', this.handleTypeChange);
            
            // Copier le contenu
            $(document).on('click', '#copy-content', this.handleCopy);
            
            // Regénérer
            $(document).on('click', '#regenerate-content', this.handleRegenerate);
            
            // Test de connexion Ollama
            $('#test-ollama').on('click', this.testOllama);
        },
        
        handleGenerate: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $btn = $('#generate-btn');
            var $spinner = $('.spinner');
            var $result = $('#result-container');
            
            // Validation
            if (!$('#template_type').val()) {
                alert('Veuillez sélectionner un type de contenu');
                return;
            }
            
            // État loading
            $btn.prop('disabled', true);
            $spinner.show();
            $result.hide();
            
            // Préparer les données
            var formData = $form.serializeArray();
            var data = {};
            
            $.each(formData, function(i, field) {
                if (field.value) {
                    data[field.name] = field.value;
                }
            });
            
            // Requête AJAX
            $.post(aicg_ajax.ajax_url, {
                action: 'aicg_generate_content',
                data: data,
                nonce: aicg_ajax.nonce
            })
            .done(function(response) {
                if (response.success) {
                    $('#generated-content').val(response.data.content);
                    $result.show();
                    
                    // Scroll vers le résultat
                    $('html, body').animate({
                        scrollTop: $result.offset().top - 50
                    }, 500);
                } else {
                    aicg.showError('Erreur: ' + response.data);
                }
            })
            .fail(function() {
                aicg.showError('Erreur de communication avec le serveur');
            })
            .always(function() {
                $btn.prop('disabled', false);
                $spinner.hide();
            });
        },
        
        handleTypeChange: function() {
            var type = $(this).val();
            var $container = $('#dynamic-fields');
            
            $container.empty();
            
            if (!type) return;
            
            var fields = aicg.getFieldsForType(type);
            
            $.each(fields, function(i, field) {
                var $field = aicg.createField(field);
                $container.append($field);
            });
        },
        
        getFieldsForType: function(type) {
            var fields = {
                'realisation': [
                    {type: 'text', name: 'type', placeholder: 'Type de projet (ex: portail, escalier)'},
                    {type: 'text', name: 'materials', placeholder: 'Matériaux utilisés'},
                    {type: 'text', name: 'client', placeholder: 'Type de client'},
                    {type: 'text', name: 'date', placeholder: 'Date de réalisation'}
                ],
                'city_page': [
                    {type: 'text', name: 'city', placeholder: 'Nom de la ville', required: true},
                    {type: 'text', name: 'department', placeholder: 'Département'},
                    {type: 'text', name: 'population', placeholder: 'Population'},
                    {type: 'text', name: 'specifics', placeholder: 'Particularités locales'},
                    {type: 'text', name: 'services', placeholder: 'Services adaptés'}
                ],
                'meta_description': [
                    {type: 'text', name: 'type', placeholder: 'Type de page'},
                    {type: 'text', name: 'subject', placeholder: 'Sujet principal'},
                    {type: 'text', name: 'location', placeholder: 'Localisation'}
                ],
                'content_improvement': [
                    {type: 'textarea', name: 'content', placeholder: 'Contenu à améliorer'},
                    {type: 'number', name: 'target_length', placeholder: 'Longueur cible à ajouter'},
                    {type: 'text', name: 'keywords', placeholder: 'Mots-clés (séparés par des virgules)'}
                ],
                'testimonial': [
                    {type: 'text', name: 'service', placeholder: 'Service concerné'},
                    {type: 'text', name: 'location', placeholder: 'Localisation'},
                    {type: 'select', name: 'client_type', options: {'particulier': 'Particulier', 'professionnel': 'Professionnel'}}
                ]
            };
            
            return fields[type] || [];
        },
        
        createField: function(field) {
            var $wrapper = $('<div></div>');
            
            if (field.type === 'textarea') {
                var $input = $('<textarea>')
                    .attr('name', field.name)
                    .attr('placeholder', field.placeholder)
                    .addClass('large-text')
                    .attr('rows', '5');
            } else if (field.type === 'select') {
                var $input = $('<select>')
                    .attr('name', field.name);
                
                $.each(field.options, function(value, label) {
                    $input.append($('<option>').val(value).text(label));
                });
            } else {
                var $input = $('<input>')
                    .attr('type', field.type)
                    .attr('name', field.name)
                    .attr('placeholder', field.placeholder)
                    .addClass('regular-text');
                
                if (field.required) {
                    $input.attr('required', 'required');
                }
            }
            
            $wrapper.append($input);
            return $wrapper;
        },
        
        handleCopy: function() {
            var $textarea = $('#generated-content');
            var $btn = $(this);
            
            $textarea.select();
            document.execCommand('copy');
            
            $btn.addClass('copied').text('Copié!');
            
            setTimeout(function() {
                $btn.removeClass('copied').text('Copier');
            }, 2000);
        },
        
        handleRegenerate: function() {
            $('#aicg-generator-form').submit();
        },
        
        checkOllamaStatus: function() {
            $.get(aicg_ajax.ajax_url, {
                action: 'aicg_test_ollama',
                nonce: aicg_ajax.nonce
            }, function(response) {
                if (response.success) {
                    if (!response.data.available) {
                        $('.aicg-wrap').prepend(
                            '<div class="notice notice-error is-dismissible">' +
                            '<p><strong>Attention:</strong> Ollama n\'est pas accessible. Veuillez vérifier l\'installation.</p>' +
                            '</div>'
                        );
                    }
                    
                    // Mettre à jour la liste des modèles
                    var $modelSelect = $('#model');
                    $modelSelect.empty();
                    
                    $.each(response.data.models, function(i, model) {
                        $modelSelect.append($('<option>').val(model).text(model));
                    });
                }
            });
        },
        
        testOllama: function() {
            var $btn = $(this);
            var originalText = $btn.text();
            
            $btn.prop('disabled', true).text('Test en cours...');
            
            $.get(aicg_ajax.ajax_url, {
                action: 'aicg_test_ollama',
                nonce: aicg_ajax.nonce
            }, function(response) {
                if (response.success && response.data.available) {
                    alert('Ollama est opérationnel! Modèles disponibles: ' + response.data.models.join(', '));
                } else {
                    alert('Ollama n\'est pas accessible. Vérifiez l\'installation.');
                }
            }).always(function() {
                $btn.prop('disabled', false).text(originalText);
            });
        },
        
        showError: function(message) {
            var $notice = $('<div class="notice notice-error is-dismissible"><p>' + message + '</p></div>');
            $('.wrap h1').after($notice);
            
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $notice.remove();
                });
            }, 5000);
        }
    };
    
    // Initialiser
    aicg.init();
    
    // Exposer pour les autres scripts
    window.aicg = aicg;
});
