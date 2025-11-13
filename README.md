# 🏗️ Site WordPress AL Metallerie

Site WordPress personnalisé pour AL Metallerie avec thème responsive (one-page mobile, multi-pages desktop).

## 📋 Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop) installé et en cours d'exécution
- [Git](https://git-scm.com/) (optionnel, pour le versioning)

## 🚀 Installation et démarrage

### 1. Cloner ou télécharger le projet

```bash
cd "c:\Users\BIGBUD\Desktop\PROJETS\AL Metallerie\ALMETAL"
```

### 2. Lancer l'environnement Docker

```bash
docker-compose up -d
```

Cette commande va :
- Télécharger les images Docker nécessaires (première fois uniquement)
- Créer et démarrer les containers (WordPress, MySQL, phpMyAdmin)
- Configurer automatiquement la base de données

### 3. Accéder au site

- **WordPress** : http://localhost:8000
- **phpMyAdmin** : http://localhost:8080
  - Serveur : `db`
  - Utilisateur : `almetal_user`
  - Mot de passe : `almetal_password_2025`

### 4. Installation initiale de WordPress

Lors de la première visite sur http://localhost:8000, suivez l'assistant d'installation WordPress :
1. Choisissez la langue
2. Créez votre compte administrateur
3. Donnez un titre à votre site

## 🛠️ Commandes utiles

### Démarrer les containers
```bash
docker-compose up -d
```

### Arrêter les containers
```bash
docker-compose down
```

### Voir les logs
```bash
docker-compose logs -f
```

### Redémarrer les containers
```bash
docker-compose restart
```

### Arrêter et supprimer tout (y compris les données)
```bash
docker-compose down -v
```
⚠️ **Attention** : Cette commande supprime la base de données !

## 📁 Structure du projet

```
ALMETAL/
├── docker-compose.yml          # Configuration Docker
├── .env                        # Variables d'environnement
├── .gitignore                  # Fichiers à ignorer par Git
├── README.md                   # Ce fichier
├── CHECKLIST_PROJET_WORDPRESS.md  # Checklist du projet
└── wordpress/                  # Fichiers WordPress (créé automatiquement)
    └── wp-content/
        └── themes/
            └── almetal-theme/  # Votre thème personnalisé
```

## 🎨 Développement du thème

Le thème personnalisé se trouve dans :
```
wordpress/wp-content/themes/almetal-theme/
```

Après avoir créé le thème, activez-le depuis l'administration WordPress :
**Apparence > Thèmes > Almetal Theme**

## 🔧 Configuration

### Modifier les ports

Si les ports 8000 ou 8080 sont déjà utilisés, modifiez le fichier `.env` :

```env
WP_PORT=8001      # Nouveau port pour WordPress
PMA_PORT=8081     # Nouveau port pour phpMyAdmin
```

Puis redémarrez les containers :
```bash
docker-compose down
docker-compose up -d
```

### Mode debug

Pour activer/désactiver le mode debug WordPress, modifiez dans `.env` :
```env
WP_DEBUG=true   # ou false
```

## 📦 Export pour production (O2switch)

### 1. Exporter la base de données

Via phpMyAdmin (http://localhost:8080) :
1. Sélectionnez la base `almetal_db`
2. Onglet "Exporter"
3. Méthode : Rapide
4. Format : SQL
5. Téléchargez le fichier

### 2. Préparer les fichiers

Les fichiers WordPress se trouvent dans le dossier `wordpress/`

### 3. Adapter pour O2switch

Avant le déploiement :
- Modifier les URLs dans la base de données (search & replace)
- Mettre à jour `wp-config.php` avec les identifiants O2switch
- Désactiver le mode debug (`WP_DEBUG=false`)

## 🔒 Sécurité

⚠️ **Important** :
- Les mots de passe dans `.env` sont pour le développement local uniquement
- **Ne commitez JAMAIS le fichier `.env` sur Git**
- Changez tous les mots de passe pour la production
- Utilisez des mots de passe forts pour O2switch

## 📞 Support

Pour toute question sur le projet, consultez la `CHECKLIST_PROJET_WORDPRESS.md`

## 📝 Licence

Projet privé - AL Metallerie © 2025
