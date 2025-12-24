#!/bin/bash
# Script pour supprimer les fichiers WordPress core du dépôt
# Ces fichiers ne doivent PAS être dans le dossier du thème

echo "Suppression des fichiers WordPress core du dépôt Git..."

# Fichiers à supprimer s'ils existent dans le dépôt
files_to_remove=(
    "wp-settings.php"
    "wp-config.php"
    "wp-load.php"
    "wp-blog-header.php"
    "index.php"
)

for file in "${files_to_remove[@]}"; do
    if [ -f "$file" ]; then
        echo "Suppression de $file..."
        git rm "$file"
    else
        echo "$file non trouvé dans le dépôt"
    fi
done

echo "Vérification des fichiers dans .gitignore..."
echo "Assurez-vous que .gitignore contient:"
echo "wp-settings.php"
echo "wp-config.php"
echo "wp-load.php"
echo "wp-blog-header.php"
echo "wordpress/wp-config.php"
echo "wordpress/wp-settings.php"
echo "wordpress/wp-load.php"
echo "wordpress/wp-blog-header.php"
