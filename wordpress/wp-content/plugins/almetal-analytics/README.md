# 📊 AL Métallerie Analytics

Plugin WordPress d'analytics RGPD-compliant avec dashboard intégré au backoffice.

## ✨ Fonctionnalités

### Analytics
- ✅ Visites en temps réel
- ✅ Sources de trafic (UTM, direct, social, referral)
- ✅ Pages vues, durée session, taux rebond
- ✅ Scroll depth tracking
- ✅ Heatmaps (clics)
- ✅ Device info (OS, navigateur, mobile/desktop)
- ✅ Nouveaux visiteurs vs retours

### Conformité RGPD/CNIL
- ✅ Cookie banner avec choix (accepter/refuser/personnaliser)
- ✅ Consent Mode v2 (Google)
- ✅ IP anonymisée (dernier octet masqué)
- ✅ Auto-suppression des données après 13 mois
- ✅ Log des preuves de consentement
- ✅ Export/suppression données utilisateur (Art. 15 & 17)

### Collecte opt-in
- ✅ Double opt-in (email de confirmation)
- ✅ Stockage chiffré AES-256
- ✅ Export CSV/JSON

### Dashboard
- ✅ Interface moderne intégrée à WordPress
- ✅ Dark mode
- ✅ Graphiques interactifs (Chart.js)
- ✅ Export CSV, JSON
- ✅ Widget dashboard WordPress

## 📦 Installation

1. Copier le dossier `almetal-analytics` dans `/wp-content/plugins/`
2. Activer le plugin dans WordPress
3. Aller dans **Analytics > Réglages** pour configurer

## 🔧 Configuration

### Réglages disponibles

| Option | Description | Défaut |
|--------|-------------|--------|
| Tracking activé | Active/désactive la collecte | ✅ Oui |
| Heatmaps | Enregistre les clics | ❌ Non |
| Anonymisation IP | Masque le dernier octet | ✅ Oui |
| Rétention données | Durée de conservation | 13 mois |
| Exclure rôles | Rôles non trackés | Administrateur |

## 📊 Dashboard

Accessible via le menu **Analytics** dans l'admin WordPress :

- **Dashboard** : Vue d'ensemble avec KPIs et graphiques
- **Temps réel** : Visiteurs actifs en ce moment
- **Heatmaps** : Visualisation des clics par page
- **Opt-ins** : Gestion des leads collectés
- **RGPD** : Rapport de conformité et actions
- **Réglages** : Configuration du plugin

## 🍪 Cookie Banner

Le cookie banner s'affiche automatiquement sur le frontend. Il propose :

- **Tout accepter** : Active tous les cookies
- **Refuser** : N'active que les cookies nécessaires
- **Personnaliser** : Choix par catégorie

### Catégories de cookies

| Catégorie | Description | Requis |
|-----------|-------------|--------|
| Nécessaires | Fonctionnement du site | ✅ Oui |
| Analytiques | Mesure d'audience anonyme | ❌ Non |
| Marketing | Publicité personnalisée | ❌ Non |
| Préférences | Mémorisation des choix | ❌ Non |

## 🔌 API REST

### Endpoints publics (tracking)

```
POST /wp-json/almetal-analytics/v1/track/visit
POST /wp-json/almetal-analytics/v1/track/event
POST /wp-json/almetal-analytics/v1/track/heatmap
POST /wp-json/almetal-analytics/v1/consent/log
POST /wp-json/almetal-analytics/v1/optin
```

### Endpoints admin (authentifié)

```
GET /wp-json/almetal-analytics/v1/stats
GET /wp-json/almetal-analytics/v1/stats/visits
GET /wp-json/almetal-analytics/v1/stats/pages
GET /wp-json/almetal-analytics/v1/stats/sources
GET /wp-json/almetal-analytics/v1/stats/devices
GET /wp-json/almetal-analytics/v1/realtime
GET /wp-json/almetal-analytics/v1/export/{type}
POST /wp-json/almetal-analytics/v1/gdpr/export
POST /wp-json/almetal-analytics/v1/gdpr/delete
```

## 📝 Shortcodes

### Formulaire opt-in

```php
[almetal_optin_form source="homepage" form_id="newsletter"]
```

### Bouton de gestion des cookies

```php
[almetal_cookie_settings text="Gérer mes cookies"]
```

## 🔒 Sécurité

- Chiffrement AES-256 pour les données sensibles
- Nonces WordPress pour les requêtes AJAX
- Validation et sanitization de toutes les entrées
- Rate limiting sur les endpoints de tracking

## 📄 Licence

Propriétaire - AL Métallerie © 2024

## 🆘 Support

Pour toute question, contactez l'équipe technique.
