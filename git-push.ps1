# ============================================
# Script Git Push Rapide - AL Metallerie
# ============================================
# Usage: .\git-push.ps1 "Votre message de commit"
# Ou simplement: .\git-push.ps1 (message par défaut)
# ============================================

param(
    [string]$Message = ""
)

# Couleurs pour le terminal
$Green = "Green"
$Yellow = "Yellow"
$Red = "Red"
$Cyan = "Cyan"

Write-Host ""
Write-Host "========================================" -ForegroundColor $Cyan
Write-Host "   GIT PUSH - AL METALLERIE" -ForegroundColor $Cyan
Write-Host "========================================" -ForegroundColor $Cyan
Write-Host ""

# Afficher le statut actuel
Write-Host "[1/4] Verification du statut Git..." -ForegroundColor $Yellow
git status --short

$changes = git status --porcelain
if ([string]::IsNullOrEmpty($changes)) {
    Write-Host ""
    Write-Host "Aucune modification a commiter." -ForegroundColor $Green
    Write-Host ""
    exit 0
}

# Demander le message de commit si non fourni
if ([string]::IsNullOrEmpty($Message)) {
    Write-Host ""
    Write-Host "Fichiers modifies:" -ForegroundColor $Yellow
    git diff --stat --cached 2>$null
    git diff --stat 2>$null
    Write-Host ""
    $Message = Read-Host "Entrez votre message de commit (ou appuyez sur Entree pour un message par defaut)"
    
    if ([string]::IsNullOrEmpty($Message)) {
        $date = Get-Date -Format "dd/MM/yyyy HH:mm"
        $Message = "Update $date"
    }
}

# Ajouter tous les fichiers modifies
Write-Host ""
Write-Host "[2/4] Ajout des fichiers..." -ForegroundColor $Yellow
git add -A

# Commit
Write-Host "[3/4] Commit: $Message" -ForegroundColor $Yellow
git commit -m "$Message"

if ($LASTEXITCODE -ne 0) {
    Write-Host ""
    Write-Host "Erreur lors du commit!" -ForegroundColor $Red
    exit 1
}

# Push
Write-Host "[4/4] Push vers origin/master..." -ForegroundColor $Yellow
git push origin master

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor $Green
    Write-Host "   PUSH REUSSI !" -ForegroundColor $Green
    Write-Host "========================================" -ForegroundColor $Green
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "Erreur lors du push!" -ForegroundColor $Red
    Write-Host "Essayez: git pull origin master --rebase" -ForegroundColor $Yellow
    exit 1
}
