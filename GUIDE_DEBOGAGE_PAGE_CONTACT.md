# 🔍 Guide de débogage - Page Contact

## ✅ Checklist de vérification

### 1. La page existe-t-elle ?

- [ ] **Pages → Toutes les pages** : La page "Contact" existe
- [ ] **Modèle de page** : "Page Contact" est sélectionné dans "Attributs de page"
- [ ] **Statut** : La page est "Publiée" (pas en brouillon)
- [ ] **URL** : http://localhost:8000/contact/ est accessible

---

### 2. Le CSS se charge-t-il ?

#### Vérifier dans le navigateur (F12)

1. **Ouvrir la page Contact**
2. **F12 → Onglet "Network" (Réseau)**
3. **Recharger la page (Ctrl+R)**
4. **Chercher** : `contact.css`

**✅ Si vous voyez** : `contact.css` avec statut 200 → CSS chargé  
**❌ Si vous ne voyez pas** : CSS non chargé → Voir solutions ci-dessous

#### Solutions si CSS non chargé

**A. Vider le cache WordPress**
```
Admin → Extensions → Installer "WP Super Cache"
→ Supprimer le cache
```

**B. Vérifier le fichier existe**
```
Chemin : wordpress/wp-content/themes/almetal-theme/assets/css/contact.css
Taille : ~14 Ko
```

**C. Forcer le rechargement**
- Ctrl + F5 (Windows)
- Cmd + Shift + R (Mac)

**D. Vérifier functions.php**
```php
// Ligne 93 de functions.php
if (is_page_template('page-contact.php') || is_page('contact')) {
```

---

### 3. Le JavaScript se charge-t-il ?

#### Vérifier dans la console (F12)

1. **F12 → Onglet "Console"**
2. **Chercher des erreurs** en rouge

**Erreurs courantes** :

```
❌ "google is not defined"
→ Clé API manquante ou invalide

❌ "$ is not a function"
→ jQuery non chargé

❌ "Failed to load resource: contact.js"
→ Fichier JS introuvable
```

#### Solutions

**A. Vérifier la clé API Google Maps**
```javascript
// Ligne 280 de contact.js
const apiKey = 'AIzaSyAWrQ0heLj3xzkTUy_-elelg0I9HtsvzH8';
```

**B. Activer l'API dans Google Cloud**
1. https://console.cloud.google.com/
2. APIs & Services → Library
3. Chercher "Maps JavaScript API"
4. Cliquer "Enable"

**C. Vérifier les restrictions**
1. Google Cloud Console
2. Credentials → Votre clé API
3. Application restrictions → HTTP referrers
4. Ajouter : `http://localhost:8000/*`

---

### 4. La carte Google Maps s'affiche-t-elle ?

#### Diagnostic

**Ouvrir F12 → Console et chercher** :

```
✅ "Google Maps loaded successfully"
→ Tout fonctionne

❌ "Google Maps JavaScript API error: InvalidKeyMapError"
→ Clé API invalide

❌ "Google Maps JavaScript API error: RefererNotAllowedMapError"
→ Domaine non autorisé

❌ "This page can't load Google Maps correctly"
→ Quota dépassé ou facturation non activée
```

#### Solutions

**A. Clé API invalide**
1. Vérifier la clé dans `contact.js` ligne 280
2. Copier-coller depuis Google Cloud Console
3. Pas d'espaces avant/après

**B. Domaine non autorisé**
1. Google Cloud Console → Credentials
2. Votre clé API → Edit
3. Application restrictions → HTTP referrers
4. Add an item : `http://localhost:8000/*`
5. Save

