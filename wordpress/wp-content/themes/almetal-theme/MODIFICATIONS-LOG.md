# 📝 Journal des modifications - AL Métallerie

## 14 novembre 2024

### ✅ Favicon et couleur du navigateur

**Fichiers créés** :
- `site.webmanifest` : Configuration PWA (Android)
- `browserconfig.xml` : Configuration Windows
- `assets/images/favicons/` : Dossier pour les favicons
- `FAVICON-GUIDE.md` : Guide complet de génération

**Fichier modifié** :
- `header.php` (lignes 16-29) : Balises meta + liens favicon

**Configuration** :
- ✅ Couleur du navigateur : Orange `#F08B18`
- ✅ Android Chrome : Barre d'adresse orange
- ✅ iOS Safari : Barre de statut adaptée
- ✅ Windows : Tuile orange
- ✅ PWA ready (Progressive Web App)

**Balises ajoutées** :
```html
<meta name="theme-color" content="#F08B18">
<meta name="msapplication-TileColor" content="#F08B18">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
```

**Favicons à générer** :
- favicon.ico (16x16, 32x32, 48x48)
- favicon-16x16.png, favicon-32x32.png
- apple-touch-icon.png (180x180)
- android-chrome-192x192.png, android-chrome-512x512.png
- mstile-150x150.png

**Guide** : Voir `FAVICON-GUIDE.md` pour générer les favicons depuis le logo

---

### ✅ Template "Page En Construction"

**Fichiers créés** :
- `page-en-construction.php` : Template de page assignable
- `assets/css/under-construction.css` : Styles dédiés

**Fichier modifié** :
- `functions.php` (lignes 439-451) : Enqueue du CSS

**Utilisation** :
1. Créer une page dans WordPress
2. Dans "Attributs de page" > "Modèle", sélectionner "Page En Construction"
3. Publier la page

**Caractéristiques** :
- ✅ Message : "Cette page est encore à l'atelier !"
- ✅ Icône casque de chantier + outils croisés (SVG)
- ✅ Fond transparent (seule couleur background `#222222` visible)
- ✅ Bouton "Retour à l'accueil"
- ✅ Style identique à la page 404 (minimaliste, centré)
- ✅ Responsive (mobile, tablet, desktop)
- ✅ Animation de flottement de l'icône (3s infinite)
- ✅ Animations au chargement (fadeInUp, scaleIn)
- ✅ Accessibilité : focus visible, prefers-reduced-motion

**Design** :
- Couleurs : Orange `#F08B18`, fond `#222222`
- Polices : Poppins (titres), Roboto Flex (textes)
- Icône : 120-200px selon device
- Bouton : Border-radius 50px, effet ripple

---

### ✅ Page 404 personnalisée

**Fichiers créés** :
- `404.php` : Template de la page 404
- `assets/css/error-404.css` : Styles dédiés à la page 404

**Fichier modifié** :
- `functions.php` (lignes 425-437) : Enqueue du CSS 404

**Caractéristiques** :
- ✅ Message humoristique lié à la métallerie : "Cette page a été soudée au mauvais endroit !"
- ✅ Code "404" stylisé avec icône de marteau au centre
- ✅ Fond sombre cohérent avec le site
- ✅ Bouton "Retour à l'accueil" avec effet hover
- ✅ Liens de suggestion : Réalisations, Contact, Formations
- ✅ Style minimaliste et centré
- ✅ Responsive (mobile, tablet, desktop)
- ✅ Animations subtiles au chargement (fadeInUp, scaleIn)
- ✅ Animation du marteau (rotation douce)
- ✅ Effet de texture métallique en fond
- ✅ Accessibilité : focus visible, prefers-reduced-motion

**Design** :
- Couleurs : Orange `#F08B18`, fond sombre `#222222`
- Polices : Poppins (titres), Roboto Flex (textes)
- Effets : Glow orange, ombres, transitions fluides
- Bouton : Border-radius 50px, effet ripple au hover

---

### ✅ Harmonisation des images de la section Formations

**Fichier modifié** : `assets/css/custom.css` (lignes 2534-2549)

**Problème** : Les images des cards de la section Formations avaient un dimensionnement différent des cards de Réalisations.

**Solution** : Ajout du ratio `aspect-ratio: 4/3` pour les images de la section Formations (desktop uniquement).

**Styles ajoutés** :
```css
/* Image de la carte - Alignée sur le style des réalisations (DESKTOP uniquement) */
.services-grid .realisation-image-wrapper {
    position: relative;
    overflow: hidden;
    aspect-ratio: 4/3; /* Ratio 4/3 comme les cards de réalisations */
}

.services-grid .realisation-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.services-grid .realisation-card:hover .realisation-image {
    transform: scale(1.1);
}
```

**Impact** :
- ✅ Desktop : Images avec ratio 4/3 identique aux réalisations
- ✅ Mobile : Conserve la hauteur fixe de 250px (inchangé)
- ✅ Effet hover : Zoom 1.1x au survol (identique aux réalisations)

---

## Modifications précédentes

### Système de gestion du slideshow
**Date** : 14 novembre 2024
**Fichiers** : Voir `SLIDESHOW-ADMIN-README.md`

### Bannière de cookies RGPD
**Date** : Antérieur
**Fichiers** : Voir `COOKIE-BANNER-README.md`
