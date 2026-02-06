#!/bin/bash
# Script de remplacement des emails sur le serveur O2switch
# Date: 6 février 2026

cd /home/jezu0138/public_html

echo "=== Remplacement des emails: aurelien@al-metallerie.fr -> contact@al-metallerie.fr ==="

# 1. page-contact.php
echo "Modification de page-contact.php..."
sed -i 's/aurelien@al-metallerie.fr/contact@al-metallerie.fr/g' page-contact.php
echo "✓ page-contact.php modifié"

# 2. page-contact-mobile.php
echo "Modification de page-contact-mobile.php..."
sed -i 's/aurelien@al-metallerie.fr/contact@al-metallerie.fr/g' page-contact-mobile.php
echo "✓ page-contact-mobile.php modifié"

# 3. inc/contact-handler.php
echo "Modification de inc/contact-handler.php..."
sed -i 's/aurelien@al-metallerie.fr/contact@al-metallerie.fr/g' inc/contact-handler.php
echo "✓ inc/contact-handler.php modifié"

# 4. inc/seo-local.php
echo "Modification de inc/seo-local.php..."
sed -i 's/aurelien@al-metallerie.fr/contact@al-metallerie.fr/g' inc/seo-local.php
echo "✓ inc/seo-local.php modifié"

echo ""
echo "=== Vérification des modifications ==="
grep -n "contact@al-metallerie.fr" page-contact.php page-contact-mobile.php inc/contact-handler.php inc/seo-local.php | head -20

echo ""
echo "=== Modifications terminées ==="
