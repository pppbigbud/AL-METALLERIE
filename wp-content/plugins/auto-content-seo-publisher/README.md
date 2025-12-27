# Auto Content SEO Publisher

Plugin WordPress pour la génération automatique d'articles de blog optimisés SEO pour AL Métallerie & Soudure.

## Description

Auto Content SEO Publisher est un plugin WordPress complet qui génère automatiquement des articles de blog optimisés pour le référencement. Conçu spécifiquement pour AL Métallerie & Soudure, il utilise des templates intelligents pour créer du contenu unique et pertinent sur les thématiques de la métallerie, serrurerie et soudure.

## Fonctionnalités principales

### 📝 Génération automatique
- Articles générés automatiquement selon une planification (hebdomadaire, bi-hebdomadaire, mensuelle)
- 7 types d'articles : guides pratiques, tendances, tutoriels, études de cas, FAQ, comparatifs, inspirations
- Contenu unique grâce à des templates intelligents et variables dynamiques
- Longueur configurable (1000-1500 mots par défaut)

### 🎯 Optimisation SEO
- Analyse et calcul du score SEO pour chaque article
- Optimisation automatique de la densité de mots-clés (1-2%)
- Structure H1-H6 correcte
- Méta descriptions optimisées
- URLs SEO-friendly
- Fil d'Ariane intégré
- Balises Schema.org

### 🖼️ Gestion des images
- Import depuis Unsplash (gratuit)
- Génération d'images de remplacement
- Optimisation des balises ALT
- Dimensions configurables

### 📊 Tableau de bord
- Statistiques détaillées des générations
- Suivi des scores SEO
- Historique complet
- Actions rapides

### ⚙️ Configuration avancée
- Planification flexible
- Types de contenu sélectionnables
- Mots-clés personnalisés
- Localisations ciblées
- Notifications email

## Installation

1. Téléchargez le dossier `auto-content-seo-publisher`
2. Uploadez-le dans `wp-content/plugins/`
3. Activez le plugin depuis l'administration WordPress
4. Configurez les réglages dans `Auto Content SEO Publisher > Réglages`

## Configuration

### Planification
1. Allez dans `Auto Content SEO Publisher > Réglages`
2. Onglet "Planification"
3. Activez la génération automatique
4. Choisissez la fréquence, le jour et l'heure
5. Sélectionnez le statut de publication (recommandé : Brouillon)

### SEO
1. Onglet "SEO"
2. Configurez les longueurs d'articles
3. Définissez le score SEO minimum
4. Ajoutez vos mots-clés principaux
5. Spécifiez les localisations à cibler

### Contenu
1. Onglet "Contenu"
2. Choisissez le ton (professionnel, décontracté, mixte)
3. Sélectionnez les types d'articles à générer
4. Personnalisez l'auteur et la signature

### Images
1. Onglet "Images"
2. Choisissez la source (Unsplash, réalisé, généré)
3. Configurez les dimensions
4. Importez des images si nécessaire

## Utilisation

### Génération manuelle
1. Dans le tableau de bord, cliquez sur "Générer un article maintenant"
2. Attendez la fin de la génération
3. L'article apparaîtra dans la liste

### Génération automatique
Le plugin génère automatiquement les articles selon la planification configurée. Vous recevez une email après chaque génération (si activé).

### Consultation des articles
- Les articles générés apparaissent dans "Articles" comme les articles WordPress normaux
- Vous pouvez les éditer avant publication
- Le score SEO est affiché dans l'historique

## Templates d'articles

Le plugin inclut 7 types de templates :

### Guide pratique
Structure : Problème → Solution → Étapes → Conseils → CTA

### Tendance
Structure : Actualité → Analyse → Implications → Recommandations

### Tutoriel
Structure : Objectif → Prérequis → Étapes détaillées → Erreurs à éviter

### Étude de cas
Structure : Contexte → Cahier des charges → Solution → Résultat → Témoignage

### FAQ
Structure : Questions fréquentes → Réponses détaillées → Points bonus

### Comparatif
Structure : Présentation → Tableau comparatif → Avantages/Inconvénients → Recommandation

### Inspiration
Structure : Liste d'idées → Explications → Adaptation → Appel à l'action

## Variables disponibles

Les templates utilisent des variables dynamiques :

- `{COMPANY}` : AL Métallerie & Soudure
- `{LOCATION}` : Ville aléatoire (Thiers, Clermont-Ferrand, etc.)
- `{KEYWORD}` : Mot-clé principal
- `{KEYWORD_PLURAL}` : Mot-clé au pluriel
- `{SERVICE}` : Service aléatoire
- `{MATERIAL}` : Matériau aléatoire
- `{PHONE}` : 06 73 33 35 32
- `{EMAIL}` : contact@al-metallerie.fr
- `{YEAR}` : Année en cours
- `{SEASON}` : Saison actuelle
- `{REALISATION}` : Réalisation aléatoire

## Données créées

### Tables WordPress

Le plugin crée 4 tables dans la base de données :

- `wp_acsp_articles` : Articles générés
- `wp_acsp_keywords` : Mots-clés utilisés
- `wp_acsp_topics` : Sujets traités
- `wp_acsp_images` : Images importées

### Options WordPress

Le plugin sauvegarde ses réglages dans `wp_options` avec le préfixe `acsp_`.

## Personnalisation

### Ajouter un nouveau template

1. Créez une nouvelle méthode dans `includes/class-article-templates.php`
2. Ajoutez le type dans `includes/class-content-generator.php`
3. Mettez à jour la liste dans les réglages

### Modifier les templates

Éditez les fichiers dans `includes/class-article-templates.php` pour personnaliser le contenu généré.

### Ajouter des variables

1. Ajoutez la variable dans `includes/class-content-generator.php`
2. Utilisez-la dans les templates avec `{NOM_VARIABLE}`

## Sécurité

- Tous les appels AJAX utilisent des nonces WordPress
- Les données sont sanitizées avant insertion
- Les permissions sont vérifiées
- Pas d'exécution de code externe

## Performance

- Génération en arrière-plan (WP Cron)
- Optimisation des images
- Nettoyage automatique des anciens articles
- Cache des requêtes

## Support

Pour toute question ou problème :

- Email : contact@al-metallerie.fr
- Téléphone : 06 73 33 35 32
- Site : https://al-metallerie.fr

## Changelog

### Version 1.0.0 (2024-12-27)
- Version initiale
- Génération automatique d'articles
- 7 types de templates
- Optimisation SEO intégrée
- Gestion des images Unsplash
- Tableau de bord complet
- Configuration avancée
- Historique détaillé
- Export CSV/JSON

## License

Ce plugin est sous licence GPL v2 ou plus.

## Crédits

Développé par AL Métallerie & Soudure pour al-metallerie.fr

---

**Note importante** : Ce plugin utilise une approche basée sur des templates pré-écrits pour générer du contenu unique. Il n'utilise pas d'API payante et fonctionne entièrement localement sur votre installation WordPress.
