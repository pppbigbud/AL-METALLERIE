# 🚀 Guide Rapide - Pousser sur GitHub

## Étape 1 : Créer le dépôt sur GitHub

1. Allez sur **https://github.com**
2. Connectez-vous à votre compte
3. Cliquez sur le bouton **"+"** en haut à droite → **"New repository"**
4. Remplissez :
   - **Repository name** : `almetal-v1`
   - **Description** : `Site WordPress AL Métallerie - Version 1 pour démo client`
   - **Visibilité** : Choisissez **Private** (recommandé) ou Public
   - **NE COCHEZ PAS** "Initialize this repository with a README"
5. Cliquez sur **"Create repository"**

## Étape 2 : Pousser le code

GitHub vous affichera des instructions. Copiez l'URL de votre dépôt (elle ressemble à `https://github.com/VOTRE_USERNAME/almetal-v1.git`)

Ensuite, dans votre terminal PowerShell :

```powershell
# Se placer dans le dossier du projet
cd "c:\Users\BIGBUD\Desktop\PROJETS\AL Metallerie\ALMETAL"

# Ajouter le dépôt distant (remplacez l'URL par la vôtre)
git remote add origin https://github.com/VOTRE_USERNAME/almetal-v1.git

# Vérifier que la branche s'appelle "main"
git branch -M main

# Pousser le code
git push -u origin main
```

**Note** : GitHub vous demandera peut-être de vous authentifier. Utilisez un **Personal Access Token** au lieu du mot de passe.

### Créer un Personal Access Token (si nécessaire)

1. Sur GitHub, allez dans **Settings** (votre profil)
2. Cliquez sur **Developer settings** (en bas à gauche)
3. Cliquez sur **Personal access tokens** → **Tokens (classic)**
4. Cliquez sur **Generate new token** → **Generate new token (classic)**
5. Donnez un nom : `almetal-deploy`
6. Cochez : **repo** (accès complet aux dépôts)
7. Cliquez sur **Generate token**
8. **COPIEZ LE TOKEN** (vous ne pourrez plus le voir après)
9. Utilisez ce token comme mot de passe lors du `git push`

## Étape 3 : Vérifier

Retournez sur GitHub et actualisez la page de votre dépôt. Vous devriez voir tous vos fichiers !

## 📦 Prochaines étapes pour le déploiement

Consultez le fichier **DEPLOIEMENT_RENDER.md** pour les options de déploiement :

### Option recommandée pour démo client : **InfinityFree**

**Avantages** :
- ✅ Gratuit
- ✅ Supporte WordPress + MySQL
- ✅ Facile à configurer
- ✅ Parfait pour une démo

**Inconvénients** :
- ⚠️ Sous-domaine gratuit (ex: `almetal-demo.epizy.com`)
- ⚠️ Publicités en bas de page (version gratuite)

### Alternative : **000webhost**

Similaire à InfinityFree, également gratuit avec WordPress.

### Pour production finale : **O2switch**

Comme prévu initialement, pour l'hébergement définitif du client.

## 🎯 Résumé des fichiers créés

- ✅ **render.yaml** : Configuration Render (si vous choisissez Render)
- ✅ **Dockerfile.render** : Image Docker pour Render
- ✅ **DEPLOIEMENT_RENDER.md** : Guide complet de déploiement
- ✅ **README.md** : Documentation du projet
- ✅ **.gitignore** : Fichiers à ignorer par Git

## 💡 Commandes Git utiles

```powershell
# Voir l'état des fichiers
git status

# Voir l'historique des commits
git log --oneline

# Voir les dépôts distants
git remote -v

# Pousser les modifications futures
git add .
git commit -m "Description des modifications"
git push
```

---

**Besoin d'aide ?** Consultez le fichier **DEPLOIEMENT_RENDER.md** pour plus de détails !
