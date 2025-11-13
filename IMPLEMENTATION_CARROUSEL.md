# Implémentation du Carrousel Hero - AL Métallerie

## ✅ Fichiers créés/modifiés

### 1. Template du carrousel
**Fichier** : `wordpress/wp-content/themes/almetal-theme/template-parts/hero-carousel.php`
- Carrousel avec 3 slides
- Contrôles de navigation (précédent/suivant)
- Indicateurs de slides
- Structure HTML sémantique

### 2. Page d'accueil
**Fichier** : `wordpress/wp-content/themes/almetal-theme/front-page.php`
- Intégration du carrousel en version desktop uniquement
- Préservation de la version one-page pour mobile

### 3. Styles CSS
**Fichier** : `wordpress/wp-content/themes/almetal-theme/style.css`
- Styles du carrousel avec transitions rapides (0.6s)
- Animations fluides pour le contenu
- Design responsive (mobile/tablet/desktop)
- Effets hover sur les boutons et CTA

### 4. JavaScript
**Fichier** : `wordpress/wp-content/themes/almetal-theme/assets/js/main.js`
- Fonction `initHeroCarousel()` ajoutée
- Navigation automatique toutes les 5 secondes
- Contrôles manuels (boutons + clavier)
- Pause au survol
- Gestion des indicateurs

### 5. Dossier images
**Dossier** : `wordpress/wp-content/themes/almetal-theme/assets/images/hero/`
- README avec instructions pour ajouter les images
- Recommandations de format et dimensions

## 🎨 Caractéristiques du carrousel

### Transitions
- **Durée** : 0.6 secondes (rapide et fluide)
- **Intervalle** : 5 secondes entre chaque slide
- **Animation** : Fade in/out avec translateY pour le contenu

### Navigation
- **Automatique** : Défilement toutes les 5 secondes
- **Manuelle** : Boutons précédent/suivant
- **Clavier** : Flèches gauche/droite
- **Indicateurs** : Points cliquables en bas du carrousel
- **Pause** : Au survol de la souris

### Responsive
- **Desktop** : Hauteur 80vh (max 800px)
- **Mobile** : Hauteur 60vh avec contrôles réduits
- **Tablet** : Adaptation automatique

## 📝 Prochaines étapes

### 1. Ajouter les images
Placez vos images dans le dossier :
```
wordpress/wp-content/themes/almetal-theme/assets/images/hero/
```

Noms requis :
- `hero-1.jpg` - Image d'accueil
- `hero-2.jpg` - Image créations
- `hero-3.jpg` - Image formations

### 2. Personnaliser les textes
Éditez le fichier `template-parts/hero-carousel.php` pour modifier :
- Les titres (`.hero-title`)
- Les sous-titres (`.hero-subtitle`)
- Les liens des boutons CTA (`.hero-cta`)

### 3. Ajuster les couleurs
Dans `style.css`, modifiez les variables CSS :
```css
:root {
    --color-primary: #F08B18;    /* Couleur principale */
    --color-secondary: #3498db;  /* Couleur secondaire */
}
```

### 4. Ajouter/supprimer des slides
Dans `template-parts/hero-carousel.php`, dupliquez ou supprimez les blocs :
```html
<div class="hero-slide" style="background-image: url('...');">
    <div class="hero-content">
        <h1 class="hero-title">Titre</h1>
        <p class="hero-subtitle">Sous-titre</p>
        <a href="#" class="hero-cta">Bouton</a>
    </div>
</div>
```

## 🔧 Configuration technique

### Dépendances
- **jQuery** : Chargé automatiquement par WordPress
- **WordPress** : Version 6.0 minimum
- **PHP** : Version 7.4 minimum

### Performance
- Transitions CSS optimisées
- Pas de bibliothèque externe
- Code léger et performant
- Images à optimiser (max 500KB chacune)

## 🎯 Fonctionnalités

✅ Carrousel automatique avec intervalle de 5 secondes
✅ Transitions rapides (0.6s)
✅ Navigation manuelle (boutons + clavier)
✅ Indicateurs de slides
✅ Pause au survol
✅ Responsive design
✅ Animations fluides du contenu
✅ Overlay sombre sur les images
✅ Boutons CTA avec effets hover
✅ Accessible (ARIA labels)

## 📱 Comportement selon le device

### Desktop (> 768px)
- Carrousel visible en pleine page
- Hauteur 80vh (max 800px)
- Navigation complète

### Mobile (≤ 768px)
- Carrousel masqué (version one-page utilisée)
- Si activé : hauteur 60vh avec contrôles réduits

## 🐛 Dépannage

### Le carrousel ne s'affiche pas
1. Vérifiez que vous êtes en version desktop
2. Vérifiez que les images existent dans le dossier
3. Vérifiez la console JavaScript pour les erreurs

### Les transitions sont trop lentes/rapides
Modifiez dans `style.css` :
```css
.hero-slide {
    transition: opacity 0.6s ease-in-out; /* Ajustez la durée */
}
```

Et dans `main.js` :
```javascript
const slideDelay = 5000; // Ajustez l'intervalle (en ms)
```

### Les images ne se chargent pas
1. Vérifiez les chemins dans `hero-carousel.php`
2. Vérifiez les permissions du dossier images
3. Utilisez l'inspecteur du navigateur pour voir les erreurs

## 📞 Support

Pour toute question ou modification, référez-vous à :
- Documentation WordPress : https://developer.wordpress.org/
- Thème AL Métallerie : Voir `CHECKLIST_PROJET_WORDPRESS.md`
