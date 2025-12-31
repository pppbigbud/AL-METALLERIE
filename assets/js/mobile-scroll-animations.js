/**
 * Animations au scroll pour mobile
 * Gère les classes .scroll-xxx pour les templates mobiles
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si on est sur mobile
    if (window.innerWidth <= 768) {
        initMobileScrollAnimations();
    }
});

function initMobileScrollAnimations() {
    // Éléments à animer
    const animatedElements = document.querySelectorAll('.scroll-zoom, .scroll-fade, .scroll-slide-up');
    
    if (animatedElements.length === 0) return;
    
    // Observer pour détecter quand les éléments sont visibles
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                
                // Animation des cartes en cascade
                if (entry.target.classList.contains('scroll-slide-up')) {
                    const cards = entry.target.parentElement.querySelectorAll('.scroll-slide-up');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.classList.add('active');
                        }, index * 100);
                    });
                }
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px 0px -50px 0px'
    });
    
    // Observer tous les éléments
    animatedElements.forEach(element => {
        observer.observe(element);
    });
    
    // Gérer le redimensionnement
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            // Nettoyer les classes si on passe en desktop
            animatedElements.forEach(element => {
                element.classList.remove('active');
            });
            observer.disconnect();
        }
    });
}
