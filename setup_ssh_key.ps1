# Script pour générer et configurer une clé SSH
Write-Host "=== Génération de la clé SSH ===" -ForegroundColor Green

# Créer le dossier .ssh s'il n'existe pas
$sshDir = "$env:USERPROFILE\.ssh"
if (-not (Test-Path $sshDir)) {
    New-Item -ItemType Directory -Path $sshDir | Out-Null
}

# Générer la clé SSH sans passphrase
ssh-keygen -t ed25519 -f "$sshDir\almetal_key" -N ""

Write-Host "Clé générée : $sshDir\almetal_key" -ForegroundColor Yellow
Write-Host "Clé publique  : $sshDir\almetal_key.pub" -ForegroundColor Yellow

# Copier la clé publique sur le serveur
Write-Host "`n=== Copie de la clé sur le serveur ===" -ForegroundColor Green
$pubKey = Get-Content "$sshDir\almetal_key.pub"

# Se connecter et ajouter la clé
ssh jezu0138@109.234.166.189 "mkdir -p ~/.ssh && echo '$pubKey' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys && chmod 700 ~/.ssh"

Write-Host "`n=== Configuration terminée ===" -ForegroundColor Green
Write-Host "Vous pouvez maintenant vous connecter sans mot de passe avec :" -ForegroundColor Cyan
Write-Host "ssh -i $sshDir\almetal_key jezu0138@109.234.166.189" -ForegroundColor Cyan
