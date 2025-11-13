# 📱 GUIDE DE PUBLICATION AUTOMATIQUE SUR LES RÉSEAUX SOCIAUX

## 🎉 SYSTÈME INSTALLÉ !

Votre thème WordPress AL Métallerie dispose maintenant d'un système complet de publication automatique sur les réseaux sociaux avec génération de contenu SEO.

---

## ✨ FONCTIONNALITÉS

### **1. Génération Automatique de Texte SEO** 🤖
- ✅ Description SEO optimisée (150-160 caractères, compatible Yoast)
- ✅ Texte adapté pour Facebook (conversationnel, émojis)
- ✅ Texte adapté pour Instagram (court, hashtags)
- ✅ Texte adapté pour LinkedIn (professionnel, technique)
- ✅ Utilise l'API Hugging Face (gratuite)

### **2. Publication Automatique** 📤
- ✅ Publication sur Facebook, Instagram et LinkedIn
- ✅ Choix des réseaux par réalisation
- ✅ Publication automatique ou manuelle
- ✅ Photo principale + galerie

### **3. Optimisation Automatique des Images** 📸
- ✅ Renommage SEO : `portail-acier-clermont-ferrand-2024.jpg`
- ✅ Attribut alt généré automatiquement
- ✅ Titre, légende et description optimisés
- ✅ Conforme aux recommandations SEO

---

## 🚀 UTILISATION

### **Créer une Nouvelle Réalisation**

1. **Aller dans** : Réalisations → Ajouter
2. **Remplir les informations** :
   - Titre
   - Client
   - Lieu (ex: Clermont-Ferrand)
   - Date de réalisation
   - Durée
   - Type (Portail, Garde-corps, etc.)
3. **Uploader les images** → Elles seront automatiquement renommées et optimisées !

### **Générer le Texte SEO**

1. **Scroller vers** : "✨ Générateur de Texte SEO"
2. **Cliquer sur** : "✨ Générer le texte SEO automatiquement"
3. **Attendre** : Le système génère 4 textes :
   - Description SEO (pour WordPress/Yoast)
   - Texte Facebook
   - Texte Instagram
   - Texte LinkedIn
4. **Modifier si besoin** : Les textes sont modifiables avant publication

### **Publier sur les Réseaux Sociaux**

#### **Option 1 : Publication Automatique**
1. **Dans la sidebar droite** : "📱 Publication sur les Réseaux Sociaux"
2. **Cocher** : "Activer la publication automatique"
3. **Sélectionner** : Facebook, Instagram, LinkedIn (au choix)
4. **Publier** : Cliquer sur "Publier" → Publication automatique !

#### **Option 2 : Publication Manuelle**
1. **Publier la réalisation** normalement
2. **Cliquer sur** : "🔄 Republier maintenant"
3. **Confirmer** : La publication se fait immédiatement

---

## ⚙️ CONFIGURATION DES API

### **Étape 1 : Configurer Hugging Face (GRATUIT)**

