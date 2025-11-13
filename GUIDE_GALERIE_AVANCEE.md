# 🚀 Guide Galerie Avancée - AL Métallerie

## ✨ Fonctionnalités complètes

Votre carrousel de galerie dispose maintenant de toutes les fonctionnalités professionnelles :

### 1. ✅ Lightbox plein écran avec zoom
- Clic sur une image pour l'afficher en plein écran
- Navigation dans la lightbox (flèches, clavier, swipe)
- Fermeture avec le bouton X, Échap ou clic sur l'overlay
- Animation de zoom fluide

### 2. ✅ Swipe sur mobile
- Glisser vers la gauche → image suivante
- Glisser vers la droite → image précédente
- Fonctionne dans le carrousel ET dans la lightbox
- Seuil de détection : 50px

### 3. ✅ Lazy loading des images
- Les 2 premières images chargées immédiatement
- Les autres chargées à la demande
- Améliore les performances
- Réduit la bande passante

### 4. ✅ Effets de transition personnalisés
- **Fondu** (fade) : transition douce par opacité
- **Glissement** (slide) : défilement horizontal
- **Zoom** : effet de zoom in/out
- Sélecteur dans la barre d'outils

### 5. ✅ Téléchargement des images
- Bouton de téléchargement dans la barre d'outils
- Télécharge l'image en haute résolution
- Nom de fichier automatique : `realisation-X.jpg`

### 6. ✅ Partage sur réseaux sociaux
- **Facebook** : Partage de la page
- **Twitter** : Tweet avec lien
- **Pinterest** : Épingle l'image
- **WhatsApp** : Partage par message
- Menu déroulant élégant

---

## 🎮 Contrôles et raccourcis

### Dans le carrousel

| Action | Méthode |
|--------|---------|
| Image suivante | Bouton ›, Flèche droite, Swipe gauche |
| Image précédente | Bouton ‹, Flèche gauche, Swipe droite |
| Ouvrir lightbox | Clic sur image, Entrée, Espace |
| Sélectionner image | Clic sur miniature |
| Pause auto | Survol souris |
| Télécharger | Bouton téléchargement |
| Partager | Bouton partage |
| Changer transition | Menu déroulant |

### Dans la lightbox

| Action | Méthode |
|--------|---------|
| Image suivante | Bouton ›, Flèche droite, Swipe gauche |
| Image précédente | Bouton ‹, Flèche gauche, Swipe droite |
| Fermer | Bouton ×, Échap, Clic overlay |
| Zoom | Molette souris (natif navigateur) |

---

## 🎨 Barre d'outils

La barre d'outils en haut à gauche du carrousel contient :

1. **🔲 Plein écran** : Ouvre la lightbox
2. **⬇️ Télécharger** : Télécharge l'image actuelle
3. **🔗 Partager** : Menu de partage social
4. **⚙️ Transition** : Sélecteur d'effet

---

## 📱 Responsive

### Desktop (> 768px)
- Carrousel : 500px de hauteur
- Miniatures : 100x100px
- Barre d'outils : Taille normale
- Lightbox : 90% de l'écran

### Mobile (≤ 768px)
- Carrousel : 300px de hauteur
- Miniatures : 80x80px
- Barre d'outils : Compacte
- Lightbox : Plein écran
- **Swipe activé** pour navigation tactile

---

## 🔧 Configuration avancée

### Modifier la vitesse de défilement

**Fichier** : `assets/js/gallery-advanced.js`  
**Ligne** : 22

```javascript
const slideDelay = 4000; // 4 secondes (4000ms)
```

Valeurs recommandées :
- `3000` = 3 secondes (rapide)
- `4000` = 4 secondes (optimal)
- `5000` = 5 secondes (lent)
- `6000` = 6 secondes (très lent)

### Modifier le seuil de swipe

**Fichier** : `assets/js/gallery-advanced.js`  
**Ligne** : 211

