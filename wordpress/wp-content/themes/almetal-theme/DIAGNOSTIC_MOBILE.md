# 🔍 DIAGNOSTIC MOBILE - AL Metallerie

## Problème constaté
Les styles CSS mobiles ne s'appliquent pas correctement.

## Solutions de test

### ✅ Option 1 : Forcer le mode mobile avec URL
Ajouter `?force_mobile=1` à la fin de l'URL :
```
http://votre-site.local/?force_mobile=1
```

### ✅ Option 2 : Mode responsive Chrome
1. **F12** pour ouvrir DevTools
2. **Ctrl + Shift + M** pour activer le mode responsive
3. Choisir un appareil mobile (iPhone 12, Samsung Galaxy, etc.)
4. **F5** pour rafraîchir la page

### ✅ Option 3 : Vérifier le chargement CSS
1. Ouvrir DevTools (F12)
2. Onglet **Network**
3. Filtrer par **CSS**
4. Rafraîchir (F5)
5. Vérifier que `mobile.css` est chargé

## Vérifications à faire

### 1. La classe `.is-mobile` est-elle présente ?
Inspecter l'élément `<body>` :
```html
<body class="... is-mobile mobile-view one-page-layout">
```

### 2. Le fichier mobile.css est-il chargé ?
Dans le code source (Ctrl+U), chercher :
```html
<link rel='stylesheet' id='almetal-mobile-css' href='.../mobile.css' />
```

### 3. Les styles s'appliquent-ils ?
Inspecter un élément mobile (ex: `.mobile-header`) :
- Si les styles sont barrés → problème de spécificité
- Si les styles n'apparaissent pas → problème de chargement

## Structure CSS mobile

Le fichier `mobile.css` utilise une media query :
```css
@media (max-width: 768px) {
    /* Tous les styles mobiles */
}
```

**Important** : Les styles ne s'appliquent QUE si :
- La largeur d'écran est < 768px
- OU le mode responsive est activé dans le navigateur

## Fichiers concernés

- `functions.php` : Détection mobile + chargement CSS
- `mobile.css` : Tous les styles mobiles
- `header-mobile.php` : Template header mobile
- `footer-mobile.php` : Template footer mobile
- `mobile-onepage.php` : Template one-page mobile

## Contact
Si le problème persiste, vérifier :
1. Cache du navigateur (Ctrl+Shift+R pour hard refresh)
2. Cache WordPress (si plugin de cache actif)
3. Console JavaScript pour erreurs
