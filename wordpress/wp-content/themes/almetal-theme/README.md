# Thème AL Metallerie

Thème WordPress personnalisé pour AL Metallerie avec design responsive adaptatif.

## 🎯 Caractéristiques

- **Mobile** : Layout one-page avec navigation par ancres
- **Desktop** : Layout multi-pages classique
- **Responsive** : Adaptation automatique selon le device
- **Performance** : Code optimisé et léger
- **SEO-friendly** : Structure sémantique HTML5

## 📁 Structure du thème

```
almetal-theme/
├── style.css              # Styles principaux + métadonnées du thème
├── functions.php          # Fonctionnalités WordPress
├── index.php              # Template par défaut
├── front-page.php         # Page d'accueil
├── page.php               # Pages standards
├── single.php             # Articles individuels
├── header.php             # En-tête du site
├── footer.php             # Pied de page
├── screenshot.png         # Capture d'écran du thème
├── README.md              # Ce fichier
├── assets/
│   ├── css/
│   │   └── custom.css     # Styles personnalisés
│   ├── js/
│   │   └── main.js        # Scripts JavaScript
│   └── images/            # Images du thème
└── template-parts/
    └── mobile-onepage.php # Template one-page mobile
```

## 🚀 Installation

1. Télécharger ou cloner le thème dans `wp-content/themes/`
2. Activer le thème depuis l'administration WordPress
3. Configurer les menus dans **Apparence > Menus**
4. Personnaliser dans **Apparence > Personnaliser**

## 🎨 Personnalisation

### Couleurs

Les couleurs sont définies en variables CSS dans `style.css` :

```css
:root {
    --color-primary: #2c3e50;
    --color-secondary: #3498db;
    --color-accent: #e74c3c;
    /* ... */
}
```

### Menus

Le thème supporte 2 emplacements de menu :
- **Menu Principal** : Navigation principale
- **Menu Footer** : Pied de page

### Widgets

Zones de widgets disponibles :
- **Sidebar Principale** : Sidebar (desktop uniquement)
- **Footer Widget 1, 2, 3** : Trois zones dans le footer

## 📱 One-Page Mobile

Pour la navigation one-page sur mobile :

1. Créer vos pages dans WordPress
2. Ajouter un **ID de section** dans la métabox (panneau latéral)
3. Utiliser cet ID dans les liens du menu : `#services`, `#contact`, etc.

## 🔧 Fonctionnalités

### Détection Mobile/Desktop

Le thème détecte automatiquement le type d'appareil et applique le bon layout.

### Navigation Smooth Scroll

Navigation fluide entre les sections (one-page mobile).

### Lazy Loading

Chargement différé des images pour de meilleures performances.

### SEO

- Balises sémantiques HTML5
- Support du titre automatique
- Meta tags optimisés

## 🎯 Intégration Figma

Pour intégrer votre maquette Figma :

1. **Exporter les assets** :
   - Images → `assets/images/`
   - Icônes SVG → `assets/images/icons/`

2. **Couleurs** :
   - Copier la palette de couleurs
   - Mettre à jour les variables CSS dans `style.css`

3. **Typographie** :
   - Ajouter les Google Fonts dans `functions.php`
   - Mettre à jour `--font-primary` et `--font-heading`

4. **Layout** :
   - Adapter les sections dans `template-parts/mobile-onepage.php`
   - Personnaliser `custom.css` selon le design

## 📝 TODO

- [ ] Ajouter un formulaire de contact
- [ ] Créer des Custom Post Types si nécessaire
- [ ] Intégrer la maquette Figma complète
- [ ] Optimiser les images
- [ ] Ajouter des animations
- [ ] Tester sur différents navigateurs

## 🔒 Sécurité

- Échappement de toutes les sorties
- Vérification des nonces
- Validation des entrées utilisateur
- Protection contre l'accès direct aux fichiers

## 📄 Licence

Projet privé - AL Metallerie © 2025

## 👨‍💻 Développeur

BIGBUD - Développeur Web & Web Mobile
