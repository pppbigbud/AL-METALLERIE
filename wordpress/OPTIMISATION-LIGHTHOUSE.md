# Guide d'Optimisation Lighthouse - AL Métallerie

## Score actuel : ~90 | Objectif : 95+

---

## ✅ OPTIMISATIONS DÉJÀ APPLIQUÉES

1. **main.js minifié** → `main.min.js` (économie ~3 Ko)
2. **Google Fonts optimisé** → Seulement 4 poids au lieu de 18
3. **Préconnexion fonts.gstatic.com** ajoutée
4. **Preload image LCP** ajouté pour la page d'accueil
5. **fetchpriority="high"** sur l'image principale
6. **Format `<picture>` avec WebP** sur toutes les images

---

## 🔴 ACTION REQUISE : Créer les fichiers WebP manquants

### Images à convertir en WebP (URGENT)

Les fichiers suivants n'ont PAS de version WebP et pèsent très lourd :

| Fichier | Taille actuelle | Action requise |
|---------|-----------------|----------------|
| `pexels-kelly-2950108 3.png` | 937 KB | Créer WebP |
| `pexels-pavel-chernonogov-2381463 2.png` | 906 KB | Créer WebP |
| `pexels-pixabay-73833 1.png` | 1236 KB | Créer WebP |
| `pexels-tima-miroshnichenko-5846282 1.png` | **5991 KB** | Créer WebP |
| `hero-1.png` | 906 KB | Créer WebP |
| `hero-2.png` | **9156 KB** | Créer WebP |
| `hero-3.png` | **5991 KB** | Créer WebP |

### Comment créer les WebP

**Option 1 : Squoosh (gratuit, en ligne)**
1. Aller sur https://squoosh.app/
2. Glisser-déposer chaque image PNG
3. Choisir "WebP" comme format de sortie
4. Qualité : 80-85% (bon compromis qualité/taille)
5. Télécharger et placer dans le même dossier

**Option 2 : Plugin WordPress "Performance Lab"**
- Installer depuis WordPress Admin > Extensions
- Active la conversion automatique WebP

**Option 3 : Ligne de commande (si cwebp installé)**
```bash
cwebp -q 80 image.png -o image.webp
```

---

## 🟡 CSS inutilisé (14 Ko à économiser)

Le fichier `4a26ad6….css` (probablement `mega-menu.css` ou `components.css`) contient 14 Ko de CSS non utilisé sur la page d'accueil.

### Solutions :

1. **Charger le CSS conditionnellement** (déjà partiellement fait dans functions.php)
2. **Utiliser PurgeCSS** pour supprimer les règles inutilisées
3. **Critical CSS** : Inliner le CSS critique dans le `<head>`

---

## 🟡 JavaScript non minifié (3 Ko à économiser)

Le fichier `main.js` (5.5 Ko) peut être réduit à ~2.6 Ko avec minification.

### Solution immédiate :
Créer une version minifiée `main.min.js` et la charger en production.

---

## 🟡 Requêtes bloquant le rendu (8 fichiers CSS)

### Fichiers identifiés :
- `a9a8b44….css` (style principal)
- `220de4b….css`
- `2151225….css`
- `e44d2f4….css`
- `d7ad75d….css`
- `d603b95….css`
- `acf8e47….css`
- `4a26ad6….css`

### Solutions :

1. **Précharger le CSS critique** :
```html
<link rel="preload" href="style.css" as="style">
```

2. **Différer le CSS non-critique** :
```html
<link rel="stylesheet" href="non-critical.css" media="print" onload="this.media='all'">
```

---

## 🟢 Points déjà optimisés

✅ Logo avec `fetchpriority="high"`
✅ Images avec `loading="lazy"`
✅ Format `<picture>` avec WebP pour certaines images
✅ Compression GZIP configurée dans .htaccess
✅ Cache navigateur configuré (1 an pour assets)
✅ Emojis WordPress désactivés

---

## Actions immédiates recommandées

### 1. Convertir les images (priorité haute)
Économie estimée : **~15 Mo** de bande passante

### 2. Ajouter les images WebP manquantes dans le code

Modifier `section-services.php` ligne 88 :
```php
<picture>
    <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-tima-miroshnichenko-5846282 1.webp'); ?>" type="image/webp">
    <img src="..." loading="lazy">
</picture>
```

### 3. Optimiser le chargement des polices Google

Ajouter dans `functions.php` :
```php
// Préconnexion aux serveurs Google Fonts
function almetal_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'almetal_preconnect_fonts', 1);
```

### 4. Différer le chargement du CSS non-critique

Modifier le chargement des CSS dans `functions.php` pour utiliser `media="print"` avec `onload`.

---

## Outils de test

- **Lighthouse** : DevTools Chrome > Lighthouse
- **PageSpeed Insights** : https://pagespeed.web.dev/
- **WebPageTest** : https://www.webpagetest.org/
- **GTmetrix** : https://gtmetrix.com/

---

*Dernière mise à jour : Décembre 2024*
