# Audit SEO des Images - AL Métallerie

## Résumé

| Catégorie | Statut | Action |
|-----------|--------|--------|
| Attributs alt | ⚠️ Partiellement OK | Améliorer certains |
| Dimensions width/height | ❌ Manquantes | Ajouter partout |
| Lazy loading | ✅ OK | Déjà implémenté |
| Noms de fichiers | ⚠️ À optimiser | Renommer |

---

## 1. Images Statiques du Thème

### Gallery (Section Présentation & Services)

| Fichier actuel | Nom optimisé proposé | Alt actuel | Alt optimisé | Dimensions |
|----------------|---------------------|------------|--------------|------------|
| `pexels-kelly-2950108 1.png` | `soudeur-metallerie-thiers.png` | ✅ "Soudeur professionnel AL-Metallerie en action à Thiers" | OK | 300x400 |
| `pexels-rik-schots-11624248 2.png` | `travaux-metallerie-precision-thiers.png` | ✅ "Travaux de métallerie de précision à Thiers, Puy-de-Dôme" | OK | 300x400 |
| `pexels-tima-miroshnichenko-5846282 1.png` | `formation-soudure-professionnels.png` | ✅ "Formation métallerie pour professionnels à Thiers" | OK | 400x300 |
| `pexels-arthur-krijgsman-6036670 2.png` | `atelier-metallerie-thiers.png` | ❌ Non utilisé | À définir | - |
| `pexels-pavel-chernonogov-2381463 2.png` | `soudure-arc-metallerie.png` | ❌ Non utilisé | À définir | - |
| `pexels-pixabay-73833 1.png` | `fer-forge-artisanal.png` | ❌ Non utilisé | À définir | - |

### Hero (Slideshow)

| Fichier actuel | Nom optimisé proposé | Dimensions recommandées |
|----------------|---------------------|------------------------|
| `hero-1.png` | `portail-metallerie-thiers-hero.png` | 1920x1080 |
| `hero-2.png` | `garde-corps-acier-thiers-hero.png` | 1920x1080 |
| `hero-3.png` | `escalier-metal-thiers-hero.png` | 1920x1080 |

### Logo

| Fichier | Alt recommandé | Dimensions |
|---------|---------------|------------|
| `logo.png` / `logo.webp` | "AL Métallerie - Métallier Ferronnier à Thiers (63)" | 200x80 |

---

## 2. Images Dynamiques (WordPress)

### Réalisations (CPT)
- **Source** : Images à la une WordPress
- **Alt actuel** : Titre du post (`get_the_title()`)
- **Amélioration** : Utiliser le champ alt personnalisé de la médiathèque

### Formations (Cards dynamiques)
- **Source** : Champ personnalisé `image` + `image_alt`
- **Statut** : ✅ Déjà optimisé avec alt personnalisé

---

## 3. Corrections à Implémenter

### Priorité 1 : Ajouter width/height

**Fichiers à modifier :**
- `template-parts/section-presentation.php`
- `template-parts/mobile-presentation.php`
- `template-parts/section-services.php`
- `template-parts/section-actualites.php`
- `template-parts/mobile-actualites.php`
- `template-parts/section-formations.php`
- `template-parts/mobile-onepage.php`
- `template-parts/single-realisation-mobile.php`

### Priorité 2 : Améliorer les alt des réalisations

**Fichier** : `functions.php` (AJAX handler)
**Action** : Générer des alt descriptifs avec type + lieu

### Priorité 3 : Renommer les fichiers

**Action** : Renommer les fichiers avec des noms SEO-friendly
- Remplacer les espaces par des tirets
- Utiliser des mots-clés descriptifs
- Format : `type-materiau-lieu.extension`

---

## 4. Code Optimisé - Exemples

### Image statique optimisée
```php
<picture>
    <source srcset="soudeur-metallerie-thiers.webp" type="image/webp">
    <img src="soudeur-metallerie-thiers.png" 
         alt="Soudeur professionnel AL Métallerie en action dans l'atelier de Thiers"
         width="300" 
         height="400" 
         loading="lazy"
         decoding="async">
</picture>
```

### Image dynamique (réalisation) optimisée
```php
<img src="<?php echo esc_url($thumbnail_url); ?>" 
     alt="<?php echo esc_attr($type_realisation . ' à ' . $lieu . ' - AL Métallerie Thiers'); ?>"
     width="400" 
     height="300" 
     loading="lazy"
     decoding="async">
```

---

## 5. Checklist d'Implémentation

- [ ] Ajouter width/height aux images de présentation
- [ ] Ajouter width/height aux images de services
- [ ] Ajouter width/height aux cards de formations
- [ ] Ajouter width/height aux réalisations
- [ ] Améliorer les alt des réalisations avec type + lieu
- [ ] Renommer les fichiers d'images (optionnel)
- [ ] Ajouter decoding="async" pour performance
- [ ] Vérifier fetchpriority="high" sur les images above-the-fold

---

*Généré le : 07/12/2024*
*Par : Cascade AI*
