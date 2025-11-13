# 🎨 Guide d'intégration Figma → WordPress

Ce guide vous accompagne pour intégrer votre maquette Figma dans le thème WordPress AL Metallerie.

---

## 📋 Étape 1 : Export des assets depuis Figma

### 1.1 Exporter les images

1. **Ouvrir votre maquette Figma**
2. **Sélectionner les images** à exporter
3. **Clic droit > Export** ou panneau de droite "Export"
4. **Paramètres recommandés** :
   - Format : **PNG** (pour photos/images complexes)
   - Format : **SVG** (pour icônes/logos)
   - Résolution : **2x** (pour Retina)
5. **Enregistrer dans** : `wordpress/wp-content/themes/almetal-theme/assets/images/`

**Organisation recommandée** :
```
assets/images/
├── logo.svg
├── hero-bg.jpg
├── icons/
│   ├── phone.svg
│   ├── email.svg
│   └── location.svg
└── gallery/
    ├── projet-1.jpg
    ├── projet-2.jpg
    └── ...
```

### 1.2 Exporter les icônes/SVG

1. **Sélectionner l'icône** dans Figma
2. **Export en SVG**
3. **Optimiser le SVG** (optionnel) : https://jakearchibald.github.io/svgomg/
4. **Enregistrer dans** : `assets/images/icons/`

---

## 🎨 Étape 2 : Récupérer la palette de couleurs

### 2.1 Dans Figma

1. **Ouvrir le panneau de styles** (icône pinceau)
2. **Noter toutes les couleurs** utilisées
3. **Copier les codes hexadécimaux**

### 2.2 Mettre à jour le thème

Ouvrir le fichier `style.css` et modifier les variables CSS :

```css
:root {
    /* Couleurs principales */
    --color-primary: #VOTRE_COULEUR;      /* Ex: #2c3e50 */
    --color-secondary: #VOTRE_COULEUR;    /* Ex: #3498db */
    --color-accent: #VOTRE_COULEUR;       /* Ex: #e74c3c */
    
    /* Couleurs de texte */
    --color-text: #VOTRE_COULEUR;         /* Ex: #333333 */
    --color-text-light: #VOTRE_COULEUR;   /* Ex: #666666 */
    
    /* Couleurs de fond */
    --color-background: #VOTRE_COULEUR;   /* Ex: #ffffff */
    --color-background-alt: #VOTRE_COULEUR; /* Ex: #f8f9fa */
}
```

**💡 Astuce** : Utilisez le fichier `DESIGN_TOKENS.md` (voir ci-dessous) pour documenter vos couleurs.

---

## 📝 Étape 3 : Récupérer les typographies

### 3.1 Dans Figma

1. **Sélectionner un texte**
2. **Panneau de droite** → Noter :
   - Nom de la police
   - Taille (px)
   - Poids (weight)
   - Hauteur de ligne (line-height)

### 3.2 Ajouter les Google Fonts

Si vous utilisez des Google Fonts, ouvrir `functions.php` et ajouter :

```php
function almetal_enqueue_fonts() {
    wp_enqueue_style(
        'almetal-google-fonts',
        'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Montserrat:wght@600;700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'almetal_enqueue_fonts');
```

### 3.3 Mettre à jour les variables CSS

Dans `style.css` :

```css
:root {
    --font-primary: 'Roboto', sans-serif;
    --font-heading: 'Montserrat', sans-serif;
}
```

---

## 🏗️ Étape 4 : Structure HTML/CSS

### 4.1 Identifier les sections

Dans votre maquette Figma, identifiez les sections principales :
- Hero / Bannière
- À propos
- Services
- Réalisations / Portfolio
- Contact
- Footer

### 4.2 Créer les sections dans WordPress

1. **Aller dans WordPress** → Pages → Ajouter
2. **Créer une page pour chaque section** :
   - Titre : "Services"
   - ID de section : "services" (dans la métabox à droite)
3. **Répéter pour toutes les sections**

### 4.3 Personnaliser le template mobile

Ouvrir `template-parts/mobile-onepage.php` et personnaliser le HTML selon votre design.

**Exemple de section personnalisée** :

```php
<section id="services" class="onepage-section section-services">
    <div class="container">
        <h2 class="section-title">Nos Services</h2>
        
        <div class="services-grid">
            <div class="service-card">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/service-1.svg" alt="">
                <h3>Service 1</h3>
                <p>Description du service...</p>
            </div>
            <!-- Répéter pour chaque service -->
        </div>
    </div>
</section>
```

---

## 🎯 Étape 5 : Intégrer les styles CSS

### 5.1 Ouvrir `assets/css/custom.css`

### 5.2 Ajouter vos styles personnalisés

**Exemple pour une section Hero** :

```css
/* Section Hero */
.section-hero {
    background-image: url('../images/hero-bg.jpg');
    background-size: cover;
    background-position: center;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 2rem;
}

.hero-cta {
    display: inline-block;
    padding: 1rem 2rem;
    background-color: var(--color-accent);
    color: white;
    border-radius: 50px;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.hero-cta:hover {
    transform: translateY(-3px);
}
```

---

## 📱 Étape 6 : Responsive Design

### 6.1 Vérifier les breakpoints

Les breakpoints par défaut sont :
- Mobile : `< 768px`
- Tablet : `769px - 1024px`
- Desktop : `> 1025px`

### 6.2 Adapter selon votre maquette

Dans `custom.css`, ajoutez vos media queries :

```css
/* Mobile */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
}

/* Desktop */
@media (min-width: 769px) {
    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
}
```

---

## ✅ Checklist d'intégration

- [ ] Exporter toutes les images de Figma
- [ ] Exporter tous les icônes/SVG
- [ ] Noter la palette de couleurs complète
- [ ] Noter les typographies utilisées
- [ ] Ajouter les Google Fonts (si nécessaire)
- [ ] Mettre à jour les variables CSS (couleurs)
- [ ] Mettre à jour les variables CSS (typographies)
- [ ] Créer les pages WordPress pour chaque section
- [ ] Personnaliser le template mobile-onepage.php
- [ ] Ajouter les styles CSS personnalisés
- [ ] Tester sur mobile
- [ ] Tester sur desktop
- [ ] Optimiser les images (compression)

---

## 🔧 Outils utiles

- **Optimisation images** : https://tinypng.com/
- **Optimisation SVG** : https://jakearchibald.github.io/svgomg/
- **Générateur de palette** : https://coolors.co/
- **Google Fonts** : https://fonts.google.com/
- **Can I Use** (compatibilité CSS) : https://caniuse.com/

---

## 💡 Conseils

1. **Commencez par le mobile** : Intégrez d'abord la version mobile (one-page)
2. **Testez régulièrement** : Vérifiez votre intégration à chaque étape
3. **Utilisez les variables CSS** : Facilite les modifications ultérieures
4. **Optimisez les images** : Compression avant upload
5. **Commentez votre code** : Pour vous y retrouver plus tard

---

## 🆘 Besoin d'aide ?

Si vous rencontrez des difficultés :
1. Vérifiez la console du navigateur (F12)
2. Consultez le README.md du thème
3. Testez avec le thème par défaut de WordPress
4. Demandez de l'aide !

---

**Bonne intégration ! 🚀**