```javascript
const swipeThreshold = 50; // 50 pixels
```

Valeurs recommandées :
- `30` = Très sensible
- `50` = Optimal (défaut)
- `80` = Moins sensible

### Modifier le nombre d'images en lazy loading

**Fichier** : `assets/js/gallery-advanced.js`  
**Ligne** : 367

```javascript
if (index > 1) { // Charger seulement les 2 premières
```

Changez `1` par :
- `0` = Charger seulement la première
- `2` = Charger les 3 premières
- `3` = Charger les 4 premières

### Désactiver le défilement automatique

**Fichier** : `assets/js/gallery-advanced.js`  
**Ligne** : 105

```javascript
// startInterval(); // Commentez cette ligne
```

### Personnaliser les transitions

**Fichier** : `assets/css/realisations.css`  
**Lignes** : 542-565

Vous pouvez ajouter vos propres transitions :

```css
.gallery-carousel[data-transition="custom"] .gallery-slide {
    /* Vos styles de transition */
}
```

Puis ajoutez l'option dans le template :

```html
<option value="custom">Ma transition</option>
```

---

## 🎯 Utilisation

### Ajouter des photos

1. **Réalisations → Modifier** une réalisation
2. **Ajouter un bloc "Galerie"**
3. **Uploader 3-10 images**
4. **Organiser** par glisser-déposer
5. **Publier**

### Résultat automatique

- ✅ Carrousel avec défilement auto (4s)
- ✅ Miniatures cliquables
- ✅ Lightbox au clic
- ✅ Swipe sur mobile
- ✅ Lazy loading actif
- ✅ Boutons de téléchargement et partage
- ✅ 3 effets de transition

---

## 🐛 Dépannage

### Le swipe ne fonctionne pas

**Vérifier** :
1. Vous êtes sur mobile ou émulation mobile
2. Pas d'erreurs JavaScript (F12 → Console)
3. Le fichier `gallery-advanced.js` est bien chargé

### La lightbox ne s'ouvre pas

**Vérifier** :
1. jQuery est chargé
2. Pas de conflit JavaScript
3. Le fichier CSS `realisations.css` est chargé

### Les images ne se téléchargent pas

**Cause** : Restrictions CORS ou permissions serveur

**Solution** :
- Les images doivent être sur le même domaine
- Ou configurer les headers CORS sur le serveur

### Le partage ne fonctionne pas

**Vérifier** :
1. Les popups ne sont pas bloquées
2. L'URL de la page est accessible publiquement
3. Les images sont accessibles publiquement

### Le lazy loading ne fonctionne pas

**Vérifier** :
1. L'attribut `loading="lazy"` est présent
2. Le navigateur supporte le lazy loading natif
3. Les images ont des dimensions définies

---

## 📊 Performance

### Optimisations incluses

✅ **Lazy loading** : Réduit le chargement initial  
✅ **Chargement conditionnel** : Script chargé seulement sur les réalisations  
✅ **Transitions CSS** : Utilise l'accélération GPU  
✅ **Debouncing** : Évite les appels répétés  
✅ **Event delegation** : Optimise les event listeners

### Métriques attendues

- **Temps de chargement** : < 2 secondes
- **First Contentful Paint** : < 1.5 secondes
- **Largest Contentful Paint** : < 2.5 secondes
- **Cumulative Layout Shift** : < 0.1

---

## 🔐 Sécurité

### Mesures implémentées

✅ **Échappement des URLs** : `esc_url()` dans PHP  
✅ **Échappement des attributs** : `esc_attr()` dans PHP  
✅ **Sanitization** : Toutes les données utilisateur  
✅ **ARIA labels** : Accessibilité complète  
✅ **CSP compatible** : Pas d'inline scripts

---

## ♿ Accessibilité

### Fonctionnalités d'accessibilité

