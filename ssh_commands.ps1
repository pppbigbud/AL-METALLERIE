$password = "D8Yc-n4KK-FYy+"
$server = "jezu0138@109.234.166.189"

# Commander 1: Analyse des thèmes
echo "=== 1. Analyse des thèmes ==="
ssh $server "cd /home/jezu0138/public_html && ls -la wp-content/themes/"

# Commander 2: Thème actif
echo "`n=== 2. Thème actif ==="
ssh $server "cd /home/jezu0138/public_html && mysql -u jezu0138 -pjezu0138 jezu0138_wp -e 'SELECT option_value FROM wp_options WHERE option_name=\"template\";'"

# Commander 3: Functions.php
echo "`n=== 3. Tous les functions.php ==="
ssh $server "cd /home/jezu0138/public_html && find . -name 'functions.php' -type f"

# Commander 4: deploy_theme.sh
echo "`n=== 4. Configuration deploy_theme.sh ==="
ssh $server "cd /home/jezu0138/public_html && grep 'DEST_THEME' deploy_theme.sh"

# Commander 5: Corriger le script
echo "`n=== 5. Correction du deploy_theme.sh ==="
ssh $server "cd /home/jezu0138/public_html && sed -i 's/almetal-theme/almetal/g' deploy_theme.sh"

# Commander 6: Supprimer l'ancien thème
echo "`n=== 6. Suppression de l'ancien thème ==="
ssh $server "cd /home/jezu0138/public_html && rm -rf wp-content/themes/almetal-theme"

# Commander 7: Vérification finale
echo "`n=== 7. Vérification finale ==="
ssh $server "cd /home/jezu0138/public_html && ls -la wp-content/themes/"
