# 📸 Guide d'ajout de photos dans les Réalisations

## ✨ Fonctionnalités du carrousel

Votre système de galerie dispose de :
- ✅ **Défilement automatique** (toutes les 4 secondes)
- ✅ **Miniatures cliquables** pour navigation rapide
- ✅ **Boutons précédent/suivant**
- ✅ **Navigation au clavier** (flèches gauche/droite)
- ✅ **Pause au survol** de la souris
- ✅ **Compteur** d'images (ex: 3 / 10)
- ✅ **Design responsive** (mobile/tablet/desktop)

---

## 📝 Comment ajouter des photos à une réalisation

### Méthode 1 : Via l'éditeur de contenu (RECOMMANDÉ)

1. **Aller dans Réalisations → Modifier** (ou Ajouter)

2. **Dans l'éditeur de contenu**, cliquer sur le bouton **"+"** pour ajouter un bloc

3. **Chercher "Galerie"** et cliquer dessus

4. **Uploader vos images** :
   - Cliquer sur "Uploader"
   - Sélectionner plusieurs images (Ctrl + clic ou Shift + clic)
   - Ou glisser-déposer les images

5. **Organiser les images** :
   - Glisser-déposer pour réorganiser
   - La première image sera affichée en premier dans le carrousel

6. **Publier ou Mettre à jour**

### Méthode 2 : Via la médiathèque

1. **Aller dans Réalisations → Modifier** une réalisation

2. **Cliquer sur "Ajouter un média"** dans l'éditeur

3. **Uploader des fichiers** ou sélectionner depuis la médiathèque

4. **Insérer dans l'article**

5. Les images seront automatiquement détectées et ajoutées au carrousel

---

## 🎯 Bonnes pratiques

### Taille et format des images

**Recommandations** :
- **Format** : JPG (pour photos) ou PNG (pour images avec transparence)
- **Dimensions** : 1920x1080px (Full HD) ou 1600x1200px
- **Ratio** : 16:9 ou 4:3 (éviter les formats trop étroits)
- **Poids** : Maximum 500KB par image
- **Qualité** : 80-85% (bon compromis qualité/poids)

### Optimisation des images

**Avant d'uploader**, optimisez vos images avec :
- **TinyPNG** : https://tinypng.com/ (gratuit, en ligne)
- **ImageOptim** : https://imageoptim.com/ (Mac)
- **RIOT** : https://riot-optimizer.com/ (Windows)

**Pourquoi optimiser ?**
- ✅ Chargement plus rapide du site
- ✅ Meilleure expérience utilisateur
- ✅ Meilleur référencement Google
- ✅ Économie de bande passante

### Nommage des fichiers

**Bonnes pratiques** :
- ✅ `portail-fer-forge-clermont-01.jpg`
- ✅ `garde-corps-escalier-moderne-02.jpg`
- ❌ `IMG_20250124_123456.jpg`
- ❌ `DSC_0001.jpg`

**Pourquoi ?**
- Meilleur pour le SEO
- Plus facile à retrouver
- Plus professionnel

---

## 🔧 Ordre d'affichage des images

### Dans le carrousel

L'ordre d'affichage est :
1. **Ordre de la galerie** (si vous utilisez le bloc Galerie)
2. **Ordre d'upload** (si vous uploadez directement)

### Modifier l'ordre

**Dans l'éditeur Gutenberg** :
1. Cliquer sur le bloc Galerie
2. Glisser-déposer les images pour les réorganiser

**Dans l'éditeur classique** :
1. Cliquer sur "Modifier la galerie"
2. Glisser-déposer les images

---

## 📱 Affichage responsive

Le carrousel s'adapte automatiquement :

### Desktop (> 768px)
- Hauteur : 500px
- Miniatures : 100x100px
- Grille : 6-8 miniatures par ligne

### Mobile (≤ 768px)
- Hauteur : 300px
- Miniatures : 80x80px
- Grille : 3-4 miniatures par ligne

---