1. **Créer un compte** : [huggingface.co](https://huggingface.co/)
2. **Aller dans** : Settings → Access Tokens
3. **Créer un token** : Cliquer sur "New token"
   - Name : `AL Metallerie`
   - Type : `Read`
4. **Copier le token** : `hf_xxxxxxxxxxxxxxxxxxxxx`
5. **Dans WordPress** :
   - Aller dans : Réglages → Publication Sociale
   - Coller le token dans "Clé API Hugging Face"
   - Enregistrer

**✅ Gratuit : 1000 requêtes/mois** (largement suffisant)

---

### **Étape 2 : Configurer Facebook (PLUS TARD)**

#### **2.1 Créer une Application Facebook**
1. **Aller sur** : [developers.facebook.com](https://developers.facebook.com/)
2. **Créer une app** : Mes Apps → Créer une app
3. **Type** : Business
4. **Nom** : AL Métallerie Auto Post
5. **Email** : Votre email professionnel

#### **2.2 Configurer l'Application**
1. **Ajouter un produit** : Facebook Login
2. **Paramètres** :
   - Valid OAuth Redirect URIs : `https://votre-site.fr/wp-admin/`
3. **Récupérer** :
   - App ID : Dans Paramètres → Général
   - App Secret : Dans Paramètres → Général (Afficher)

#### **2.3 Obtenir le Page Access Token**
1. **Aller sur** : [developers.facebook.com/tools/explorer](https://developers.facebook.com/tools/explorer/)
2. **Sélectionner** : Votre app
3. **Permissions** : `pages_manage_posts`, `pages_read_engagement`
4. **Générer le token** : Cliquer sur "Generate Access Token"
5. **Prolonger le token** :
   ```
   https://graph.facebook.com/oauth/access_token?
   grant_type=fb_exchange_token&
   client_id=VOTRE_APP_ID&
   client_secret=VOTRE_APP_SECRET&
   fb_exchange_token=VOTRE_TOKEN_COURT
   ```

#### **2.4 Récupérer le Page ID**
1. **Aller sur votre page Facebook**
2. **Cliquer sur** : À propos
3. **Copier** : L'ID de la page (en bas)

#### **2.5 Dans WordPress**
- Aller dans : Réglages → Publication Sociale
- Remplir :
  - App ID
  - App Secret
  - Page ID
  - Access Token
- Enregistrer

---

### **Étape 3 : Configurer Instagram (PLUS TARD)**

#### **Prérequis**
- ✅ Compte Instagram Business
- ✅ Lié à une page Facebook
- ✅ API Facebook configurée

#### **3.1 Convertir en Compte Business**
1. **Dans l'app Instagram** : Paramètres → Compte
2. **Passer en compte professionnel**
3. **Lier à la page Facebook** : AL Métallerie

#### **3.2 Récupérer l'Instagram Business Account ID**
1. **Utiliser l'API Graph** :
   ```
   https://graph.facebook.com/v18.0/me/accounts?access_token=VOTRE_TOKEN
   ```
2. **Trouver** : `instagram_business_account` → `id`

#### **3.3 Dans WordPress**
- Aller dans : Réglages → Publication Sociale
- Remplir :
  - User ID : Instagram Business Account ID
  - Access Token : Même token que Facebook
- Enregistrer

---

### **Étape 4 : Configurer LinkedIn (PLUS TARD)**

#### **4.1 Créer une Application LinkedIn**
1. **Aller sur** : [linkedin.com/developers](https://www.linkedin.com/developers/)
2. **Créer une app** : Create app
3. **Remplir** :
   - App name : AL Métallerie Auto Post
   - LinkedIn Page : Votre page entreprise
   - App logo : Logo AL Métallerie

#### **4.2 Demander les Permissions**
1. **Products** : Demander "Share on LinkedIn"
2. **Attendre l'approbation** (quelques jours)

#### **4.3 Générer un Access Token**
1. **Auth** : Onglet Auth
2. **Redirect URLs** : `https://votre-site.fr/wp-admin/`
3. **Générer le token** :
   ```
   https://www.linkedin.com/oauth/v2/authorization?
   response_type=code&
   client_id=VOTRE_CLIENT_ID&
   redirect_uri=https://votre-site.fr/wp-admin/&
   scope=w_member_social
   ```
4. **Échanger le code** contre un token

#### **4.4 Récupérer l'Organization ID**
1. **API** :
   ```
   https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee
   ```
2. **Copier** : L'ID de votre organisation

#### **4.5 Dans WordPress**
- Aller dans : Réglages → Publication Sociale
- Remplir :
  - Client ID
  - Client Secret
  - Access Token
  - Organization ID
- Enregistrer

---

## 📊 EXEMPLE DE WORKFLOW

### **Scénario : Nouvelle Réalisation de Portail**

1. **Créer la réalisation** :
   - Titre : "Portail coulissant en acier"
   - Client : "M. Dupont"
   - Lieu : "Clermont-Ferrand"
   - Date : "2024-01-15"
   - Durée : "3 jours"
   - Type : "Portail"

2. **Uploader 5 photos** :
   - Automatiquement renommées :
     - `portail-acier-clermont-ferrand-2024-1.jpg`
     - `portail-acier-clermont-ferrand-2024-2.jpg`
     - etc.
   - Alt text généré : "Portail en acier Clermont-Ferrand - AL Métallerie"

3. **Générer les textes** :
   - Cliquer sur "✨ Générer le texte SEO"
   - Résultat :
     - **SEO** : "AL Métallerie vous présente sa réalisation de portail coulissant en acier à Clermont-Ferrand (janvier 2024). Découvrez notre savoir-faire en métallerie sur-mesure."
     - **Facebook** : "🔥 Nouvelle réalisation AL Métallerie ! 🔥\n\nNous sommes fiers de vous présenter notre dernier projet : Portail coulissant en acier à Clermont-Ferrand..."
     - **Instagram** : "✨ Portail coulissant en acier ✨\n\nNouvelle réalisation à Clermont-Ferrand 🔥\n\n#ALMetallerie #portail #Metallerie..."
     - **LinkedIn** : "Nouvelle réalisation AL Métallerie\n\nNous sommes heureux de partager notre dernière réalisation : Portail coulissant en acier à Clermont-Ferrand..."

4. **Activer la publication** :
   - Cocher : "Activer la publication automatique"
   - Sélectionner : Facebook, Instagram, LinkedIn
   - Publier !

5. **Résultat** :
   - ✅ Réalisation publiée sur WordPress
   - ✅ Post Facebook créé avec photo + galerie
   - ✅ Post Instagram créé avec hashtags
   - ✅ Post LinkedIn créé version professionnelle
   - ✅ Images optimisées SEO
   - ✅ Description Yoast SEO remplie

---

## 🎨 EXEMPLES DE NOMMAGE D'IMAGES

### **Avant (mauvais)** ❌
- `IMG_20240115_143052.jpg`
- `DSC_0001.jpg`
- `photo1.jpg`

### **Après (bon)** ✅
- `portail-acier-clermont-ferrand-2024.jpg`
- `garde-corps-inox-riom-2024.jpg`
- `escalier-aluminium-vichy-2024.jpg`

### **Format Automatique**
```
[type]-[matériau]-[lieu]-[année]-[numéro].jpg
```

**Exemples** :
- Portail en acier à Clermont → `portail-acier-clermont-ferrand-2024.jpg`
- Garde-corps inox à Riom → `garde-corps-inox-riom-2024.jpg`
- Escalier alu à Vichy → `escalier-aluminium-vichy-2024.jpg`

---

## 🔍 ATTRIBUTS ALT GÉNÉRÉS

### **Format**
```
[Type] en [Matériau] [Lieu] - AL Métallerie
```

### **Exemples**
- `Portail en acier Clermont-Ferrand - AL Métallerie`
- `Garde-corps en inox Riom - AL Métallerie`
- `Escalier en aluminium Vichy - AL Métallerie`

**Longueur** : Max 125 caractères (recommandation SEO)

---

## 📈 AVANTAGES SEO

### **Images Optimisées**
- ✅ Nom de fichier descriptif avec mots-clés
- ✅ Attribut alt rempli automatiquement
- ✅ Titre, légende et description SEO
- ✅ Meilleur référencement Google Images

### **Contenu Optimisé**
- ✅ Description 150-160 caractères (optimal Yoast)
- ✅ Mots-clés pertinents (métallerie, lieu, type)
- ✅ Texte unique et engageant
- ✅ Compatible Yoast SEO

### **Réseaux Sociaux**
- ✅ Visibilité accrue
- ✅ Trafic vers le site
- ✅ Engagement client
- ✅ Backlinks sociaux

---

## 🛠️ FICHIERS CRÉÉS

```
wordpress/wp-content/themes/almetal-theme/
├── inc/
│   ├── social-auto-publish.php      # Système principal
│   ├── seo-text-generator.php       # Générateur de texte
│   ├── image-optimizer.php          # Optimisation images
│   └── social-settings-page.php     # Page de configuration
└── assets/
    └── js/
        └── admin-social-publish.js  # Scripts admin
```

---

## ❓ FAQ

### **Q: Dois-je configurer toutes les API maintenant ?**
**R:** Non ! Vous pouvez commencer par Hugging Face (gratuit) pour la génération de texte. Les API des réseaux sociaux peuvent être configurées plus tard.

### **Q: Combien coûte Hugging Face ?**
**R:** Gratuit jusqu'à 1000 requêtes/mois. Largement suffisant pour vos besoins.

### **Q: Les images sont-elles automatiquement optimisées ?**
**R:** Oui ! Dès l'upload, les images sont renommées et les attributs SEO sont générés.

### **Q: Puis-je modifier les textes générés ?**
**R:** Oui ! Les textes sont modifiables avant publication.

### **Q: Que se passe-t-il si je ne configure pas les API ?**
**R:** Le système utilise des templates par défaut. La génération de texte fonctionne quand même, mais moins performante.

### **Q: Puis-je republier une réalisation ?**
**R:** Oui ! Utilisez le bouton "🔄 Republier maintenant" dans la meta box.

---

## 🎯 PROCHAINES ÉTAPES

### **Immédiat**
1. ✅ Configurer Hugging Face (5 min, gratuit)
2. ✅ Tester la génération de texte
3. ✅ Créer une réalisation test

### **Plus tard (quand vous aurez le temps)**
1. ⏳ Configurer Facebook API
2. ⏳ Configurer Instagram API
3. ⏳ Configurer LinkedIn API

---

## 📞 SUPPORT

Si vous avez des questions ou besoin d'aide pour configurer les API, n'hésitez pas à me contacter !

---

**🎉 FÉLICITATIONS ! Votre système de publication automatique est prêt !**
