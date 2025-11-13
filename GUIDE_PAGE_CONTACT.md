# 📞 Guide de la Page Contact - AL Métallerie

## ✨ Vue d'ensemble

La page de contact a été créée avec un design moderne inspiré de 34burger, avec :
- **Carte Google Maps plein écran** en arrière-plan
- **Overlay avec informations** cliquables
- **Formulaire de contact** intégré
- **Design responsive** (mobile/desktop)
- **Icônes SVG métallerie** personnalisées

---

## 📋 Informations affichées

### Coordonnées
- **Téléphone** : 06 73 33 35 32 (cliquable pour appel direct)
- **Adresse** : 14 route de Maringues, 63920 Peschadoires (lien vers itinéraire)
- **Email** : al.metallerie.soudure@orange.fr (ouvre client mail)
- **Horaires** : Lun-Ven 8h-18h, Sam sur rendez-vous

### Actions rapides
- Bouton **Appeler** (vert)
- Bouton **Itinéraire** (bleu)

---

## 🗺️ Configuration de la carte Google Maps

### Étape 1 : Obtenir une clé API Google Maps

1. **Aller sur** : https://console.cloud.google.com/
2. **Créer un projet** ou sélectionner un projet existant
3. **Activer l'API** : "Maps JavaScript API"
4. **Créer des identifiants** → Clé API
5. **Copier la clé**

### Étape 2 : Ajouter la clé dans le code

**Fichier** : `assets/js/contact.js`  
**Ligne** : 185

```javascript
const apiKey = 'VOTRE_CLE_API_GOOGLE_MAPS';
```

Remplacez `'VOTRE_CLE_API_GOOGLE_MAPS'` par votre clé réelle.

### Étape 3 : Restreindre la clé (sécurité)

Dans la console Google Cloud :
1. **Restrictions d'API** → Sélectionner "Maps JavaScript API"
2. **Restrictions de site web** → Ajouter votre domaine
   - `http://localhost:8000/*` (développement)
   - `https://votre-domaine.fr/*` (production)

### Style de carte

La carte utilise un **style sombre** personnalisé qui s'harmonise avec le design du site.

Pour modifier le style :
- **Fichier** : `assets/js/contact.js`
- **Lignes** : 31-89 (tableau `styles`)

Styles disponibles :
- **Standard** : Supprimer le tableau `styles`
- **Satellite** : `mapTypeId: 'satellite'`
- **Personnalisé** : Utiliser https://mapstyle.withgoogle.com/

---

## 📝 Formulaire de contact

### Champs du formulaire

1. **Nom complet** (requis)
2. **Téléphone** (requis)
3. **Email** (requis)
4. **Type de projet** (requis) :
   - Portail
   - Garde-corps
   - Escalier
   - Pergola
   - Verrière
   - Mobilier métallique
   - Réparation
   - Formation
   - Autre

5. **Message** (requis)
6. **Consentement RGPD** (requis)

### Validation

- **Côté client** : JavaScript (temps réel)
- **Côté serveur** : PHP (sécurité)
- **Anti-spam** : Nonce WordPress

### Envoi d'emails

Lors de la soumission :

1. **Email à l'entreprise** :
   - Destinataire : `al.metallerie.soudure@orange.fr`
   - Sujet : "Nouvelle demande de contact - [Type de projet]"
   - Format : HTML avec design

2. **Email de confirmation au client** :
   - Confirmation de réception
   - Rappel des coordonnées
   - Format : HTML professionnel

### Stockage en base de données

Toutes les demandes sont sauvegardées dans une table WordPress :
- Table : `wp_almetal_contacts`
- Accessible via : **Admin WordPress → Contacts**

---

## 🎨 Personnalisation

### Modifier les couleurs

**Fichier** : `assets/css/contact.css`

```css
/* Couleur primaire (orange) */
--color-primary: #F08B18;

/* Couleur secondaire (bleu) */
--color-secondary: #6C85FC;
```

### Modifier les horaires

**Fichier** : `page-contact.php`  
**Lignes** : 88-91

```php
<span class="contact-info-value">
    Lun - Ven : 8h00 - 18h00<br>
    Sam : Sur rendez-vous
</span>
```

