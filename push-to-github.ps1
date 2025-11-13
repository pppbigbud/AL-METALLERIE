# Script PowerShell pour pousser le code sur GitHub
# Usage: .\push-to-github.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  AL Métallerie - Push vers GitHub" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Demander l'URL du dépôt GitHub
Write-Host "Entrez l'URL de votre dépôt GitHub:" -ForegroundColor Yellow
Write-Host "Exemple: https://github.com/VOTRE_USERNAME/almetal-v1.git" -ForegroundColor Gray
$repoUrl = Read-Host "URL"

if ([string]::IsNullOrWhiteSpace($repoUrl)) {
    Write-Host "❌ Erreur: URL vide" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "📋 Vérification du dépôt Git..." -ForegroundColor Cyan

# Vérifier si le remote existe déjà
$remoteExists = git remote get-url origin 2>$null

if ($remoteExists) {
    Write-Host "⚠️  Un remote 'origin' existe déjà: $remoteExists" -ForegroundColor Yellow
    $response = Read-Host "Voulez-vous le remplacer? (o/n)"
    
    if ($response -eq "o" -or $response -eq "O") {
        Write-Host "🔄 Suppression de l'ancien remote..." -ForegroundColor Cyan
        git remote remove origin
        Write-Host "✅ Ancien remote supprimé" -ForegroundColor Green
    } else {
        Write-Host "❌ Opération annulée" -ForegroundColor Red
        exit 0
    }
}

# Ajouter le nouveau remote
Write-Host "➕ Ajout du remote GitHub..." -ForegroundColor Cyan
git remote add origin $repoUrl

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erreur lors de l'ajout du remote" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Remote ajouté avec succès" -ForegroundColor Green
Write-Host ""

# Vérifier la branche
Write-Host "🔍 Vérification de la branche..." -ForegroundColor Cyan
$currentBranch = git branch --show-current

if ($currentBranch -ne "main") {
    Write-Host "🔄 Renommage de la branche en 'main'..." -ForegroundColor Cyan
    git branch -M main
    Write-Host "✅ Branche renommée" -ForegroundColor Green
}

Write-Host ""
Write-Host "🚀 Push vers GitHub..." -ForegroundColor Cyan
Write-Host "⚠️  GitHub va vous demander de vous authentifier" -ForegroundColor Yellow
Write-Host "💡 Utilisez votre Personal Access Token comme mot de passe" -ForegroundColor Gray
Write-Host ""

# Pousser vers GitHub
git push -u origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "  ✅ Code poussé sur GitHub avec succès!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "🌐 Votre dépôt est maintenant disponible sur:" -ForegroundColor Cyan
    Write-Host $repoUrl.Replace(".git", "") -ForegroundColor White
    Write-Host ""
    Write-Host "📖 Prochaine étape: Consultez DEPLOIEMENT_RENDER.md" -ForegroundColor Yellow
} else {
    Write-Host ""
    Write-Host "❌ Erreur lors du push" -ForegroundColor Red
    Write-Host "💡 Vérifiez vos identifiants GitHub" -ForegroundColor Yellow
    Write-Host "💡 Assurez-vous d'utiliser un Personal Access Token" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
