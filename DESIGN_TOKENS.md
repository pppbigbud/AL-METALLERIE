# 🎨 Design Tokens - AL Metallerie

Ce fichier centralise tous les tokens de design (couleurs, typographies, espacements) extraits de Figma.

---

## 🎨 Palette de couleurs

### Couleurs principales

| Nom | Hex | Usage | Figma |
|-----|-----|-------|-------|
| Primary | `#2c3e50` | Couleur principale, header, titres | ⬜ À définir |
| Secondary | `#3498db` | Liens, boutons secondaires | ⬜ À définir |
| Accent | `#e74c3c` | CTA, éléments importants | ⬜ À définir |

### Couleurs de texte

| Nom | Hex | Usage | Figma |
|-----|-----|-------|-------|
| Text | `#333333` | Texte principal | ⬜ À définir |
| Text Light | `#666666` | Texte secondaire | ⬜ À définir |
| Text White | `#ffffff` | Texte sur fond sombre | ⬜ À définir |

### Couleurs de fond

| Nom | Hex | Usage | Figma |
|-----|-----|-------|-------|
| Background | `#ffffff` | Fond principal | ⬜ À définir |
| Background Alt | `#f8f9fa` | Fond alternatif | ⬜ À définir |
| Background Dark | `#2c3e50` | Fond sombre (footer) | ⬜ À définir |

### Couleurs d'état

| Nom | Hex | Usage | Figma |
|-----|-----|-------|-------|
| Success | `#27ae60` | Messages de succès | ⬜ À définir |
| Warning | `#f39c12` | Avertissements | ⬜ À définir |
| Error | `#e74c3c` | Erreurs | ⬜ À définir |
| Info | `#3498db` | Informations | ⬜ À définir |

---

## 📝 Typographie

### Polices

| Type | Police | Poids disponibles | Source |
|------|--------|-------------------|--------|
| Principale | `Roboto` | 400, 500, 700 | ⬜ Google Fonts / Figma |
| Titres | `Montserrat` | 600, 700 | ⬜ Google Fonts / Figma |

### Tailles de police

#### Mobile

| Élément | Taille | Line Height | Figma |
|---------|--------|-------------|-------|
| H1 | `2rem` (32px) | 1.2 | ⬜ À définir |
| H2 | `1.75rem` (28px) | 1.2 | ⬜ À définir |
| H3 | `1.5rem` (24px) | 1.2 | ⬜ À définir |
| Body | `1rem` (16px) | 1.6 | ⬜ À définir |
| Small | `0.875rem` (14px) | 1.4 | ⬜ À définir |

#### Desktop

| Élément | Taille | Line Height | Figma |
|---------|--------|-------------|-------|
| H1 | `2.5rem` (40px) | 1.2 | ⬜ À définir |
| H2 | `2rem` (32px) | 1.2 | ⬜ À définir |
| H3 | `1.75rem` (28px) | 1.2 | ⬜ À définir |
| Body | `1rem` (16px) | 1.8 | ⬜ À définir |
| Small | `0.875rem` (14px) | 1.4 | ⬜ À définir |

---

## 📏 Espacements

| Nom | Valeur | Usage | Figma |
|-----|--------|-------|-------|
| XS | `0.5rem` (8px) | Très petit espacement | ⬜ À définir |
| SM | `1rem` (16px) | Petit espacement | ⬜ À définir |
| MD | `2rem` (32px) | Espacement moyen | ⬜ À définir |
| LG | `3rem` (48px) | Grand espacement | ⬜ À définir |
| XL | `4rem` (64px) | Très grand espacement | ⬜ À définir |

---

## 🔲 Bordures & Ombres

### Radius

| Nom | Valeur | Usage | Figma |
|-----|--------|-------|-------|
| Small | `4px` | Boutons, inputs | ⬜ À définir |
| Medium | `8px` | Cards, images | ⬜ À définir |
| Large | `16px` | Sections | ⬜ À définir |
| Round | `50%` | Éléments ronds | ⬜ À définir |

### Ombres

| Nom | Valeur | Usage | Figma |
|-----|--------|-------|-------|
| Small | `0 2px 4px rgba(0,0,0,0.1)` | Léger relief | ⬜ À définir |
| Medium | `0 4px 12px rgba(0,0,0,0.15)` | Cards | ⬜ À définir |
| Large | `0 8px 24px rgba(0,0,0,0.2)` | Modales | ⬜ À définir |

---

## 🎬 Animations

| Nom | Durée | Easing | Usage |
|-----|-------|--------|-------|
| Fast | `0.2s` | ease | Hover, petites transitions |
| Normal | `0.3s` | ease | Transitions standard |
| Slow | `0.5s` | ease | Animations complexes |

---

## 📱 Breakpoints

| Nom | Valeur | Usage |
|-----|--------|-------|
| Mobile | `< 768px` | Smartphones |
| Tablet | `769px - 1024px` | Tablettes |
| Desktop | `> 1025px` | Ordinateurs |
| Wide | `> 1440px` | Grands écrans |

---

## 📋 Instructions d'utilisation

### 1. Remplir depuis Figma

1. Ouvrir votre maquette Figma
2. Pour chaque élément, noter les valeurs exactes
3. Cocher ✅ dans la colonne "Figma" une fois noté

### 2. Mettre à jour le CSS

Une fois tous les tokens notés, mettre à jour `style.css` :

```css
:root {
    /* Couleurs */
    --color-primary: #VOTRE_VALEUR;
    --color-secondary: #VOTRE_VALEUR;
    /* ... */
    
    /* Typographie */
    --font-primary: 'VOTRE_POLICE', sans-serif;
    --font-heading: 'VOTRE_POLICE', sans-serif;
    /* ... */
}
```

### 3. Utiliser dans le code

```css
.mon-element {
    color: var(--color-primary);
    font-family: var(--font-heading);
    padding: var(--spacing-md);
    border-radius: 8px;
}
```

---

## 🔄 Historique des modifications

| Date | Modification | Par |
|------|--------------|-----|
| 23/10/2025 | Création du fichier | BIGBUD |
| | | |

---

## 📝 Notes

- Ce fichier sert de référence unique pour tous les tokens de design
- Mettre à jour ce fichier à chaque modification de la maquette Figma
- Utiliser les variables CSS pour faciliter les modifications futures
