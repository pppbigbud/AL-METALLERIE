# City Pages Generator - Plugin WordPress

Plugin WordPress professionnel pour générer automatiquement des pages optimisées SEO local pour AL Métallerie.

## Description

Ce plugin permet de créer des pages uniques pour différentes villes de votre zone d'intervention, optimisées pour le référencement local (SEO local).

## Fonctionnalités

### Interface d'administration

- **Tableau de bord** : Vue d'ensemble avec statistiques
- **Ajouter une ville** : Formulaire complet pour créer une nouvelle page
- **Toutes les villes** : Liste avec actions (modifier, regénérer, supprimer)
- **Import/Export** : Gestion CSV pour création en masse
- **Paramètres** : Configuration globale du plugin

### Génération de contenu

- **Variations automatiques** : 4 variations de contenu pour éviter le duplicate content
- **Sections configurables** :
  - Introduction SEO
  - Services proposés
  - Réalisations locales
  - Pourquoi nous choisir
  - Zone d'intervention
  - Contact avec formulaire
  - FAQ locale

### SEO

- **Meta tags** automatiques (title, description, Open Graph)
- **Schema.org** LocalBusiness
- **Fil d'Ariane** avec markup structuré
- **Balises geo** pour le référencement local

## Installation

1. Téléchargez le plugin
2. Uploadez le dossier `city-pages-generator` dans `/wp-content/plugins/`
3. Activez le plugin dans WordPress
4. Allez dans **Pages Ville > Paramètres** pour configurer

## Configuration

### Paramètres de l'entreprise

- Nom de l'entreprise
- Adresse de l'atelier
- Téléphone
- Email
- Description

### Services

Activez/désactivez les services affichés sur les pages :
- Portails sur mesure
- Garde-corps et rambardes
- Escaliers métalliques
- Grilles de sécurité
- Pergolas et structures
- Verrières d'intérieur
- Ferronnerie d'art
- Mobilier métallique

### Sections

Activez/désactivez les sections de chaque page :
- Introduction
- Services
- Réalisations
- Pourquoi nous choisir
- Zone d'intervention
- Contact
- FAQ

## Utilisation

### Créer une page ville

1. Allez dans **Pages Ville > Ajouter une ville**
2. Remplissez les informations :
   - Nom de la ville
   - Code postal
   - Département
   - Priorité (1-3)
   - Distance depuis l'atelier
   - Temps de trajet
   - Spécificités locales
   - Communes à proximité
3. Choisissez le statut (brouillon ou publié)
4. Cliquez sur **Générer la page**

### Import CSV

Format du fichier CSV (séparateur : point-virgule) :

```
city_name;postal_code;department;priority;distance_km;travel_time;local_specifics;nearby_cities
Clermont-Ferrand;63000;Puy-de-Dôme;1;25;25 minutes;quartiers de Chamalières, Royat;Chamalières,Royat,Beaumont
Thiers;63300;Puy-de-Dôme;2;15;15 minutes;cité coutelière;Peschadoires,Celles-sur-Durolle
```

### Shortcodes

```php
// Fil d'Ariane
[cpg_breadcrumb]

// Réalisations de la ville
[cpg_city_realisations city="Clermont-Ferrand" count="6" columns="3"]

// Carte Google Maps
[cpg_city_map city="Clermont-Ferrand" height="400px"]

// Formulaire de contact
[cpg_contact_form city="Clermont-Ferrand"]
```

## Hooks disponibles

### Filtres

```php
// Modifier le contenu généré
add_filter('cpg_city_page_content', function($content, $city_data) {
    // Personnaliser le contenu
    return $content;
}, 10, 2);
```

### Actions

```php
// Avant les services
do_action('cpg_before_city_services', $city_data);

// Après les services
do_action('cpg_after_city_services', $city_data);
```

## Structure des URLs

- **Page ville** : `/metallier-[slug-ville]/`
  - Exemple : `/metallier-clermont-ferrand/`
- **Archive** : `/metallier-puy-de-dome/`

## Intégration avec les réalisations

Le plugin ajoute automatiquement :
- Une taxonomie "Ville" au CPT "realisation"
- Un champ pour associer une réalisation à une ville
- L'affichage automatique des réalisations sur les pages ville

## Structure des fichiers

```
city-pages-generator/
├── city-pages-generator.php      # Fichier principal
├── includes/
│   ├── class-post-type.php       # Custom Post Type
│   ├── class-taxonomy.php        # Taxonomies
│   ├── class-metaboxes.php       # Champs personnalisés
│   ├── class-content-generator.php # Génération de contenu
│   ├── class-seo-handler.php     # SEO et Schema.org
│   ├── class-template-loader.php # Chargement des templates
│   └── class-realisation-integration.php # Intégration réalisations
├── admin/
│   ├── class-admin.php           # Interface admin
│   ├── class-settings.php        # Page paramètres
│   ├── class-city-list-table.php # Tableau des villes
│   ├── css/admin.css             # Styles admin
│   └── js/admin.js               # Scripts admin
├── public/
│   ├── class-public.php          # Fonctionnalités publiques
│   ├── css/public.css            # Styles frontend
│   └── js/public.js              # Scripts frontend
├── templates/
│   └── single-city_page.php      # Template page ville
└── languages/                    # Traductions
```

## Prérequis

- WordPress 5.8+
- PHP 7.4+
- MySQL 5.7+

## Bonnes pratiques SEO

1. **Unicité du contenu** : Le plugin génère automatiquement des variations pour éviter le duplicate content
2. **Génération progressive** : Utilisez l'import par lots pour rester naturel
3. **Personnalisation** : Éditez manuellement les pages importantes après génération
4. **Images** : Ajoutez des images de réalisations locales

## Changelog

### 1.0.0
- Version initiale
- Custom Post Type "city_page"
- Génération automatique de contenu SEO
- Interface d'administration complète
- Import/Export CSV
- Intégration Schema.org
- Intégration avec les réalisations

## Support

Pour toute question ou problème :
- Email : contact@al-metallerie.fr
- Site : https://al-metallerie.fr

## Licence

GPL-2.0+
