# 🔧 Script de création de la table contacts

## Méthode 1 : Via phpMyAdmin (RECOMMANDÉ)

1. **Ouvrir phpMyAdmin** : http://localhost:8080
2. **Sélectionner la base** : `almetal_db`
3. **Onglet SQL**
4. **Copier-coller ce code** :

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

5. **Cliquer sur "Exécuter"**
6. **Vérifier** : La table `wp_almetal_contacts` doit apparaître dans la liste

---

## Méthode 2 : Via WordPress (automatique)

La table devrait se créer automatiquement maintenant. Pour forcer la création :

1. **Aller dans WordPress Admin**
2. **Apparence → Thèmes**
3. **Activer un autre thème** (Twenty Twenty-Three par exemple)
4. **Réactiver AL Métallerie**
5. **Vérifier** : Aller dans **Contacts** (menu admin)

---

## Méthode 3 : Via WP-CLI (si disponible)

```bash
wp db query "CREATE TABLE IF NOT EXISTS wp_almetal_contacts (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  phone varchar(50) NOT NULL,
  email varchar(255) NOT NULL,
  project_type varchar(100) NOT NULL,
  message text NOT NULL,
  submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
```

---

## Vérification

Pour vérifier que la table existe :

### Via phpMyAdmin
1. Base de données `almetal_db`
2. Chercher `wp_almetal_contacts` dans la liste des tables

### Via SQL
```sql
SHOW TABLES LIKE 'wp_almetal_contacts';
```

### Via WordPress
1. **Admin → Contacts**
2. Si la page s'affiche sans erreur → ✅ Table créée

---

## Structure de la table

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | mediumint(9) | Identifiant unique (auto-incrémenté) |
| `name` | varchar(255) | Nom complet du contact |
| `phone` | varchar(50) | Numéro de téléphone |
| `email` | varchar(255) | Adresse email |
| `project_type` | varchar(100) | Type de projet |
| `message` | text | Message du contact |
| `submitted_at` | datetime | Date et heure de soumission |

---

## Données de test

Pour insérer des données de test :

```sql
INSERT INTO `wp_almetal_contacts` 
(`name`, `phone`, `email`, `project_type`, `message`) 
VALUES 
('Jean Dupont', '06 12 34 56 78', 'jean.dupont@example.com', 'portail', 'Je souhaite un devis pour un portail coulissant.'),
('Marie Martin', '06 98 76 54 32', 'marie.martin@example.com', 'garde-corps', 'Besoin d\'un garde-corps pour une terrasse.');
```

---

**Après avoir créé la table, rechargez la page Admin → Contacts**