**C. Facturation non activée**
1. Google Cloud Console
2. Billing → Link a billing account
3. Ajouter une carte (gratuit jusqu'à 200$/mois)

**D. Quota dépassé**
1. Google Cloud Console
2. APIs & Services → Dashboard
3. Vérifier les quotas

---

### 5. La table de base de données existe-t-elle ?

#### Vérifier via phpMyAdmin

1. **Ouvrir** : http://localhost:8080
2. **Base** : `almetal_db`
3. **Chercher** : `wp_almetal_contacts`

**✅ Si la table existe** : Tout est OK  
**❌ Si la table n'existe pas** : Créer manuellement

#### Créer la table manuellement

**Méthode 1 : Via phpMyAdmin**

1. phpMyAdmin → almetal_db → SQL
2. Copier-coller :

```sql
CREATE TABLE IF NOT EXISTS `wp_almetal_contacts` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `project_type` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

3. Exécuter

**Méthode 2 : Réactiver le thème**

1. Admin → Apparence → Thèmes
2. Activer "Twenty Twenty-Three"
3. Réactiver "AL Métallerie"
4. La table devrait se créer automatiquement

---

### 6. Le formulaire fonctionne-t-il ?

#### Test complet

1. **Remplir tous les champs**
2. **Cocher le consentement**
3. **Cliquer "Envoyer"**

**Résultats possibles** :

**✅ Message de succès vert**
→ Tout fonctionne !

**❌ Message d'erreur rouge**
→ Voir console (F12)

**❌ Rien ne se passe**
→ Erreur JavaScript

#### Vérifier l'envoi

**A. Console JavaScript (F12)**
```
✅ "XHR finished loading: POST admin-post.php"
→ Formulaire envoyé

❌ "Failed to load resource: admin-post.php"
→ Problème de route WordPress
```

**B. Vérifier la réception email**
- Destinataire : al.metallerie.soudure@orange.fr
- Vérifier les spams
- Attendre 1-2 minutes

**C. Vérifier la base de données**
```sql
SELECT * FROM wp_almetal_contacts ORDER BY submitted_at DESC;
```

---

## 🐛 Problèmes courants et solutions

### Problème 1 : "La page est blanche"

**Causes** :
- Erreur PHP fatale
- Template introuvable

**Solutions** :
1. Activer le mode debug WordPress
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

2. Vérifier les logs
```
wordpress/wp-content/debug.log
```

3. Vérifier que le fichier existe
```
wordpress/wp-content/themes/almetal-theme/page-contact.php
```

---

### Problème 2 : "Le CSS ne s'applique pas"

**Causes** :
- Cache navigateur
- Cache WordPress
- Fichier CSS non chargé

**Solutions** :
1. **Vider le cache navigateur** : Ctrl + F5
2. **Vider le cache WordPress** : Plugin WP Super Cache
3. **Vérifier F12 → Network** : contact.css doit être chargé
4. **Forcer le rechargement** : Modifier la version dans functions.php

```php
// functions.php ligne 98
wp_get_theme()->get('Version') . '.1'  // Ajouter .1
```

---

### Problème 3 : "Google Maps ne s'affiche pas"

**Causes** :
- Clé API manquante
- API non activée
- Domaine non autorisé
- Facturation non activée

**Solutions** :

**1. Vérifier la clé API**
```javascript
// contact.js ligne 280
console.log('API Key:', apiKey);  // Ajouter cette ligne pour debug
```

**2. Tester la clé manuellement**
```
https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE&callback=initMap
```

**3. Activer l'API**
- Google Cloud Console
- APIs & Services → Library
- Maps JavaScript API → Enable

**4. Autoriser le domaine**
- Credentials → Votre clé
- Application restrictions
- HTTP referrers : `http://localhost:8000/*`

**5. Activer la facturation**
- Billing → Link billing account
- Gratuit jusqu'à 200$/mois

---

### Problème 4 : "Les emails ne sont pas envoyés"

**Causes** :
- Fonction wp_mail() bloquée
- Serveur SMTP non configuré
- Emails dans les spams

**Solutions** :

**1. Tester wp_mail()**
```php
// Ajouter dans functions.php temporairement
add_action('init', function() {
    $test = wp_mail('votre@email.com', 'Test', 'Test email');
    var_dump($test);  // true = fonctionne, false = problème
});
```

**2. Installer un plugin SMTP**
```
Extensions → Ajouter
Chercher : "WP Mail SMTP"
Installer et activer
Configurer avec smtp.orange.fr
```

**3. Vérifier les spams**
- Dossier spam de al.metallerie.soudure@orange.fr
- Marquer comme "Non spam"

**4. Logs d'erreurs**
```php
// wp-config.php
define('WP_DEBUG_LOG', true);

// Vérifier
wordpress/wp-content/debug.log
```

---

### Problème 5 : "Erreur de base de données"

**Message** :
```
Table 'almetal_db.wp_almetal_contacts' doesn't exist
```

**Solution** :

**1. Créer la table manuellement**
```sql
-- Via phpMyAdmin
CREATE TABLE IF NOT EXISTS `wp_almetal_contacts` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `project_type` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**2. Vérifier le préfixe**
```php
// wp-config.php
$table_prefix = 'wp_';  // Doit correspondre
```

**3. Forcer la création**
```php
// Ajouter temporairement dans functions.php
add_action('init', function() {
    require_once get_template_directory() . '/inc/contact-handler.php';
    almetal_create_contacts_table();
});
```

---

## 📊 Outils de diagnostic

### 1. Console JavaScript (F12)

**Onglets importants** :
- **Console** : Erreurs JavaScript
- **Network** : Fichiers chargés (CSS, JS, API)
- **Elements** : Inspecter le HTML/CSS

### 2. phpMyAdmin

**URL** : http://localhost:8080  
**Utilité** : Vérifier/créer la table, voir les données

### 3. WordPress Debug

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

**Logs** : `wordpress/wp-content/debug.log`

### 4. Query Monitor (Plugin)

```
Extensions → Ajouter → "Query Monitor"
Installer et activer
Voir les requêtes SQL, hooks, erreurs
```

---

## ✅ Checklist finale

Avant de dire "Ça ne fonctionne pas", vérifier :

- [ ] La page "Contact" existe et est publiée
- [ ] Le template "Page Contact" est sélectionné
- [ ] Le fichier `page-contact.php` existe
- [ ] Le fichier `contact.css` existe et se charge (F12 → Network)
- [ ] Le fichier `contact.js` existe et se charge (F12 → Network)
- [ ] La clé API Google Maps est configurée (ligne 280 de contact.js)
- [ ] L'API "Maps JavaScript API" est activée dans Google Cloud
- [ ] Le domaine `localhost:8000` est autorisé dans les restrictions
- [ ] La table `wp_almetal_contacts` existe dans la base de données
- [ ] Le cache navigateur est vidé (Ctrl + F5)
- [ ] Pas d'erreurs dans la console JavaScript (F12)
- [ ] Le fichier `contact-handler.php` est chargé dans functions.php

---

## 🆘 Besoin d'aide ?

### Informations à fournir

Si vous avez toujours un problème, fournissez :

1. **Capture d'écran** de la page
2. **Console JavaScript** (F12 → Console)
3. **Network** (F12 → Network → contact.css et contact.js)
4. **Erreurs PHP** (debug.log)
5. **Version WordPress** : Admin → Tableau de bord
6. **Navigateur utilisé** : Chrome, Firefox, Safari, Edge

---

**Suivez ce guide étape par étape et la page Contact fonctionnera !** 🎉
