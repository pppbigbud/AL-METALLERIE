# 📱 PLAN COMPLET - VERSION MOBILE ONE-PAGE
## Thème AL Metallerie

---

## 🎨 ANALYSE DU STYLE GRAPHIQUE EXISTANT

### Palette de couleurs
- **Primaire** : `#F08B18` (Orange métallerie)
- **Fond** : `#222222` (Gris foncé)
- **Texte** : `#ECECEC` (Blanc cassé)
- **Accent** : Dégradés orange (`#F08B18` → `#e67e0f`)

### Typographie
- **Titres** : `Poppins`, bold 700
- **Corps** : `Roboto Flex`, regular 400
- **Tailles** : Base 16px, responsive

### Effets et animations
- **Transitions** : 0.3s ease (standard)
- **Hover** : `translateY(-5px)` + shadow orange
- **Border-radius** : 12-16px (moderne)
- **Shadows** : Multiples niveaux avec teinte orange

### Style des cards (Desktop)
```css
.card {
    background: rgba(34, 34, 34, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid rgba(240, 139, 24, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 70px rgba(240, 139, 24, 0.3);
    border-color: var(--color-primary);
}
```

---

## 📂 ARCHITECTURE ACTUELLE

### Fichiers existants
```
almetal-theme/
├── assets/
│   ├── css/
│   │   ├── style.css (variables globales)
│   │   ├── components.css (cards, boutons)
│   │   ├── custom.css (styles desktop)
│   │   ├── mobile.css (styles mobile - AVEC media query)
│   │   └── ...
│   └── js/
│       ├── main.js
│       └── mobile-realisations-filter.js
├── template-parts/
│   ├── hero-carousel.php
│   ├── section-presentation.php
│   ├── section-services.php
│   ├── mobile-onepage.php (EXISTE DÉJÀ !)
│   ├── mobile-realisations.php
│   └── mobile-formations.php
├── front-page.php
├── header.php
├── footer.php
└── functions.php
```

### Chargement CSS actuel
- `mobile.css` est chargé **POUR TOUS** (ligne 154-160 functions.php)
- Contient une media query `@media (max-width: 768px)` (ligne 10)
- ✅ **Bonne approche** : CSS mobile isolé dans media queries

---

## ✅ RECOMMANDATION : APPROCHE **B + C**

### B) Media queries strictes dans fichiers existants
- ✅ `mobile.css` déjà configuré avec media query
- ✅ Isolation complète du code mobile
- ✅ Pas de conflit avec desktop

### C) Templates WordPress dédiés
- ✅ `mobile-onepage.php` existe déjà
- ✅ Créer templates pour pages détaillées
- ✅ Utiliser `wp_is_mobile()` pour forcer les templates

### Pourquoi cette approche ?
1. **Séparation claire** : CSS mobile dans `mobile.css` avec media queries
2. **Templates dédiés** : Structure HTML différente pour mobile
3. **Pas de conflit** : Le desktop reste intact
4. **Performance** : Chargement conditionnel du JS mobile
5. **Maintenabilité** : Code organisé et commenté

---

## 🏗️ STRUCTURE MOBILE ONE-PAGE (Accueil)

### Sections dans l'ordre
```
1. Header Mobile (menu burger)
   └─ Logo centré + Icône burger

2. Slideshow Touch
   └─ Swiper.js avec navigation tactile

3. Section Présentation (NON cliquable)
   └─ Contenu de la page "À propos"

4. Section Actualités/Réalisations (CLIQUABLE)
   ├─ Titre cliquable → Page Actualités
   ├─ Menu déroulant filtrage (avec icônes)
   ├─ Maximum 3 cards
   └─ Style cards desktop adapté

5. Section Formations (CLIQUABLE)
   ├─ Titre cliquable → Page Formations
   ├─ 2-3 cards empilées
   └─ Style cards desktop adapté

6. Section Contact (CLIQUABLE optionnel)
   ├─ Titre cliquable → Page Contact
   ├─ Formulaire simplifié
   └─ Google Maps pleine largeur

7. Footer Light
   └─ Mentions + Réseaux sociaux + Copyright
```

---

## 📄 PAGES DÉTAILLÉES MOBILES

### 1. Page Actualités (archive-realisation-mobile.php)
```
Header avec bouton RETOUR
├─ Logo centré
└─ ← Retour (vers home)

Liste complète des réalisations
├─ Filtrage par catégories (menu déroulant)
├─ Toutes les cards (pas de limite)
├─ Pagination ou infinite scroll
└─ Style identique à la one-page

Footer Light
```

### 2. Page Formations (page-formations-mobile.php)
```
Header avec bouton RETOUR
├─ Logo centré
└─ ← Retour (vers home)

Liste complète des formations
├─ Cards empilées verticalement
├─ Description complète
└─ Style identique à la one-page

Footer Light
```

### 3. Page Contact (page-contact-mobile.php)
```
Header avec bouton RETOUR
├─ Logo centré
└─ ← Retour (vers home)

Formulaire de contact complet
├─ Champs tactiles bien dimensionnés
├─ Google Maps pleine largeur
└─ Informations de contact

Footer Light
```

---

## 🔄 SYSTÈME DE NAVIGATION CONDITIONNELLE

### Sur la ONE-PAGE (accueil mobile)
```html
<header class="mobile-header">
    <div class="mobile-logo">
        <img src="logo.svg" alt="AL Metallerie">
    </div>
    <button class="mobile-burger-btn" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</header>

<nav class="mobile-menu" id="mobile-menu">
    <ul>
        <li><a href="#accueil">Accueil</a></li>
        <li><a href="#presentation">Présentation</a></li>
        <li><a href="#actualites">Actualités</a></li>
        <li><a href="#formations">Formations</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>
```

