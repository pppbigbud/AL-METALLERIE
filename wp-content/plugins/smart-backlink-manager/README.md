# Smart Backlink Manager

Plugin WordPress pour la gestion avancée des backlinks et l'optimisation des liens internes.

## Fonctionnalités

- **Gestion des liens internes** : Ajoutez et suivez facilement les liens entre vos pages
- **Suivi des backlinks** : Surveillez l'état de vos backlinks et vérifiez automatiquement leur validité
- **Opportunités de backlinks** : Identifiez et suivez les opportunités d'obtenir de nouveaux backlinks
- **Dashboard complet** : Visualisez des statistiques détaillées et l'évolution de votre profil de liens
- **Import/Export CSV** : Gérez vos données en masse via des fichiers CSV
- **Intégration Gutenberg** : Bloc WordPress pour insérer facilement des liens internes
- **Automatisation** : Vérifications automatiques et tâches planifiées

## Configuration requise

- WordPress 5.0 ou supérieur
- PHP 7.4 ou supérieur
- MySQL 5.6 ou supérieur

## Installation

### Méthode 1 : Via FTP

1. Téléchargez le dossier `smart-backlink-manager`
2. Uploadez-le dans le dossier `wp-content/plugins/` de votre WordPress
3. Allez dans l'administration WordPress → Plugins
4. Activez "Smart Backlink Manager"

### Méthode 2 : Via l'administration WordPress

1. Téléchargez le fichier ZIP du plugin
2. Allez dans l'administration WordPress → Plugins → Ajouter
3. Cliquez sur "Mettre en ligne" et sélectionnez le fichier ZIP
4. Installez et activez le plugin

## Configuration initiale

1. Après activation, allez dans **Backlinks → Réglages**
2. Configurez les informations de base :
   - Nom du site
   - URL du site
   - Secteur d'activité
3. Ajoutez vos mots-clés personnalisés pour de meilleures suggestions
4. Activez les options d'automatisation si souhaité

## Utilisation

### Gestion des liens internes

1. Allez dans **Backlinks → Liens Internes**
2. Cliquez sur "Ajouter un lien" pour créer un nouveau lien interne
3. Sélectionnez la page source et la page cible
4. Optionnellement, personnalisez le texte d'ancre
5. Sauvegardez

### Suivi des backlinks

1. Allez dans **Backlinks → Backlinks**
2. Ajoutez manuellement vos backlinks existants
3. Utilisez "Tout vérifier" pour vérifier l'état de tous vos backlinks
4. Les backlinks sont automatiquement vérifiés chaque jour (si activé dans les réglages)

### Opportunités de backlinks

1. Allez dans **Backlinks → Opportunités**
2. Ajoutez des opportunités que vous identifiez
3. Suivez le statut de chaque opportunité (nouveau, à contacter, en cours, obtenu)
4. Utilisez les filtres pour organiser vos opportunités

### Import/Export

- **Export** : Cliquez sur "Exporter" dans n'importe quelle liste pour télécharger un CSV
- **Import** : Utilisez le formulaire d'import en bas des pages pour importer des données

Format CSV pour les liens internes :
```
URL source,URL cible,Texte d'ancre
```

Format CSV pour les backlinks :
```
URL source,URL cible,Texte d'ancre,Type
```

## Tables créées

Le plugin crée automatiquement 3 tables dans votre base de données MySQL :

- `wp_sbm_internal_links` : Stockage des liens internes
- `wp_sbm_backlinks` : Stockage des backlinks
- `wp_sbm_opportunities` : Stockage des opportunités

## Tâches planifiées (Cron)

Si activé dans les réglages, le plugin exécute automatiquement :

- **Vérification des backlinks** : Quotidienne
- **Recherche d'opportunités** : Hebdomadaire

## API REST

Le plugin expose une API REST pour les développeurs :

- `GET /wp-json/sbm/v1/internal-links` : Lister les liens internes
- `POST /wp-json/sbm/v1/internal-links` : Créer un lien interne
- `GET /wp-json/sbm/v1/backlinks` : Lister les backlinks
- `POST /wp-json/sbm/v1/backlinks` : Créer un backlink
- `POST /wp-json/sbm/v1/backlinks/{id}/check` : Vérifier un backlink
- `GET /wp-json/sbm/v1/opportunities` : Lister les opportunités
- `POST /wp-json/sbm/v1/opportunities` : Créer une opportunité
- `GET /wp-json/sbm/v1/stats` : Obtenir les statistiques

## Sécurité

- Toutes les requêtes AJAX sont protégées par des nonces WordPress
- Vérification des permissions utilisateur (manage_options requis)
- Échappement et validation de toutes les données entrantes
- Prévention des injections SQL avec $wpdb->prepare()

## Personnalisation

### Filtrer les types de posts

Pour ajouter des types de posts personnalisés dans les sélecteurs :

```php
add_filter('sbm_allowed_post_types', function($types) {
    $types[] = 'votre_custom_post_type';
    return $types;
});
```

### Personnaliser les textes

Utilisez les fichiers de traduction dans `languages/` ou le hook `gettext` WordPress.

## Support

Pour toute question ou problème :

1. Vérifiez les logs d'erreurs de WordPress
2. Désactivez temporairement les autres plugins pour identifier les conflits
3. Assurez-vous que votre configuration serveur respecte les prérequis

## Changelog

### 1.0.0
- Version initiale
- Gestion des liens internes
- Suivi des backlinks
- Gestion des opportunités
- Dashboard avec statistiques
- Import/Export CSV
- API REST
- Tâches automatisées

## Licence

Ce plugin est distribué sous licence GPL v2.
