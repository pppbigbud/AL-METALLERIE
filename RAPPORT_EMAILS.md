# RAPPORT D'ANALYSE - REMPLACEMENT DES EMAILS
## Projet : AL Métallerie
## Date : 6 février 2026
## Objectif : Uniformiser toutes les adresses email vers contact@al-metallerie.fr

---

## 📊 STATISTIQUES GLOBALES

- **Total d'occurrences trouvées** : 208 dans 108 fichiers
- **Fichiers WordPress Core ignorés** : Oui (wp-includes, wp-admin, etc.)
- **Fichiers à modifier identifiés** : 4 fichiers principaux
- **Occurrences à remplacer** : `aurelien@al-metallerie.fr` → `contact@al-metallerie.fr`

---

## 📁 FICHIERS À MODIFIER

### 1. `page-contact.php`
**Emplacement** : `/public_html/page-contact.php`
**Occurrences** : 2

| Ligne | Type | Contenu actuel | Action |
|-------|------|----------------|--------|
| 76 | mailto | `href="mailto:aurelien@al-metallerie.fr"` | Remplacer par `contact@al-metallerie.fr` |
| 85 | texte affiché | `aurelien@al-metallerie.fr` | Remplacer par `contact@al-metallerie.fr` |

**Diff prévu :**
```diff
- <a href="mailto:aurelien@al-metallerie.fr" class="contact-info-item contact-email">
+ <a href="mailto:contact@al-metallerie.fr" class="contact-info-item contact-email">

- <span class="contact-info-value">aurelien@al-metallerie.fr</span>
+ <span class="contact-info-value">contact@al-metallerie.fr</span>
```

---

### 2. `page-contact-mobile.php`
**Emplacement** : `/public_html/page-contact-mobile.php`
**Occurrences** : 2

| Ligne | Type | Contenu actuel | Action |
|-------|------|----------------|--------|
| 52 | mailto | `href="mailto:contact@al-metallerie.fr"` | ✅ Déjà correct (mais texte incorrect) |
| 61 | texte affiché | `aurelien@al-metallerie.fr` | Remplacer par `contact@al-metallerie.fr` |

**Note** : Le lien mailto ligne 52 est déjà correct mais le texte affiché ligne 61 est incorrect.

**Diff prévu :**
```diff
- <p>aurelien@al-metallerie.fr</p>
+ <p>contact@al-metallerie.fr</p>
```

---

### 3. `inc/contact-handler.php`
**Emplacement** : `/public_html/inc/contact-handler.php`
**Occurrences** : 3

| Ligne | Type | Contenu actuel | Action |
|-------|------|----------------|--------|
| 65 | destinataire email | `$to = 'aurelien@al-metallerie.fr';` | Remplacer par `contact@al-metallerie.fr` |
| 158 | email confirmation | `aurelien@al-metallerie.fr` | Remplacer par `contact@al-metallerie.fr` |
| 173 | header From | `From: AL Métallerie <aurelien@al-metallerie.fr>` | Remplacer par `contact@al-metallerie.fr` |

**Diff prévu :**
```diff
- $to = 'aurelien@al-metallerie.fr';
+ $to = 'contact@al-metallerie.fr';

- <li>Email : <a href="mailto:aurelien@al-metallerie.fr">aurelien@al-metallerie.fr</a></li>
+ <li>Email : <a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></li>

- 'From: AL Métallerie <aurelien@al-metallerie.fr>'
+ 'From: AL Métallerie <contact@al-metallerie.fr>'
```

---

### 4. `inc/seo-local.php`
**Emplacement** : `/public_html/inc/seo-local.php`
**Occurrences** : 2

| Ligne | Type | Contenu actuel | Action |
|-------|------|----------------|--------|
| 102 | données entreprise | `'email' => 'aurelien@al-metallerie.fr',` | Remplacer par `contact@al-metallerie.fr` |
| 754 | lien contact | `aurelien@al-metallerie.fr` | Remplacer par `contact@al-metallerie.fr` |

**Diff prévu :**
```diff
- 'email' => 'aurelien@al-metallerie.fr',
+ 'email' => 'contact@al-metallerie.fr',

- <a href="mailto:aurelien@al-metallerie.fr" style="color: #F08B18;">aurelien@al-metallerie.fr</a>
+ <a href="mailto:contact@al-metallerie.fr" style="color: #F08B18;">contact@al-metallerie.fr</a>
```

---

## ✅ FICHIERS DÉJÀ CORRECTS (PAS DE MODIFICATION NÉCESSAIRE)

| Fichier | Ligne(s) | Email présent |
|---------|----------|---------------|
| `footer.php` | 75, 84 | contact@al-metallerie.fr ✅ |
| `page-mentions-legales.php` | 100, 114, 211, 284 | contact@al-metallerie.fr ✅ |
| `page-politique-confidentialite.php` | 70, 201 | contact@al-metallerie.fr ✅ |
| `single-city_page.php` | 309, 314 | contact@al-metallerie.fr ✅ |
| `template-parts/mobile-onepage.php` | 251, 260 | contact@al-metallerie.fr ✅ |
| `inc/seo-text-generator.php` | 908, 1165, 1191, 1209, 1232, 1255 | contact@al-metallerie.fr ✅ |
| `inc/seo-local.php` | 343, 487 | contact@al-metallerie.fr ✅ |
| `inc/custom-post-types.php` | 642 | contact@al-metallerie.fr ✅ |
| `city-pages-generator/includes/class-groq-generator-v2.php` | 79, 167 | contact@al-metallerie.fr ✅ |
| `city-pages-generator/includes/class-content-generator-fixed.php` | 256 | contact@al-metallerie.fr ✅ |

---

## 📋 PLAN D'ACTION

### Phase 1 : Modification sur le serveur (O2switch)
- [ ] Modifier `page-contact.php`
- [ ] Modifier `page-contact-mobile.php`
- [ ] Modifier `inc/contact-handler.php`
- [ ] Modifier `inc/seo-local.php`

### Phase 2 : Validation utilisateur
- [ ] Demander validation avant MAJ locale
- [ ] Demander validation avant MAJ Git

### Phase 3 : Synchronisation
- [ ] Mettre à jour version locale
- [ ] Commit et push Git

---

## ⚠️ NOTES IMPORTANTES

1. **Le fichier `footer.php` est déjà correct** - ne pas toucher
2. **Les fichiers WordPress Core sont ignorés** - ils contiennent des emails de test/exemple qui ne doivent pas être modifiés
3. **Tous les liens `mailto:` et textes affichés** sont concernés
4. **Les placeholders de formulaire** (ex: `votre@email.com`) ne sont PAS des emails réels et ne doivent pas être modifiés

---

## 🔄 RÉSUMÉ DES REMPLACEMENTS

**Ancienne adresse** : `aurelien@al-metallerie.fr`  
**Nouvelle adresse** : `contact@al-metallerie.fr`  
**Nombre total de remplacements** : 7 occurrences dans 4 fichiers

---

Généré automatiquement le 6 février 2026