### Sur les PAGES DÉTAILLÉES
```html
<header class="mobile-header mobile-header--back">
    <a href="<?php echo home_url(); ?>" class="mobile-back-btn">
        <svg><!-- Icône flèche gauche --></svg>
        <span>Retour</span>
    </a>
    <div class="mobile-logo">
        <img src="logo.svg" alt="AL Metallerie">
    </div>
</header>
```

### Détection JavaScript
```javascript
// Détecter la page courante
const isHomePage = document.body.classList.contains('home');
const burger = document.querySelector('.mobile-burger-btn');
const backBtn = document.querySelector('.mobile-back-btn');

if (isHomePage && burger) {
    // Activer le menu burger
    burger.addEventListener('click', toggleMenu);
} else if (backBtn) {
    // Le bouton retour est déjà un lien, pas besoin de JS
}
```

---

## 🎬 ANIMATIONS AU SCROLL

### Bibliothèque recommandée : **Intersection Observer API** (natif)
- ✅ Performant (pas de bibliothèque externe)
- ✅ Support moderne (>95% navigateurs)
- ✅ Léger et flexible

### Animations prévues
1. **Fade-in** : Sections principales
2. **Slide-in** : Cards (de bas en haut)
3. **Stagger** : Cards multiples (décalage 100ms)
4. **Scale** : Icônes et images

### Exemple d'implémentation
```javascript
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
        }
    });
}, observerOptions);

// Observer toutes les sections
document.querySelectorAll('.mobile-section').forEach(section => {
    observer.observe(section);
});
```

---

## 📋 PLAN D'IMPLÉMENTATION (Étapes)

### PHASE 1 : Nettoyage et préparation ✅
- [x] Analyser le thème existant
- [x] Documenter le style graphique
- [x] Vérifier la structure mobile actuelle
- [x] Valider l'approche (media queries + templates)

### PHASE 2 : Header et Navigation Mobile
1. Créer `header-mobile.php` avec 2 variantes :
   - Burger (one-page)
   - Retour (pages détaillées)
2. Ajouter CSS dans `mobile.css`
3. JavaScript pour menu burger (smooth scroll)

### PHASE 3 : One-Page Mobile (Accueil)
1. Améliorer `mobile-onepage.php` :
   - Ajouter titres cliquables (Actualités, Formations, Contact)
   - Intégrer slideshow touch (Swiper.js)
   - Optimiser section Présentation
2. Compléter `mobile.css` :
   - Reprendre style cards desktop
   - Adapter pour mobile (vertical)
   - Animations au scroll

### PHASE 4 : Pages Détaillées Mobiles
1. Créer `archive-realisation-mobile.php` (Actualités)
2. Créer `page-formations-mobile.php` (Formations)
3. Créer `page-contact-mobile.php` (Contact)
4. Ajouter header avec bouton retour
5. Reprendre style cards + filtrage

### PHASE 5 : Animations et Interactions
1. Implémenter Intersection Observer
2. Ajouter animations fade-in/slide-in
3. Stagger pour cards multiples
4. Smooth scroll pour ancres

### PHASE 6 : Filtrage AJAX Réalisations
1. Menu déroulant avec icônes
2. Filtrage sans rechargement
3. Limitation 3 cards (one-page)
4. Toutes cards (page détaillée)

### PHASE 7 : Slideshow Touch
1. Intégrer Swiper.js
2. Configuration tactile
3. Adaptation hauteur mobile
4. Animations de transition

### PHASE 8 : Footer Light Mobile
1. Version allégée
2. Mentions légales + Réseaux sociaux
3. Copyright
4. Présent sur toutes les pages

### PHASE 9 : Tests et Optimisations
1. Tester sur différents appareils
2. Vérifier que desktop est intact
3. Performance (Lighthouse)
4. Accessibilité (WCAG)

---

## 📦 LIVRABLES

### Fichiers à créer
```
template-parts/
├── header-mobile.php (nouveau)
├── footer-mobile.php (nouveau)
├── archive-realisation-mobile.php (nouveau)
├── page-formations-mobile.php (nouveau)
└── page-contact-mobile.php (nouveau)

assets/css/
└── mobile.css (améliorer existant)

assets/js/
├── mobile-navigation.js (nouveau)
├── mobile-animations.js (nouveau)
└── mobile-slideshow.js (nouveau)
```

### Fichiers à modifier
```
functions.php
├── Ajouter chargement JS mobile
└── Forcer templates mobiles conditionnels

mobile-onepage.php
├── Ajouter titres cliquables
├── Intégrer slideshow
└── Optimiser sections

mobile-realisations.php
└── Menu déroulant avec icônes
```

---

## ⚠️ POINTS D'ATTENTION

### Ne PAS toucher
- ❌ CSS desktop (`custom.css` sans media queries)
- ❌ Templates desktop (`section-*.php`)
- ❌ JavaScript desktop (`main.js` hors conditions mobiles)

### Toujours utiliser
- ✅ Media query `@media (max-width: 768px)`
- ✅ Préfixe `.mobile-` pour les classes
- ✅ Commentaires clairs `/* MOBILE ONLY */`

### Tester
- ✅ Desktop intact après chaque modification
- ✅ Responsive 320px → 768px
- ✅ Touch events (swipe, tap)
- ✅ Performance (temps de chargement)

---

## 🚀 PRÊT À COMMENCER ?

**Question** : Souhaitez-vous que je commence par :

**A)** Header et Navigation Mobile (menu burger + bouton retour)  
**B)** Amélioration de la One-Page (titres cliquables + animations)  
**C)** Pages détaillées (Actualités, Formations, Contact)  
**D)** Slideshow touch avec Swiper.js  

Ou préférez-vous un autre ordre ?
