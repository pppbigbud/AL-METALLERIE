@echo off
chcp 65001 >nul
echo.
echo ========================================
echo    GIT PUSH - AL METALLERIE
echo ========================================
echo.

cd /d "%~dp0"

echo [1/4] Statut Git...
git status --short
echo.

set /p MESSAGE="Message de commit (Entree = auto): "

if "%MESSAGE%"=="" (
    for /f "tokens=1-4 delims=/ " %%a in ('date /t') do set DATE=%%a/%%b/%%c
    for /f "tokens=1-2 delims=: " %%a in ('time /t') do set TIME=%%a:%%b
    set MESSAGE=Update %DATE% %TIME%
)

echo.
echo [2/4] Ajout des fichiers...
git add -A

echo [3/4] Commit: %MESSAGE%
git commit -m "%MESSAGE%"

echo [4/4] Push vers origin/master...
git push origin master

echo.
echo ========================================
echo    TERMINE !
echo ========================================
echo.
pause