### Modifier les types de projets

**Fichier** : `page-contact.php`  
**Lignes** : 136-145

```php
<option value="portail">Portail</option>
<option value="garde-corps">Garde-corps</option>
<!-- Ajouter ou modifier ici -->
```

### Modifier le destinataire des emails

**Fichier** : `inc/contact-handler.php`  
**Ligne** : 58

```php
$to = 'al.metallerie.soudure@orange.fr';
```

---

## 🎯 Utilisation

### Créer la page dans WordPress

1. **Pages → Ajouter**
2. **Titre** : "Contact"
3. **Template** : Sélectionner "Page Contact" (dans l'encadré "Attributs de page")
4. **Publier**

### Ajouter au menu

1. **Apparence → Menus**
2. **Ajouter** la page "Contact"
3. **Enregistrer**

### Tester le formulaire

1. **Remplir tous les champs**
2. **Cocher le consentement**
3. **Envoyer**
4. **Vérifier** :
   - Message de confirmation
   - Email reçu à `al.metallerie.soudure@orange.fr`
   - Email de confirmation au client
   - Entrée dans **Admin → Contacts**

---

## 🔧 Configuration email WordPress

Pour que les emails fonctionnent correctement :

### Option 1 : Plugin SMTP (recommandé)

1. **Installer** : "WP Mail SMTP" ou "Easy WP SMTP"
2. **Configurer** avec les paramètres Orange :
   - **SMTP Host** : smtp.orange.fr
   - **Port** : 587 (TLS) ou 465 (SSL)
   - **Username** : al.metallerie.soudure@orange.fr
   - **Password** : Votre mot de passe email

### Option 2 : Configuration serveur

Sur O2switch, les emails devraient fonctionner directement.

Si problème :
- Vérifier les paramètres SMTP dans cPanel
- Contacter le support O2switch

---

## 📱 Responsive

### Desktop (> 768px)
- Carte plein écran
- Overlay centré (600px max)
- Formulaire complet
- Animations fluides

### Mobile (≤ 768px)
- Carte réduite en hauteur
- Overlay scrollable
- Formulaire adapté (1 colonne)
- Boutons empilés

### Très petit écran (≤ 480px)
- Infos en colonne
- Icônes centrées
- Texte optimisé

---

## 🎨 Icônes SVG métallerie

Les icônes utilisées :

1. **Marteau** (séparateur) : Symbolise la métallerie
2. **Téléphone** : Contact direct
3. **Localisation** : Adresse
4. **Email** : Contact écrit
5. **Horloge** : Horaires
6. **Maison** : Titre principal
7. **Avion papier** : Envoi formulaire
8. **Boussole** : Itinéraire

### Personnaliser les icônes

Les icônes sont en SVG inline dans `page-contact.php`.

Pour les modifier :
- Utiliser https://feathericons.com/
- Ou https://heroicons.com/
- Copier le code SVG
- Remplacer dans le template

---

## 🐛 Dépannage

### La carte ne s'affiche pas

**Causes possibles** :
1. Clé API manquante ou invalide
2. API Maps JavaScript non activée
3. Restrictions de domaine
4. Console JavaScript (F12) pour voir les erreurs

**Solution** :
- Vérifier la clé API
- Activer l'API dans Google Cloud Console
- Vérifier les restrictions

### Les emails ne sont pas envoyés

**Causes possibles** :
1. Fonction `wp_mail()` bloquée
2. Serveur SMTP non configuré
3. Email dans les spams

**Solution** :
- Installer un plugin SMTP
- Vérifier les logs serveur
- Tester avec un autre email

### Le formulaire ne se soumet pas

**Causes possibles** :
1. Erreur JavaScript
2. Nonce invalide
3. Validation échouée

**Solution** :
- Ouvrir la console (F12)
- Vérifier les erreurs
- Tester avec tous les champs remplis

### Les coordonnées GPS sont incorrectes

**Fichier** : `assets/js/contact.js`  
**Lignes** : 20-23

```javascript
const location = {
    lat: 45.8167,  // Latitude
    lng: 3.4833    // Longitude
};
```

Pour obtenir les coordonnées exactes :
1. Aller sur Google Maps
2. Clic droit sur l'adresse → "Plus d'infos sur cet endroit"
3. Copier les coordonnées

---

## 📊 Statistiques

### Données collectées

Pour chaque soumission :
- Nom
- Téléphone
- Email
- Type de projet
- Message
- Date/heure

### Consulter les demandes

**Admin WordPress → Contacts**

Tableau avec :
- Date de soumission
- Informations du contact
- Type de projet
- Extrait du message

### Export des données

Pour exporter :
1. Aller dans **Contacts**
2. Utiliser un plugin comme "Export All URLs" ou "WP All Export"
3. Ou requête SQL directe :

```sql
SELECT * FROM wp_almetal_contacts ORDER BY submitted_at DESC;
```

---

## 🔐 Sécurité

### Mesures implémentées

✅ **Nonce WordPress** : Protection CSRF  
✅ **Sanitization** : Nettoyage des données  
✅ **Validation** : Côté client et serveur  
✅ **Prepared statements** : Protection SQL injection  
✅ **Consentement RGPD** : Checkbox obligatoire

### Recommandations

- Installer un plugin anti-spam (Akismet, reCAPTCHA)
- Limiter les soumissions (rate limiting)
- Sauvegarder régulièrement la base de données
- Monitorer les logs

---

## ♿ Accessibilité

### Fonctionnalités

✅ **ARIA labels** : Tous les boutons  
✅ **Labels explicites** : Tous les champs  
✅ **Contraste** : WCAG 2.1 AA  
✅ **Navigation clavier** : Complète  
✅ **Focus visible** : Indicateurs clairs

### Tests

Tester avec :
- **WAVE** : https://wave.webaim.org/
- **axe DevTools** : Extension navigateur
- **Screen reader** : NVDA, JAWS, VoiceOver

---

## 🎯 Optimisations

### Performance

✅ **Chargement conditionnel** : CSS/JS seulement sur page contact  
✅ **Lazy loading** : Carte chargée après le DOM  
✅ **Minification** : À faire en production  
✅ **Cache** : Géré par WordPress

### SEO

Pour optimiser :
1. **Titre** : "Contact - AL Métallerie | Expert en métallerie à Clermont-Ferrand"
2. **Meta description** : Ajouter via plugin SEO
3. **Schema.org** : Ajouter markup LocalBusiness
4. **Alt text** : Sur les icônes si images

---

## 📝 Checklist de mise en ligne

- [ ] Obtenir clé API Google Maps
- [ ] Configurer la clé dans `contact.js`
- [ ] Tester l'affichage de la carte
- [ ] Vérifier les coordonnées GPS
- [ ] Configurer SMTP pour les emails
- [ ] Tester l'envoi du formulaire
- [ ] Vérifier la réception des emails
- [ ] Tester sur mobile
- [ ] Vérifier l'accessibilité
- [ ] Ajouter au menu de navigation
- [ ] Tester tous les liens cliquables
- [ ] Vérifier le responsive
- [ ] Installer un anti-spam
- [ ] Configurer les sauvegardes

---

## 🎨 Améliorations futures possibles

- [ ] Intégration reCAPTCHA v3
- [ ] Upload de fichiers (photos du projet)
- [ ] Calendrier de prise de rendez-vous
- [ ] Chat en direct
- [ ] Estimation de devis automatique
- [ ] Galerie de réalisations liée
- [ ] Témoignages clients
- [ ] FAQ intégrée

---

## 📞 Support

### Fichiers concernés

- **Template** : `page-contact.php`
- **Styles** : `assets/css/contact.css`
- **JavaScript** : `assets/js/contact.js`
- **Handler** : `inc/contact-handler.php`
- **Functions** : `functions.php` (lignes 93-100, 123-131, 319)

### Ressources

- Google Maps API : https://developers.google.com/maps
- WordPress Mail : https://developer.wordpress.org/reference/functions/wp_mail/
- Feather Icons : https://feathericons.com/

---

**Votre page de contact professionnelle est prête ! N'oubliez pas de configurer la clé API Google Maps.** 🎉📞
