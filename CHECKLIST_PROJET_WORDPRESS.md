# 📋 Checklist Projet WordPress - AL Metallerie

## 🎯 Objectif du Projet
Créer un site WordPress avec thème personnalisé :
- **Mobile** : One page
- **Desktop** : Multi-pages
- **Hébergement** : O2switch (Clermont-Ferrand)
- **Développement** : Docker pour portabilité

---

## Phase 1 : Configuration de l'environnement Docker

### 1.1 Structure du projet
- [x] Créer la structure de dossiers du projet
  ```
  ALMETAL/
  ├── docker/
  ├── wordpress/
  │   └── wp-content/
  │       └── themes/
  │           └── almetal-theme/
  ├── docker-compose.yml
  ├── .env
  └── README.md
  ```

### 1.2 Configuration Docker
- [x] Créer le fichier `docker-compose.yml`
  - Service WordPress
  - Service MySQL/MariaDB
  - Service phpMyAdmin (optionnel pour dev)
- [x] Créer le fichier `.env` pour les variables d'environnement
  - Identifiants base de données
  - Ports locaux
  - Configuration WordPress
- [x] Créer le fichier `.gitignore`
- [x] Tester le lancement des containers Docker

### 1.3 Vérification de l'installation
- [x] Accéder à WordPress en local (http://localhost:8000)
- [x] Vérifier phpMyAdmin (http://localhost:8080)
- [x] Compléter l'installation WordPress initiale

---

## Phase 2 : Développement du thème personnalisé

### 2.1 Structure du thème
- [x] Créer la structure de base du thème
  ```
  almetal-theme/
  ├── style.css
  ├── functions.php
  ├── index.php
  ├── header.php
  ├── footer.php
  ├── screenshot.png
  ├── assets/
  │   ├── css/
  │   ├── js/
  │   └── images/
  ├── template-parts/
  └── inc/
  ```

### 2.2 Fichiers de base
- [x] Créer `style.css` avec les métadonnées du thème
- [x] Créer `functions.php` avec les fonctionnalités de base
  - Enqueue des styles et scripts
  - Support des fonctionnalités WordPress
  - Menus et sidebars
- [x] Créer `index.php` (template par défaut)
- [x] Créer `header.php`
- [x] Créer `footer.php`

### 2.3 Templates spécifiques
- [x] Créer template pour mobile (one page)
  - `template-mobile-onepage.php`
- [x] Créer templates pour desktop
  - `front-page.php` (page d'accueil)
  - `page.php` (pages standards)
  - `single.php` (articles)
  - Templates personnalisés selon maquette Figma

### 2.4 Responsive et détection
- [x] Implémenter la détection mobile/desktop
  - Via CSS (media queries)
  - Via PHP (user agent) si nécessaire
- [x] Créer les styles CSS responsive
- [x] Tester sur différentes résolutions

---

## Phase 3 : Intégration de la maquette Figma

### 3.1 Export des assets
- [x] Exporter les images de Figma (voir GUIDE_INTEGRATION_FIGMA.md)
- [x] Exporter les icônes/SVG (voir GUIDE_INTEGRATION_FIGMA.md)
- [x] Noter les couleurs (palette) dans DESIGN_TOKENS.md
- [x] Noter les typographies dans DESIGN_TOKENS.md

### 3.2 Intégration HTML/CSS
- [x] Intégrer la structure HTML de la maquette
- [x] Créer les styles CSS personnalisés
- [x] Intégrer les animations/transitions
- [x] Optimiser les images

### 3.3 Fonctionnalités WordPress
- [x] Créer les Custom Post Types si nécessaire (Réalisations)
- [x] Créer les Custom Fields (ACF ou natif)
- [x] Configurer les menus
- [x] Configurer les widgets

---

## Phase 4 : Développement des fonctionnalités

### 4.1 Navigation
- [x] Menu principal (desktop)
- [x] Menu mobile (hamburger)
- [x] Navigation one-page (ancres) pour mobile

### 4.2 Contenu dynamique
- [x] Sections personnalisables via l'admin WordPress
- [x] Formulaire de contact
- [x] Galerie d'images/réalisations
- [x] Autres fonctionnalités selon besoins (Carrousel, Hero sections)

### 4.3 Optimisation
- [x] Optimiser les performances (lazy loading, minification)
- [x] Optimiser le SEO (meta tags, schema.org)
- [ ] Ajouter les favicons
- [x] Tester l'accessibilité (WCAG)

---

## Phase 5 : Tests et validation

### 5.1 Tests fonctionnels
- [ ] Tester toutes les pages/sections
- [ ] Tester les formulaires
- [ ] Tester les liens
- [ ] Tester sur différents navigateurs
  - Chrome
  - Firefox
  - Safari
  - Edge

### 5.2 Tests responsive
- [ ] Tester sur mobile (différentes tailles)
- [ ] Tester sur tablette
- [ ] Tester sur desktop (différentes résolutions)

### 5.3 Tests de performance
- [ ] Google PageSpeed Insights
- [ ] GTmetrix
- [ ] Temps de chargement

---

## Phase 6 : Préparation au déploiement

### 6.1 Export depuis Docker
- [ ] Exporter la base de données
- [ ] Préparer les fichiers WordPress
- [ ] Créer un backup complet

### 6.2 Configuration pour O2switch
- [ ] Adapter les URLs (search & replace dans la BDD)
- [ ] Vérifier la compatibilité PHP
- [ ] Préparer le fichier `.htaccess`
- [ ] Configurer `wp-config.php` pour production

### 6.3 Sécurité
- [ ] Changer les clés de sécurité WordPress
- [ ] Configurer les permissions des fichiers
- [ ] Désactiver le mode debug
- [ ] Installer un plugin de sécurité (Wordfence, iThemes Security)

---

## Phase 7 : Déploiement sur O2switch

### 7.1 Préparation hébergement
- [ ] Créer la base de données sur O2switch
- [ ] Noter les identifiants de connexion
- [ ] Configurer le domaine/sous-domaine

### 7.2 Upload des fichiers
- [ ] Uploader les fichiers WordPress via FTP/SFTP
- [ ] Importer la base de données via phpMyAdmin
- [ ] Configurer `wp-config.php` avec les bons identifiants

### 7.3 Vérification post-déploiement
- [ ] Vérifier que le site s'affiche correctement
- [ ] Tester toutes les fonctionnalités
- [ ] Vérifier les permaliens
- [ ] Tester les formulaires
- [ ] Configurer les sauvegardes automatiques

---

## Phase 8 : Finalisation

### 8.1 Configuration finale
- [ ] Configurer les emails (SMTP)
- [ ] Installer Google Analytics (si souhaité)
- [ ] Configurer le cache
- [ ] Optimiser la base de données

### 8.2 Documentation
- [ ] Créer un guide d'utilisation pour le client
- [ ] Documenter les fonctionnalités personnalisées
- [ ] Fournir les accès au client

### 8.3 Formation client
- [ ] Former le client à l'administration WordPress
- [ ] Expliquer comment modifier le contenu
- [ ] Expliquer la maintenance de base

---

## 📝 Notes et Ressources

### Technologies utilisées
- **WordPress** : Dernière version stable
- **Docker** : Pour l'environnement de développement
- **PHP** : Version compatible O2switch
- **MySQL/MariaDB** : Base de données
- **HTML/CSS/JavaScript** : Intégration frontend

### Ressources utiles
- Documentation WordPress : https://developer.wordpress.org/
- Docker WordPress : https://hub.docker.com/_/wordpress
- O2switch documentation : https://faq.o2switch.fr/

### Contacts
- **Client** : AL Metallerie
- **Hébergeur** : O2switch (Clermont-Ferrand)

---

**Date de début** : 23 octobre 2025  
**Dernière mise à jour** : 25 octobre 2025 - 07h30

---

## ✅ Progression globale

- Phase 1 : ✅ 7/7 tâches (100%)
- Phase 2 : ✅ 13/13 tâches (100%)
- Phase 3 : ✅ 11/11 tâches (100%)
- Phase 4 : ✅ 8/9 tâches (89%)
- Phase 5 : ⬜ 0/10 tâches (0%)
- Phase 6 : ⬜ 0/10 tâches (0%)
- Phase 7 : ⬜ 0/9 tâches (0%)
- Phase 8 : ⬜ 0/9 tâches (0%)

**Total : 39/84 tâches complétées (46%)**

### 🎉 Réalisations majeures
- ✅ Thème WordPress complet et fonctionnel
- ✅ Design harmonieux avec 120+ variables CSS globales
- ✅ Page d'accueil avec carrousel hero plein écran
- ✅ Page réalisations (archive + single) avec galerie interactive
- ✅ Page contact avec carte Google Maps en arrière-plan
- ✅ Header transparent avec logo PNG intégré
- ✅ Menu avec 3 animations (underline, glow pulse, scale bounce)
- ✅ Footer avec colonnes et animations identiques au contact
- ✅ Navigation responsive (desktop + mobile)
- ✅ Custom Post Type "Réalisations" avec taxonomies
- ✅ Animations et effets hover premium partout
- ✅ SEO et accessibilité optimisés (ARIA, roles, semantic HTML)
- ✅ Navigation avec pictogrammes animés
- ✅ Cartes réalisations avec style harmonieux
- ✅ Footer-bottom sur toutes les pages

### 🛠️ Prochaines étapes
1. Ajouter les favicons
2. Tests complets sur tous les navigateurs
3. Tests de performance (PageSpeed, GTmetrix)
