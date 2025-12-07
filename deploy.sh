#!/bin/bash
# ==============================================
# Script de déploiement AL Métallerie
# Exécuter après chaque git pull
# ==============================================

echo "🚀 Déploiement AL Métallerie..."

# Vérifier qu'on est dans public_html
if [[ ! -f "wp-config.php" ]]; then
    echo "❌ Erreur: Exécutez ce script depuis public_html"
    exit 1
fi

# 1. Copier le thème (avec rsync pour éviter les conflits)
echo "📁 Mise à jour du thème..."
if [[ -d "wordpress/wp-content/themes/almetal-theme" ]]; then
    # Supprimer les anciens fichiers cookie du thème s'ils existent
    rm -f wp-content/themes/almetal-theme/assets/js/cookie-consent.js 2>/dev/null
    rm -f wp-content/themes/almetal-theme/assets/css/cookie-banner.css 2>/dev/null
    
    # Copier le thème
    cp -r wordpress/wp-content/themes/almetal-theme/* wp-content/themes/almetal-theme/
    echo "   ✅ Thème mis à jour"
else
    echo "   ⚠️ Dossier thème non trouvé dans wordpress/"
fi

# 2. Copier le plugin analytics
echo "📁 Mise à jour du plugin Analytics..."
if [[ -d "wordpress/wp-content/plugins/almetal-analytics" ]]; then
    mkdir -p wp-content/plugins/almetal-analytics
    cp -r wordpress/wp-content/plugins/almetal-analytics/* wp-content/plugins/almetal-analytics/
    
    # Corriger le bug des checkboxes si nécessaire
    sed -i "s/\${cat.default || cat.required ? 'checked' : ''}/checked/g" wp-content/plugins/almetal-analytics/assets/js/cookie-banner.js 2>/dev/null
    
    echo "   ✅ Plugin Analytics mis à jour"
else
    echo "   ⚠️ Plugin Analytics non trouvé dans wordpress/"
fi

# 3. Copier robots.txt et sitemap.xml si présents
if [[ -f "wordpress/robots.txt" ]]; then
    cp wordpress/robots.txt ./robots.txt
    echo "   ✅ robots.txt mis à jour"
fi

if [[ -f "wordpress/sitemap.xml" ]]; then
    cp wordpress/sitemap.xml ./sitemap.xml
    echo "   ✅ sitemap.xml mis à jour"
fi

# 4. Vider le cache LiteSpeed
echo "🧹 Vidage du cache..."
if [[ -d "wp-content/litespeed" ]]; then
    rm -rf wp-content/litespeed/*
    echo "   ✅ Cache LiteSpeed vidé"
fi

# 5. Supprimer le dossier wordpress (pour garder propre)
echo "🗑️ Nettoyage..."
rm -rf wordpress/
echo "   ✅ Dossier wordpress/ supprimé"

# 6. Supprimer les fichiers obsolètes
echo "🧹 Suppression fichiers obsolètes..."
rm -f wp-content/themes/almetal-theme/assets/js/cookie-consent.js 2>/dev/null
rm -f wp-content/themes/almetal-theme/assets/css/cookie-banner.css 2>/dev/null
echo "   ✅ Fichiers obsolètes supprimés"

echo ""
echo "✅ Déploiement terminé !"
echo "   Vérifiez votre site: https://al-metallerie-soudure.fr"
echo "   Version mobile: https://al-metallerie-soudure.fr/?force_mobile=1"
