jQuery(document).ready(function($) {
    // Bouton d'aperçu
    $('.cpg-preview-button').on('click', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var cityName = $('#city_name').val();
        var temperature = $('#groq_temperature').val();
        
        if (!cityName) {
            alert('Veuillez entrer un nom de ville');
            return;
        }
        
        $button.prop('disabled', true).html('<span class="spinner is-active"></span> Génération...');
        
        $.ajax({
            url: cpgGroq.ajaxUrl,
            type: 'POST',
            data: {
                action: 'cpg_preview_groq_content',
                nonce: cpgGroq.nonce,
                city_name: cityName,
                temperature: temperature
            },
            success: function(response) {
                if (response.success) {
                    showPreview(response.data);
                } else {
                    alert('Erreur: ' + response.data);
                }
            },
            error: function() {
                alert('Erreur de communication avec le serveur');
            },
            complete: function() {
                $button.prop('disabled', false).html('🔍 Aperçu IA');
            }
        });
    });
    
    function showPreview(sections) {
        var html = '<div class="cpg-preview-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100000; display: flex; align-items: center; justify-content: center;">';
        html += '<div class="cpg-preview-content" style="background: white; padding: 30px; max-width: 800px; max-height: 80vh; overflow-y: auto; border-radius: 8px;">';
        html += '<h2>🤖 Aperçu du contenu généré par Groq AI</h2>';
        
        // Introduction
        if (sections.intro) {
            html += '<div class="preview-section">';
            html += '<h3>Introduction</h3>';
            html += '<div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #0073aa;">' + sections.intro + '</div>';
            html += '</div>';
        }
        
        // Pourquoi nous choisir
        if (sections.why_us) {
            html += '<div class="preview-section">';
            html += '<h3>Pourquoi nous choisir</h3>';
            html += '<div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #0073aa;">' + sections.why_us.replace(/\n/g, '<br>') + '</div>';
            html += '</div>';
        }
        
        // FAQ
        if (sections.faq) {
            html += '<div class="preview-section">';
            html += '<h3>FAQ</h3>';
            html += '<div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #0073aa;">' + sections.faq.replace(/\n/g, '<br>') + '</div>';
            html += '</div>';
        }
        
        html += '<div style="margin-top: 20px; text-align: center;">';
        html += '<button type="button" class="button button-secondary cpg-close-preview">Fermer</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        
        $('body').append(html);
        
        // Fermer le modal
        $('.cpg-close-preview, .cpg-preview-modal').on('click', function(e) {
            if (e.target === this) {
                $('.cpg-preview-modal').remove();
            }
        });
    }
});