## 🎨 Personnalisation

### Modifier la vitesse de défilement

Dans `assets/js/main.js`, ligne 319 :

```javascript
const slideDelay = 4000; // 4 secondes (4000ms)
```

Changez la valeur :
- `3000` = 3 secondes
- `5000` = 5 secondes
- `6000` = 6 secondes

### Modifier la hauteur du carrousel

Dans `assets/css/realisations.css`, ligne 325 :

```css
.gallery-main {
    height: 500px; /* Hauteur desktop */
}
```

Et ligne 612 pour mobile :

```css
.gallery-main {
    height: 300px; /* Hauteur mobile */
}
```

### Désactiver le défilement automatique

Dans `assets/js/main.js`, commentez la ligne 357 :

```javascript
// startInterval(); // Défilement automatique désactivé
```

---

## 🐛 Dépannage

### Les images ne s'affichent pas dans le carrousel

**Vérifier** :
1. Les images sont bien uploadées dans la réalisation
2. La réalisation est publiée (pas en brouillon)
3. Le cache du navigateur est vidé (Ctrl + Shift + R)

### Les miniatures ne sont pas cliquables

**Vérifier** :
1. jQuery est bien chargé (F12 → Console)
2. Pas d'erreurs JavaScript dans la console
3. Le fichier `main.js` est bien chargé

### Le carrousel ne défile pas automatiquement

**Vérifier** :
1. Il y a au moins 2 images
2. Le JavaScript est bien chargé
3. Pas d'erreurs dans la console

### Les images sont déformées

**Solution** :
- Utilisez des images avec le même ratio (16:9 ou 4:3)
- Ou recadrez vos images avant upload
- Le carrousel utilise `object-fit: contain` pour éviter les déformations

---

## 📊 Exemple d'utilisation

### Cas d'usage : Portail en fer forgé

1. **Créer la réalisation** :
   - Titre : "Portail en fer forgé - Clermont-Ferrand"
   - Type : Portails
   - Date : 2025-01-15
   - Lieu : Clermont-Ferrand

2. **Ajouter les photos** :
   - Photo 1 : Vue d'ensemble du portail fermé
   - Photo 2 : Détail des ornements
   - Photo 3 : Vue du portail ouvert
   - Photo 4 : Détail de la serrure
   - Photo 5 : Vue de nuit avec éclairage

3. **Résultat** :
   - Carrousel avec 5 images
   - Défilement automatique toutes les 4 secondes
   - 5 miniatures cliquables en dessous
   - Compteur "1 / 5", "2 / 5", etc.

---

## ✅ Checklist avant publication

- [ ] Images optimisées (< 500KB chacune)
- [ ] Noms de fichiers descriptifs
- [ ] Au moins 3-5 photos par réalisation
- [ ] Première image = meilleure vue d'ensemble
- [ ] Images dans le bon ordre
- [ ] Testé sur desktop et mobile
- [ ] Carrousel fonctionne correctement

---

## 🎯 Astuces pro

### Pour un rendu optimal

1. **Variez les angles** :
   - Vue d'ensemble
   - Détails techniques
   - Contexte (environnement)
   - Avant/après (si applicable)

2. **Qualité photo** :
   - Bonne lumière naturelle
   - Pas de flou
   - Cadrage soigné
   - Fond propre

3. **Nombre d'images** :
   - Minimum : 3 photos
   - Optimal : 5-8 photos
   - Maximum : 15 photos (pour ne pas surcharger)

4. **Storytelling** :
   - Racontez l'histoire du projet
   - Montrez le processus
   - Mettez en valeur le savoir-faire

---

## 📞 Support

Pour toute question :
- Voir `GUIDE_REALISATIONS.md` pour la gestion globale
- Voir `single-realisation.php` pour le code du carrousel
- Voir `assets/js/main.js` fonction `initGalleryCarousel()`

---

**Votre carrousel de photos est prêt ! Ajoutez vos plus belles réalisations.** 📸✨
