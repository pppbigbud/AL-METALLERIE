# 🎬 Guide des Animations Mobile - AL Métallerie

## 📋 Vue d'ensemble

Système d'animations au scroll optimisé pour mobile, sans bibliothèques externes.
- ✅ Utilise `IntersectionObserver` API (natif, performant)
- ✅ Support de `prefers-reduced-motion` (accessibilité)
- ✅ Fallback pour anciens navigateurs
- ✅ Optimisé GPU avec `will-change`
- ✅ Nettoyage automatique de la mémoire

---

## 🎨 Classes d'Animation Disponibles

### Animations de base

| Classe | Effet | Usage recommandé |
|--------|-------|------------------|
| `.scroll-fade` | Apparition progressive (fade-in + slide-up) | Sections, titres |
| `.scroll-slide-up` | Glissement vers le haut | Cards, conteneurs |
| `.scroll-slide-left` | Glissement depuis la gauche | Cards alternées |
| `.scroll-slide-right` | Glissement depuis la droite | Cards alternées |
| `.scroll-zoom` | Zoom léger | Boutons, badges |
| `.scroll-zoom-in` | Zoom depuis petit | Images, icônes |

### Animations avancées

| Classe | Effet | Usage recommandé |
|--------|-------|------------------|
| `.scroll-rotate` | Rotation + fade | Éléments décoratifs |
| `.scroll-flip` | Flip horizontal | Cards spéciales |
| `.scroll-blur` | Blur + fade | Backgrounds, overlays |

### Délais en cascade

Créent un effet de cascade pour les éléments multiples :

```html
<div class="realisation-card scroll-slide-left scroll-delay-1">...</div>
<div class="realisation-card scroll-slide-left scroll-delay-2">...</div>
<div class="realisation-card scroll-slide-left scroll-delay-3">...</div>
```

Classes disponibles : `.scroll-delay-1` à `.scroll-delay-5`

---

## 💻 Exemples d'Utilisation

### 1. Section Hero

```html
<section id="hero" class="mobile-section scroll-fade">
    <h1 class="section-title">Bienvenue chez AL Métallerie</h1>
    <p class="scroll-fade scroll-delay-1">Votre expert en métallerie</p>
    <a href="#contact" class="mobile-btn-cta scroll-zoom scroll-delay-2">Contactez-nous</a>
</section>
```

### 2. Grille de Réalisations

```html
<div class="realisations-grid">
    <?php foreach ($realisations as $index => $realisation) : ?>
        <div class="realisation-card scroll-slide-up scroll-delay-<?php echo ($index % 3) + 1; ?>">
            <img src="..." alt="..." class="scroll-zoom-in">
            <h3><?php echo $realisation->post_title; ?></h3>
        </div>
    <?php endforeach; ?>
</div>
```

### 3. Cards de Contact

```html
<div class="mobile-contact-info-grid">
    <a href="tel:..." class="mobile-contact-info-card scroll-fade scroll-delay-1">
        <div class="mobile-contact-info-icon scroll-zoom">
            <!-- SVG icon -->
        </div>
        <div class="mobile-contact-info-content">
            <h3>Téléphone</h3>
            <p>06 XX XX XX XX</p>
        </div>
    </a>
    
    <a href="mailto:..." class="mobile-contact-info-card scroll-fade scroll-delay-2">
        <!-- ... -->
    </a>
</div>
```

### 4. Boutons CTA

```html
<a href="#contact" class="mobile-btn-cta scroll-zoom">
    Demander un devis
</a>
```

### 5. Images avec Zoom

```html
<div class="image-container scroll-zoom-in">
    <img src="realisation.jpg" alt="Portail en acier">
</div>
```

---

## 🎯 Recommandations par Type d'Élément

### Sections

```html
<section class="mobile-section scroll-fade">
```

**Pourquoi ?** Apparition douce et progressive, idéale pour les grandes zones.

### Cards (Réalisations, Services, Actualités)

```html
<div class="realisation-card scroll-slide-up scroll-delay-1">
```

**Pourquoi ?** Glissement vers le haut + délai crée un effet cascade élégant.

### Boutons CTA

```html
<a href="#" class="mobile-btn-cta scroll-zoom">
```

**Pourquoi ?** Le zoom attire l'attention sur l'action principale.

### Images

```html
<img src="..." class="scroll-zoom-in">
```

**Pourquoi ?** Zoom progressif rend les images plus dynamiques.

### Titres de Section

```html
<h2 class="mobile-section-title scroll-fade">
```

**Pourquoi ?** Fade simple et élégant pour les titres.

### Icônes

```html
<div class="icon scroll-zoom">
```

**Pourquoi ?** Zoom léger pour donner vie aux icônes.

---

## 🔧 Configuration Technique

### Fichiers créés

1. **CSS** : `/assets/css/mobile-animations.css`
   - Définit toutes les animations
   - Support de `prefers-reduced-motion`
   - Optimisations GPU avec `will-change`

2. **JavaScript** : `/assets/js/mobile-animations.js`
   - Utilise `IntersectionObserver`
   - Fallback pour anciens navigateurs
   - Nettoyage automatique de la mémoire

3. **functions.php** : Chargement conditionnel (mobile uniquement)

```php
if (almetal_is_mobile()) {
    wp_enqueue_style('almetal-mobile-animations-css', ...);
    wp_enqueue_script('almetal-mobile-animations', ...);
}
```

### Options de l'IntersectionObserver

```javascript
const observerOptions = {
    threshold: 0.1,              // Déclenche à 10% de visibilité
    rootMargin: '0px 0px -50px 0px'  // Déclenche 50px avant d'être visible
};
```

