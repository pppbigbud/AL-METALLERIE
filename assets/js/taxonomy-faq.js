/**
 * JavaScript pour l'interactivité de la FAQ dans les pages catégories
 * Gère l'ouverture/fermeture des questions FAQ avec animations fluides
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les éléments FAQ
    const faqItems = document.querySelectorAll('.faq-item');
    
    if (faqItems.length === 0) {
        return;
    }
    
    // Ajouter les écouteurs d'événements
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question');
        
        if (question) {
            question.addEventListener('click', function() {
                // Basculer l'état actif de l'élément courant
                const isActive = item.classList.contains('active');
                
                // Fermer tous les autres éléments
                faqItems.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Ouvrir/fermer l'élément courant
                if (!isActive) {
                    item.classList.add('active');
                    
                    // Faire défiler doucement vers la question si nécessaire
                    setTimeout(function() {
                        const itemRect = item.getBoundingClientRect();
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        
                        // Vérifier si l'élément n'est pas complètement visible
                        if (itemRect.top < 100 || itemRect.bottom > window.innerHeight - 100) {
                            window.scrollTo({
                                top: scrollTop + itemRect.top - 100,
                                behavior: 'smooth'
                            });
                        }
                    }, 300);
                } else {
                    item.classList.remove('active');
                }
            });
        }
    });
    
    // Gestion du clavier pour l'accessibilité
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question');
        
        if (question) {
            question.setAttribute('tabindex', '0');
            question.setAttribute('role', 'button');
            question.setAttribute('aria-expanded', 'false');
            question.setAttribute('aria-controls', 'faq-answer-' + Array.from(faqItems).indexOf(item));
            
            const answer = item.querySelector('.faq-answer');
            if (answer) {
                answer.setAttribute('id', 'faq-answer-' + Array.from(faqItems).indexOf(item));
            }
            
            // Mettre à jour aria-expanded quand l'état change
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const isActive = item.classList.contains('active');
                        question.setAttribute('aria-expanded', isActive);
                    }
                });
            });
            
            observer.observe(item, {
                attributes: true,
                attributeFilter: ['class']
            });
            
            // Gérer les événements clavier
            question.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    question.click();
                }
            });
        }
    });
    
    // Analytics : suivre les clics sur les questions FAQ
    if (typeof gtag !== 'undefined') {
        faqItems.forEach(function(item) {
            const question = item.querySelector('.faq-question');
            const questionText = question.querySelector('h3').textContent;
            
            question.addEventListener('click', function() {
                gtag('event', 'faq_click', {
                    'faq_question': questionText,
                    'category': document.querySelector('h1').textContent
                });
            });
        });
    }
});