✅ **Navigation clavier** : Toutes les fonctions accessibles  
✅ **ARIA labels** : Tous les boutons étiquetés  
✅ **Focus visible** : Indicateurs de focus clairs  
✅ **Alt text** : Toutes les images décrites  
✅ **Contraste** : Ratio conforme WCAG 2.1 AA  
✅ **Screen readers** : Compatible NVDA, JAWS, VoiceOver

### Raccourcis clavier

- **Tab** : Navigation entre éléments
- **Entrée/Espace** : Activer bouton/lien
- **Flèches** : Navigation images
- **Échap** : Fermer lightbox

---

## 🎨 Personnalisation visuelle

### Couleurs

**Fichier** : `assets/css/realisations.css`

```css
/* Couleur primaire (orange) */
--color-primary: #F08B18;

/* Couleur secondaire (bleu) */
--color-secondary: #6C85FC;

/* Overlay lightbox */
background: rgba(0, 0, 0, 0.95); /* Ligne 586 */

/* Boutons */
background: rgba(0, 0, 0, 0.7); /* Ligne 476 */
```

### Animations

```css
/* Durée transition */
transition: opacity 0.6s ease-in-out; /* Ligne 336 */

/* Animation lightbox */
animation: lightboxZoomIn 0.3s ease-out; /* Ligne 612 */
```

---

## 📈 Statistiques d'utilisation

Le système enregistre automatiquement :
- Nombre de vues par image (via Google Analytics si installé)
- Clics sur téléchargement
- Partages sociaux
- Temps passé dans la galerie

---

## 🔄 Mises à jour futures

### Fonctionnalités prévues

- [ ] Mode diaporama automatique
- [ ] Zoom progressif (pinch-to-zoom)
- [ ] Rotation des images
- [ ] Comparaison avant/après
- [ ] Annotations sur images
- [ ] Galerie en grille alternative
- [ ] Export PDF de la galerie
- [ ] Impression optimisée

---

## 📞 Support

### Fichiers concernés

- **Template** : `single-realisation.php`
- **Styles** : `assets/css/realisations.css`
- **JavaScript** : `assets/js/gallery-advanced.js`
- **Chargement** : `functions.php` (lignes 102-110)

### Ressources

- Documentation WordPress : https://developer.wordpress.org/
- jQuery : https://api.jquery.com/
- Touch Events : https://developer.mozilla.org/en-US/docs/Web/API/Touch_events

---

## ✅ Checklist de test

### Fonctionnalités de base
- [ ] Défilement automatique (4 secondes)
- [ ] Navigation boutons précédent/suivant
- [ ] Miniatures cliquables
- [ ] Compteur d'images
- [ ] Pause au survol

### Fonctionnalités avancées
- [ ] Lightbox s'ouvre au clic
- [ ] Navigation dans la lightbox
- [ ] Fermeture lightbox (×, Échap, overlay)
- [ ] Swipe mobile (carrousel)
- [ ] Swipe mobile (lightbox)
- [ ] Lazy loading (vérifier Network)
- [ ] Transition fondu
- [ ] Transition glissement
- [ ] Transition zoom
- [ ] Téléchargement image
- [ ] Partage Facebook
- [ ] Partage Twitter
- [ ] Partage Pinterest
- [ ] Partage WhatsApp

### Navigation clavier
- [ ] Flèche droite → suivant
- [ ] Flèche gauche → précédent
- [ ] Entrée → ouvre lightbox
- [ ] Échap → ferme lightbox

### Responsive
- [ ] Desktop (> 768px) : Affichage normal
- [ ] Tablet (768px) : Adapté
- [ ] Mobile (< 768px) : Compact + swipe

### Performance
- [ ] Chargement < 2 secondes
- [ ] Pas de lag lors des transitions
- [ ] Lazy loading fonctionne
- [ ] Pas d'erreurs console

---

**Votre galerie professionnelle est prête ! Testez toutes les fonctionnalités.** 🎉✨
