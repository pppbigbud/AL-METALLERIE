# 📁 Dossier Images

Ce dossier contient toutes les images et assets visuels du thème.

## 📋 Organisation recommandée

```
images/
├── logo.svg                    # Logo du site
├── logo-white.svg              # Logo version blanche
├── favicon.ico                 # Favicon
├── hero-bg.jpg                 # Image de fond hero
├── icons/                      # Icônes SVG
│   ├── phone.svg
│   ├── email.svg
│   ├── location.svg
│   ├── facebook.svg
│   ├── instagram.svg
│   └── linkedin.svg
├── gallery/                    # Photos de réalisations
│   ├── projet-1.jpg
│   ├── projet-2.jpg
│   └── ...
├── services/                   # Images des services
│   ├── service-1.jpg
│   └── ...
└── team/                       # Photos de l'équipe
    └── ...
```

## 🎨 Export depuis Figma

### Images (JPG/PNG)
1. Sélectionner l'image dans Figma
2. Export → PNG ou JPG
3. Résolution : 2x (pour Retina)
4. Enregistrer ici

### Icônes (SVG)
1. Sélectionner l'icône dans Figma
2. Export → SVG
3. Optimiser sur https://jakearchibald.github.io/svgomg/
4. Enregistrer dans `icons/`

## 📏 Tailles recommandées

| Type | Largeur max | Format | Poids max |
|------|-------------|--------|-----------|
| Logo | 400px | SVG/PNG | 50KB |
| Hero | 1920px | JPG | 200KB |
| Gallery | 1200px | JPG | 150KB |
| Icônes | - | SVG | 10KB |
| Favicon | 32x32px | ICO/PNG | 5KB |

## 🗜️ Optimisation

Avant d'uploader les images, les optimiser :
- **JPG** : https://tinypng.com/
- **PNG** : https://tinypng.com/
- **SVG** : https://jakearchibald.github.io/svgomg/

## 💡 Utilisation dans le code

### PHP (WordPress)
```php
<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Logo">
```

### CSS
```css
.hero {
    background-image: url('../images/hero-bg.jpg');
}
```

## ⚠️ Important

- **Ne pas commiter** les images trop lourdes sur Git
- **Toujours optimiser** avant upload
- **Utiliser des noms descriptifs** : `hero-metallerie.jpg` plutôt que `img1.jpg`
- **Format SVG** pour les logos et icônes (meilleure qualité)
- **Format JPG** pour les photos
- **Format PNG** pour les images avec transparence
