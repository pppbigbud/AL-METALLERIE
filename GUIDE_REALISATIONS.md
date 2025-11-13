# 📚 Guide des Réalisations - AL Métallerie

## ✅ Ce qui a été créé

### 1. Custom Post Type "Réalisations"
Un type de contenu personnalisé pour gérer les projets de métallerie.

**Champs disponibles** :
- Titre
- Description (contenu)
- Extrait (résumé court)
- Image principale
- Galerie d'images
- Client (optionnel)
- Date de réalisation
- Lieu
- Durée du projet
- ID Facebook (pour l'import)

### 2. Taxonomie "Type de réalisation"
Catégories pour classer les projets :
- Portails
- Garde-corps
- Escaliers
- Rampes
- Grilles
- Pergolas
- Mobilier métallique
- Ferronnerie d'art
- Serrurerie
- Autres

### 3. Templates d'affichage
- **`archive-realisation.php`** : Page listant toutes les réalisations avec filtres
- **`single-realisation.php`** : Page détaillée d'une réalisation

### 4. Système d'import Facebook
Script automatique pour importer les publications Facebook en réalisations.

---

## 📝 A. Ajouter une réalisation manuellement

### Dans l'admin WordPress :

1. **Aller dans Réalisations → Ajouter**
2. **Remplir les informations** :
   - **Titre** : Nom du projet (ex: "Portail en fer forgé - Clermont-Ferrand")
   - **Contenu** : Description détaillée du projet
   - **Extrait** : Résumé court (2-3 phrases)
   - **Image principale** : Photo principale du projet
   - **Type** : Sélectionner le type (Portail, Garde-corps, etc.)

3. **Remplir les détails** (encadré "Détails de la réalisation") :
   - Client (optionnel)
   - Date de réalisation
   - Lieu
   - Durée du projet

4. **Ajouter une galerie** (optionnel) :
   - Dans l'éditeur, cliquer sur "Ajouter un bloc"
   - Choisir "Galerie"
   - Uploader plusieurs images

5. **Publier** ou **Enregistrer en brouillon**

---

## 🔄 B. Importer depuis Facebook (AUTOMATIQUE)

### Étape 1 : Export Facebook (par votre client)

Votre client doit :

1. **Se connecter à Facebook** en tant qu'admin de la page
2. **Aller dans** : Paramètres → Vos informations Facebook
3. **Cliquer sur** : "Télécharger les informations de la page"
4. **Sélectionner** :
   - ✅ Publications
   - ✅ Photos
   - Format : **JSON** (important !)
   - Plage de dates : Toutes
5. **Créer le fichier** et attendre l'email de Facebook
6. **Télécharger le fichier ZIP** et l'extraire
7. **Vous envoyer le fichier JSON**

### Étape 2 : Import dans WordPress

1. **Aller dans** : Réalisations → Import Facebook
2. **Uploader le fichier JSON**
3. **Choisir les options** :
   - Type par défaut (ex: "Autres")
   - ✅ Importer les images
4. **Cliquer sur "Importer les publications"**
5. **Attendre** (peut prendre plusieurs minutes)

### Étape 3 : Révision

Les publications importées sont en **brouillon** :

1. **Aller dans Réalisations → Tous**
2. **Pour chaque réalisation** :
   - Vérifier le titre
   - Vérifier le contenu
   - Attribuer le bon type
   - Compléter les détails (lieu, durée, etc.)
   - **Publier** quand c'est OK

---

## 🎨 C. Affichage sur le site

### Page d'archive : `/realisations/`

Affiche toutes les réalisations avec :
- Grille responsive
- Filtres par type
- Image + titre + extrait
- Pagination

### Page individuelle : `/realisations/nom-du-projet/`

Affiche une réalisation complète avec :
- Image hero
- Contenu détaillé
- Galerie photos
- Informations (date, lieu, durée)
- Navigation vers autres projets
- Bouton CTA "Un projet similaire ?"

---

## 🔧 D. Personnalisation

### Modifier les types de réalisation

**Admin WordPress** → Réalisations → Types

- Ajouter de nouveaux types
- Renommer les types existants
- Supprimer les types non utilisés

### Modifier l'ordre d'affichage

Par défaut : du plus récent au plus ancien

Pour changer, éditer `archive-realisation.php` ligne ~40 :

```php
$args = array(
    'post_type' => 'realisation',
    'orderby' => 'date',  // ou 'title', 'rand', 'meta_value'
    'order' => 'DESC',    // ou 'ASC'
);
```

### Modifier le nombre de réalisations par page

**Admin WordPress** → Réglages → Lecture → "Les pages du site affichent au maximum"

Ou dans `functions.php` :

```php
function almetal_realisations_per_page($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('realisation')) {
        $query->set('posts_per_page', 12); // Nombre souhaité
    }
}
add_action('pre_get_posts', 'almetal_realisations_per_page');
```

---

## 📊 E. Structure JSON Facebook (référence)

Le fichier JSON Facebook a cette structure :

```json
{
  "posts": [
    {
      "id": "123456789",
      "timestamp": 1234567890,
      "title": "Titre de la publication",
      "post": "Contenu de la publication...",
      "attachments": [
        {
          "data": [
            {
              "media": {
                "uri": "https://facebook.com/photo.jpg"
              }
            }
          ]
        }
      ]
    }
  ]
}
```

Le script extrait automatiquement :
- **ID** → Stocké pour éviter les doublons
- **Timestamp** → Date de publication
- **Title** → Titre de la réalisation
- **Post** → Contenu
- **Attachments** → Images

---

## 🐛 F. Dépannage

### L'import ne fonctionne pas

**Vérifier** :
1. Le fichier est bien en format JSON (pas HTML)
2. Le fichier n'est pas corrompu
3. La taille du fichier (< 50MB recommandé)
4. Les permissions PHP (upload_max_filesize, post_max_size)

### Les images ne s'importent pas

**Causes possibles** :
1. URLs Facebook expirées (export trop ancien)
2. Limite de mémoire PHP
3. Timeout PHP

**Solution** : Décocher "Importer les images" et les ajouter manuellement après

### Les réalisations n'apparaissent pas

**Vérifier** :
1. Elles sont bien **publiées** (pas en brouillon)
2. Le permalien `/realisations/` existe (Réglages → Permaliens → Enregistrer)
3. Le thème est bien activé

---

## 📱 G. Responsive

Les templates sont entièrement responsives :

- **Desktop** : Grille 3 colonnes
- **Tablet** : Grille 2 colonnes
- **Mobile** : 1 colonne

Les filtres s'adaptent automatiquement.

---

## 🎯 H. Prochaines améliorations possibles

- [ ] Lightbox pour les galeries
- [ ] Filtres AJAX (sans rechargement)
- [ ] Recherche par mot-clé
- [ ] Tri personnalisé (popularité, date, etc.)
- [ ] Partage sur réseaux sociaux
- [ ] Formulaire de demande de devis intégré
- [ ] Champs personnalisés supplémentaires (matériaux, budget, etc.)

---

## 📞 Support

Pour toute question ou modification :
- Consulter la documentation WordPress
- Voir le fichier `inc/custom-post-types.php` pour la structure
- Voir `inc/facebook-importer.php` pour l'import

---

## ✅ Checklist de mise en route

- [ ] Aller dans Réalisations (nouveau menu dans l'admin)
- [ ] Vérifier que les types par défaut sont créés
- [ ] Demander l'export Facebook au client
- [ ] Importer les publications via "Import Facebook"
- [ ] Réviser et publier les réalisations importées
- [ ] Tester l'affichage sur `/realisations/`
- [ ] Ajouter le lien dans le menu de navigation
- [ ] Personnaliser les styles si nécessaire

---

**Tout est prêt ! Vous pouvez maintenant gérer vos réalisations facilement.** 🎉
