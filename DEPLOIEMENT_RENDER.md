# 🚀 Guide de Déploiement sur Render

Ce guide vous explique comment déployer le site AL Métallerie V1 sur Render pour présentation client.

## 📋 Prérequis

1. **Compte Render** : Créez un compte gratuit sur [render.com](https://render.com)
2. **Compte GitHub** : Créez un compte sur [github.com](https://github.com) si vous n'en avez pas
3. **Git installé** : Pour pousser le code vers GitHub

## 🔧 Étape 1 : Créer un dépôt GitHub

### 1.1 Créer le dépôt sur GitHub

1. Allez sur [github.com](https://github.com)
2. Cliquez sur le bouton **"New"** (nouveau dépôt)
3. Nommez-le : `almetal-v1`
4. Laissez-le **privé** (recommandé)
5. **Ne cochez pas** "Initialize with README" (déjà présent)
6. Cliquez sur **"Create repository"**

### 1.2 Pousser le code vers GitHub

Ouvrez un terminal dans le dossier du projet et exécutez :

```bash
# Se placer dans le dossier du projet
cd "c:\Users\BIGBUD\Desktop\PROJETS\AL Metallerie\ALMETAL"

# Vérifier le statut Git
git status

# Ajouter tous les fichiers
git add .

# Créer le commit
git commit -m "V1 - Site AL Métallerie prêt pour démo client"

# Ajouter le dépôt distant (remplacez USERNAME par votre nom d'utilisateur GitHub)
git remote add origin https://github.com/USERNAME/almetal-v1.git

# Pousser le code
git push -u origin main
```

Si vous avez une erreur sur la branche, essayez :
```bash
git branch -M main
git push -u origin main
```

## 🌐 Étape 2 : Déployer sur Render

### Option A : Déploiement Simple (Recommandé pour démo)

**⚠️ Note importante** : Render ne supporte pas MySQL en gratuit. Pour une démo rapide, je recommande d'utiliser un hébergeur WordPress gratuit ou une base MySQL externe.

### Option B : Utiliser un service WordPress gratuit

Pour une démo client rapide, considérez ces alternatives :

1. **InfinityFree** (gratuit, supporte WordPress + MySQL)
   - Site : https://infinityfree.net
   - Inclut : PHP, MySQL, cPanel
   - Limite : Pas de nom de domaine personnalisé en gratuit

2. **000webhost** (gratuit, supporte WordPress)
   - Site : https://www.000webhost.com
   - Inclut : PHP, MySQL, WordPress auto-installer

3. **Render avec base externe** (configuration avancée)
   - Utiliser une base MySQL externe (ex: PlanetScale, Railway)

## 🔄 Étape 3 : Configuration Alternative - Déploiement sur InfinityFree

### 3.1 Créer un compte InfinityFree

1. Allez sur https://infinityfree.net
2. Cliquez sur "Sign Up"
3. Créez votre compte gratuit

### 3.2 Créer un site

1. Dans le panneau de contrôle, cliquez sur "Create Account"
2. Choisissez un sous-domaine (ex: `almetal-demo.epizy.com`)
3. Laissez le mot de passe généré automatiquement

### 3.3 Exporter votre base de données locale

1. Ouvrez phpMyAdmin local : http://localhost:8080
2. Sélectionnez la base `almetal_db`
3. Cliquez sur "Exporter"
4. Choisissez "Rapide" et "SQL"
5. Téléchargez le fichier `.sql`

### 3.4 Uploader les fichiers

1. Dans InfinityFree, ouvrez le "File Manager"
2. Allez dans le dossier `htdocs`
3. Uploadez tous les fichiers du dossier `wordpress/` de votre projet local
4. Ou utilisez un client FTP (FileZilla) avec les identifiants fournis

### 3.5 Créer et importer la base de données

1. Dans InfinityFree, allez dans "MySQL Databases"
2. Créez une nouvelle base de données
3. Notez le nom de la base, l'utilisateur et le mot de passe
4. Ouvrez phpMyAdmin depuis InfinityFree
5. Sélectionnez votre base et importez le fichier `.sql`

### 3.6 Configurer wp-config.php

1. Éditez le fichier `wp-config.php` sur le serveur
2. Modifiez les lignes suivantes :

```php
define('DB_NAME', 'votre_nom_de_base');
define('DB_USER', 'votre_utilisateur');
define('DB_PASSWORD', 'votre_mot_de_passe');
define('DB_HOST', 'sql123.epizy.com'); // Fourni par InfinityFree

// Ajouter ces lignes pour forcer les URLs
define('WP_HOME', 'http://almetal-demo.epizy.com');
define('WP_SITEURL', 'http://almetal-demo.epizy.com');
```

### 3.7 Mettre à jour les URLs dans la base

1. Dans phpMyAdmin, exécutez ces requêtes SQL :

```sql
-- Remplacer localhost par votre nouveau domaine
UPDATE wp_options SET option_value = 'http://almetal-demo.epizy.com' WHERE option_name = 'siteurl';
UPDATE wp_options SET option_value = 'http://almetal-demo.epizy.com' WHERE option_name = 'home';

-- Mettre à jour les URLs dans les posts
UPDATE wp_posts SET guid = REPLACE(guid, 'http://localhost:8000', 'http://almetal-demo.epizy.com');
UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://localhost:8000', 'http://almetal-demo.epizy.com');

-- Mettre à jour les URLs dans les meta
UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'http://localhost:8000', 'http://almetal-demo.epizy.com');
```

## ✅ Étape 4 : Vérification

1. Visitez votre site : `http://almetal-demo.epizy.com`
2. Testez la version mobile (DevTools ou smartphone)
3. Vérifiez que toutes les images s'affichent
4. Testez la navigation et les formulaires

## 🎯 Checklist avant présentation client

- [ ] Site accessible via l'URL publique
- [ ] Version mobile fonctionne correctement
- [ ] Toutes les images sont chargées
- [ ] Menu de navigation fonctionne
- [ ] Formulaire de contact fonctionne
- [ ] Slideshow des réalisations fonctionne
- [ ] Pages légales sont accessibles
- [ ] Pas d'erreurs dans la console navigateur

## 🔒 Sécurité

**Important** :
- Changez le mot de passe admin WordPress après déploiement
- Désactivez le mode debug (`WP_DEBUG = false`)
- Supprimez les plugins de développement inutiles
- Limitez l'accès au tableau de bord WordPress

## 📱 Partager avec le client

Une fois déployé, partagez simplement l'URL :
```
http://almetal-demo.epizy.com
```

Pour la version mobile, le client peut :
1. Ouvrir l'URL sur son smartphone
2. Ou utiliser les DevTools du navigateur (F12 > Mode responsive)

## 🆘 Dépannage

### Le site affiche une erreur 500
- Vérifiez les permissions des fichiers (755 pour dossiers, 644 pour fichiers)
- Vérifiez le fichier `.htaccess`
- Consultez les logs d'erreur dans le panneau d'hébergement

### Les images ne s'affichent pas
- Vérifiez que le dossier `wp-content/uploads` existe
- Vérifiez les permissions (755)
- Assurez-vous que les URLs ont été mises à jour dans la base

### Le thème ne s'affiche pas
- Vérifiez que le dossier `wp-content/themes/almetal-theme` est complet
- Activez le thème depuis l'admin WordPress
- Vérifiez les permissions

## 📞 Support

Pour toute question, consultez :
- Documentation WordPress : https://wordpress.org/support/
- Forum InfinityFree : https://forum.infinityfree.net/

---

**Bonne présentation ! 🎉**
