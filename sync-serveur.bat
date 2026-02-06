@echo off
REM Script de synchronisation SSH - AL METALLERIE
echo Connexion SSH et synchronisation...

REM Verifier les permissions .htaccess
echo === Verification permissions .htaccess ===
ssh jezu0138@109.234.166.189 "chmod 755 /home/jezu0138/public_html && chmod 644 /home/jezu0138/public_html/.htaccess && ls -la /home/jezu0138/public_html/.htaccess"

REM Telecharger les fichiers principaux
echo === Telechargement fichiers modifiees ===
scp jezu0138@109.234.166.189:/home/jezu0138/public_html/functions.php .\functions.php
scp jezu0138@109.234.166.189:/home/jezu0138/public_html/header.php .\header.php  
scp jezu0138@109.234.166.189:/home/jezu0138/public_html/footer.php .\footer.php
scp jezu0138@109.234.166.189:/home/jezu0138/public_html/single-realisation.php .\single-realisation.php
scp jezu0138@109.234.166.189:/home/jezu0138/public_html/taxonomy-type_realisation.php .\taxonomy-type_realisation.php
scp jezu0138@109.234.166.189:/home/jezu0138/public_html/archive-realisation.php .\archive-realisation.php

echo === Synchronisation terminee ===
pause
