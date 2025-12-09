# Training Manager - Plugin WordPress

Plugin professionnel de gestion de sessions de formation pour WordPress.

## Description

Training Manager permet de gérer des sessions de formation avec :
- Calendrier interactif
- Système de réservation évolutif
- Notifications automatiques
- Interface d'administration complète
- Statistiques et rapports

## Installation

1. Téléchargez le plugin
2. Uploadez le dossier `training-manager` dans `/wp-content/plugins/`
3. Activez le plugin dans WordPress
4. Configurez les paramètres dans **Formations > Paramètres**

## Fonctionnalités

### Types de Formation

- **Particuliers** : Découverte, "Je fais moi-même"
- **Professionnels** : CAP, Perfectionnement, Qualification

### Gestion des Sessions

- Dates et horaires multiples
- Gestion des places (total, réservées, restantes)
- Statuts : Ouvert, Complet, Liste d'attente, Annulé
- Formateur, lieu, tarif, prérequis
- Documents joints (PDF, images)

### Système de Réservation

**Phase 1 (actuelle)** : Demandes d'information
- Formulaire de contact par session
- Notifications admin

**Phase 2 (préparée)** : Réservation en ligne
- Architecture prête pour paiement
- Tables de base de données créées

### Notifications

- Nouvelle demande reçue
- Session complète
- Session bientôt complète
- Rappels avant formation
- Templates d'emails personnalisables

## Shortcodes

### Calendrier

```
[training_calendar]
[training_calendar type="particuliers" show_legend="yes"]
```

### Liste avec filtres

```
[training_list]
[training_list type="particuliers" per_page="9" columns="3"]
```

### Prochaines formations

```
[training_upcoming]
[training_upcoming count="3" type="professionnels"]
```

### Par catégorie

```
[training_category category="particuliers"]
[training_category category="professionnels" count="6"]
```

## Paramètres des Shortcodes

| Paramètre | Description | Valeurs |
|-----------|-------------|---------|
| `type` | Filtrer par type | `particuliers`, `professionnels` |
| `theme` | Filtrer par thème | Slug du thème |
| `count` | Nombre de sessions | Nombre entier |
| `per_page` | Sessions par page | Nombre entier |
| `columns` | Colonnes d'affichage | 1, 2, 3, 4 |
| `show_filters` | Afficher les filtres | `yes`, `no` |
| `show_legend` | Afficher la légende | `yes`, `no` |

## Configuration

### Paramètres généraux

- Email administrateur
- Emails supplémentaires
- Format de date/heure
- Symbole monétaire

### Notifications

- Activer/désactiver chaque type
- Seuil "bientôt complet"
- Jours avant rappel

### Affichage

- Couleur principale
- Couleur secondaire
- Premier jour de la semaine

## Structure des fichiers

```
training-manager/
├── training-manager.php          # Fichier principal
├── includes/
│   ├── class-loader.php          # Gestion des hooks
│   ├── class-activator.php       # Activation
│   ├── class-deactivator.php     # Désactivation
│   ├── class-post-types.php      # Custom Post Types
│   ├── class-taxonomies.php      # Taxonomies
│   ├── class-metaboxes.php       # Champs personnalisés
│   ├── class-notifications.php   # Système d'emails
│   ├── class-calendar.php        # Calendrier
│   ├── class-bookings.php        # Réservations
│   ├── class-settings.php        # Paramètres
│   └── class-shortcodes.php      # Shortcodes
├── admin/
│   ├── class-admin.php           # Interface admin
│   ├── css/admin.css             # Styles admin
│   └── js/admin.js               # Scripts admin
├── public/
│   ├── class-public.php          # Interface publique
│   ├── css/
│   │   ├── public.css            # Styles publics
│   │   └── calendar.css          # Styles calendrier
│   └── js/
│       ├── public.js             # Scripts publics
│       └── calendar.js           # Scripts calendrier
└── languages/                    # Traductions
```

## Base de données

Le plugin crée deux tables personnalisées :

### wp_tm_bookings

Stocke les demandes et réservations.

| Colonne | Type | Description |
|---------|------|-------------|
| id | bigint | ID unique |
| session_id | bigint | ID de la session |
| user_id | bigint | ID utilisateur (optionnel) |
| first_name | varchar | Prénom |
| last_name | varchar | Nom |
| email | varchar | Email |
| phone | varchar | Téléphone |
| company | varchar | Entreprise |
| message | text | Message |
| status | varchar | pending, confirmed, cancelled |
| booking_type | varchar | contact_request, booking |
| payment_status | varchar | Pour Phase 2 |
| created_at | datetime | Date de création |

### wp_tm_notifications_log

Historique des notifications envoyées.

## Hooks disponibles

### Actions

```php
// Après création d'une demande
do_action('tm_booking_created', $booking_id, $session_id, $data);

// Avant envoi de notification
do_action('tm_before_notification', $type, $session_id, $data);
```

### Filtres

```php
// Modifier les destinataires
add_filter('tm_notification_recipients', function($recipients, $type) {
    return $recipients;
}, 10, 2);

// Modifier le contenu email
add_filter('tm_email_content', function($content, $type, $variables) {
    return $content;
}, 10, 3);
```

## Prérequis

- WordPress 6.0+
- PHP 7.4+
- MySQL 5.7+

## Compatibilité

- Thèmes WordPress standards
- Gutenberg
- WooCommerce (préparé pour Phase 2)
- RGPD compliant

## Changelog

### 1.0.0
- Version initiale
- Custom Post Type "Sessions de Formation"
- Taxonomies Types et Thèmes
- Système de demandes d'information
- Calendrier interactif (FullCalendar)
- Shortcodes
- Interface d'administration
- Notifications email
- Statistiques de base

## Support

Pour toute question ou problème, contactez :
- Email : contact@al-metallerie.fr
- Site : https://al-metallerie-soudure.fr

## Licence

GPL-2.0+