**Personnalisation** : Modifiez ces valeurs dans `mobile-animations.js` si besoin.

---

## ♿ Accessibilité

### Support de prefers-reduced-motion

Le système désactive **automatiquement** toutes les animations si l'utilisateur a activé "Réduire les mouvements" dans ses paramètres système.

**CSS** :
```css
@media (prefers-reduced-motion: reduce) {
    .scroll-fade, .scroll-slide-up, ... {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
}
```

**JavaScript** :
```javascript
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (reducedMotion) return; // Désactive tout
```

---

## 🚀 Performances

### Optimisations implémentées

1. **IntersectionObserver** : Plus performant que `scroll` event
2. **will-change** : Accélération GPU pour les animations
3. **Nettoyage automatique** : `will-change: auto` après 3s
4. **unobserve()** : Arrête d'observer après l'animation
5. **requestAnimationFrame** : Synchronisation avec le navigateur
6. **Throttling** : Dans le fallback scroll

### Mesurer les performances

Ouvrez les DevTools (F12) → Onglet **Performance** :

1. Cliquez sur **Record**
2. Scrollez dans la page
3. Arrêtez l'enregistrement
4. Vérifiez que les animations ne causent pas de "jank" (saccades)

**Cible** : 60 FPS constant

---

## 🧪 Tests

### Test 1 : Vérifier que les animations fonctionnent

1. Ouvrez http://localhost:8000 sur mobile (ou Ctrl+Shift+M dans Chrome)
2. Scrollez lentement
3. Les éléments doivent apparaître progressivement

### Test 2 : Vérifier prefers-reduced-motion

**Chrome** :
1. F12 → Onglet **Rendering**
2. Cochez "Emulate CSS media feature prefers-reduced-motion"
3. Rechargez la page
4. Les animations doivent être désactivées

### Test 3 : Vérifier les performances

1. F12 → Onglet **Performance**
2. Enregistrez pendant le scroll
3. Vérifiez qu'il n'y a pas de frames > 16ms (60 FPS)

### Test 4 : Compatibilité navigateurs

Testez sur :
- ✅ Chrome/Edge (Chromium)
- ✅ Safari iOS
- ✅ Firefox Android
- ✅ Samsung Internet

---

## 🐛 Debugging

### Console logs

Le script affiche des logs dans la console :

```
🎬 Mobile Animations v2.0 - Initialisation
✅ Animations initialisées: 25 éléments observés
🎬 Animation: mobile-hero
🎬 Animation: realisation-card
🧹 will-change nettoyé pour 25 éléments
```

### Fonction de reset (développement)

Dans la console du navigateur :

```javascript
window.resetAnimations()
```

Réinitialise toutes les animations pour les retester.

### Vérifier qu'un élément est observé

```javascript
// Dans la console
document.querySelector('.realisation-card').dataset.animated
// Résultat : "pending" ou "done"
```

---

## 📝 Checklist d'Intégration

### ✅ Fichiers créés

- [x] `/assets/css/mobile-animations.css`
- [x] `/assets/js/mobile-animations.js`
- [x] Modification de `functions.php`

### ✅ Classes ajoutées dans les templates

- [ ] `mobile-onepage.php` : Sections avec `.scroll-fade`
- [ ] Cards de réalisations avec `.scroll-slide-up`
- [ ] Boutons CTA avec `.scroll-zoom`
- [ ] Images avec `.scroll-zoom-in`
- [ ] Cards de contact avec `.scroll-fade` + délais

### ✅ Tests effectués

- [ ] Animations visibles au scroll
- [ ] prefers-reduced-motion fonctionne
- [ ] Performances > 60 FPS
- [ ] Compatible iOS/Android
- [ ] Fallback pour anciens navigateurs

---

## 🎨 Personnalisation

### Modifier la durée des animations

Dans `mobile-animations.css` :

```css
.scroll-fade {
    transition: opacity 0.8s ..., transform 0.8s ...;
    /* Changez 0.8s en 0.6s pour plus rapide, 1s pour plus lent */
}
```

### Modifier le seuil de déclenchement

Dans `mobile-animations.js` :

```javascript
const observerOptions = {
    threshold: 0.1,  // Changez en 0.2 pour déclencher plus tard
    rootMargin: '0px 0px -50px 0px'  // Changez -50px en -100px
};
```

### Ajouter une nouvelle animation

1. **CSS** (`mobile-animations.css`) :

```css
.scroll-bounce {
    opacity: 0;
    transform: translateY(-50px);
    transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.scroll-bounce.visible {
    opacity: 1;
    transform: translateY(0);
}
```

2. **HTML** :

```html
<div class="element scroll-bounce">Contenu</div>
```

---

## 📚 Ressources

- [IntersectionObserver MDN](https://developer.mozilla.org/fr/docs/Web/API/Intersection_Observer_API)
- [prefers-reduced-motion MDN](https://developer.mozilla.org/fr/docs/Web/CSS/@media/prefers-reduced-motion)
- [will-change MDN](https://developer.mozilla.org/fr/docs/Web/CSS/will-change)
- [Cubic Bezier Generator](https://cubic-bezier.com/)

---

## 🎉 Prêt à Utiliser !

Le système d'animations est maintenant opérationnel. Il suffit d'ajouter les classes CSS aux éléments HTML pour les animer au scroll.

**Exemple rapide** :

```html
<section class="mobile-section scroll-fade">
    <h2 class="scroll-fade scroll-delay-1">Nos Réalisations</h2>
    <div class="realisation-card scroll-slide-up scroll-delay-2">...</div>
    <div class="realisation-card scroll-slide-up scroll-delay-3">...</div>
</section>
```

**Résultat** : Animations fluides et performantes ! 🚀
