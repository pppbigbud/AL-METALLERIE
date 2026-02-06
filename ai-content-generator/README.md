# AI Content Generator for AL-Metallerie

Plugin WordPress de génération de contenu unique par IA utilisant Ollama (gratuit).

## Fonctionnalités

- 🤖 **Génération IA gratuite** avec Ollama (pas de coûts d'API)
- 📝 **Contenu unique** à chaque génération (anti-duplicate)
- 🎯 **Intégrations** avec les plugins existants
- 🏙️ **Pages villes** automatiques et uniques
- 🔧 **Réalisations** générées automatiquement
- 📊 **SEO optimisé** avec meta descriptions et améliorations

## Installation

### 1. Installer le Plugin

```bash
# Copier le dossier sur le serveur
scp -r ai-content-generator/ user@server:/path/to/wp-content/plugins/
```

Ou via l'interface WordPress :
1. Zipper le dossier `ai-content-generator`
2. Dans WordPress > Plugins > Ajouter > Envoyer
3. Uploader le ZIP et activer

### 2. Installer Ollama (Serveur)

Connectez-vous en SSH à votre serveur :

```bash
# Installer Ollama
curl -fsSL https://ollama.com/install.sh | sh

# Démarrer le service
systemctl start ollama
systemctl enable ollama

# Télécharger un modèle (recommandé)
ollama pull llama3.1:8b

# Alternative pour le français
ollama pull qwen2.5:7b

# Vérifier l'installation
ollama list
```

### 3. Configurer le Plugin

1. Dans WordPress, allez dans **AI Generator > Paramètres**
2. Vérifiez l'URL Ollama (généralement `http://localhost:11434`)
3. Sélectionnez le modèle par défaut
4. Ajustez la température (0.7 recommandé)

## Utilisation

### Générateur de contenu

1. Allez dans **AI Generator > Générateur**
2. Sélectionnez le type de contenu :
   - **Description de réalisation** : Pour les projets métallerie
   - **Page ville** : Pour les pages de localisation
   - **Meta description** : Pour le SEO
   - **Amélioration de contenu** : Enrichir un texte existant
   - **Témoignage** : Générer des avis clients

3. Remplissez les champs et cliquez sur **Générer**

### Intégration automatique

#### Pour les réalisations

Lors de la création d'une réalisation :
1. Remplissez les champs (type, matériaux, client)
2. La metabox "Génération IA" permet de générer le contenu
3. Le contenu peut être appliqué directement

#### Pour les pages villes

Le plugin s'intègre automatiquement avec `city-pages-generator` :
- Génère du contenu unique pour chaque ville
- Varie le style et le vocabulaire
- Inclut des éléments locaux pertinents

#### Pour le SEO

Dans **Analytics > SEO** :
- Les suggestions sont générées par l'IA
- Meta descriptions uniques
- Améliorations de contenu pertinentes

## Modèles IA recommandés

| Modèle | Taille | Usage | Français |
|--------|--------|-------|----------|
| `llama3.1:8b` | 8B | Équilibré | Bon |
| `qwen2.5:7b` | 7B | Français | Excellent |
| `mistral:7b` | 7B | Rapide | Moyen |
| `llama3.1:70b` | 70B | Haute qualité | Très bon |

## Personnalisation

### Ajouter des templates

Éditez `includes/class-content-templates.php` :

```php
// Ajouter un nouveau type de template
public function get_custom_prompt($data) {
    // Construire le prompt personnalisé
}
```

### Modifier les variations

Les variations de vocabulaire sont dans `$this->variations` :
- `introductions` : Phrases d'introduction
- `qualifiers` : Adjectifs qualificatifs
- `benefits` : Avantages et bénéfices
- `locations` : Localisations
- `conclusions` : Phrases de conclusion

## Dépannage

### Ollama ne répond pas

```bash
# Vérifier le service
systemctl status ollama

# Vérifier les logs
journalctl -u ollama -f

# Redémarrer
systemctl restart ollama
```

### Erreur de connexion

1. Vérifiez l'URL dans les paramètres
2. Testez avec : `curl http://localhost:11434/api/tags`
3. Vérifiez le firewall (port 11434)

### Contenu répétitif

- Augmentez la température dans les paramètres (0.8-1.0)
- Changez de modèle IA
- Ajoutez plus de variations dans les templates

## Performances

### Recommandations serveur

- **RAM** : 8GB minimum (16GB pour modèles 70B)
- **CPU** : 4 coeurs minimum
- **Stockage** : 10GB par modèle

### Optimisation

```bash
# Limiter l'utilisation RAM
export OLLAMA_MAX_LOADED_MODELS=1

# Utiliser GPU si disponible (NVIDIA)
export OLLAMA_GPU=nvidia
```

## Sécurité

- Ollama fonctionne en local (pas d'envoi de données externe)
- Les prompts sont filtrés côté serveur
- Le cache évite les régénérations inutiles

## Mises à jour

1. Sauvegarder les personnalisations
2. Remplacer les fichiers du plugin
3. Réactiver le plugin
4. Vider le cache si nécessaire

## Support

Pour toute question :
- Vérifier les logs WordPress
- Consulter la documentation Ollama
- Tester avec un prompt simple

## License

Plugin propriétaire pour AL-Metallerie Soudure
