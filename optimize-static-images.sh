#!/bin/bash
# =============================================================================
# Script d'optimisation des images statiques pour AL Métallerie
# Exécuter sur le serveur avec: bash optimize-static-images.sh
# Prérequis: ImageMagick (convert) ou cwebp installé
# =============================================================================

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Répertoire de base
THEME_DIR="$HOME/public_html/wp-content/themes/almetal-theme"
ASSETS_DIR="$THEME_DIR/assets/images"

echo -e "${GREEN}=== Optimisation des images statiques AL Métallerie ===${NC}"
echo ""

# Vérifier si ImageMagick est installé
if ! command -v convert &> /dev/null; then
    echo -e "${RED}ImageMagick n'est pas installé. Installation...${NC}"
    # Sur o2switch, utiliser la version disponible ou demander au support
    echo -e "${YELLOW}Contactez le support o2switch pour installer ImageMagick si nécessaire.${NC}"
fi

# Vérifier si cwebp est installé
if ! command -v cwebp &> /dev/null; then
    echo -e "${YELLOW}cwebp n'est pas installé. Utilisation de convert pour WebP.${NC}"
    USE_CWEBP=false
else
    USE_CWEBP=true
fi

# Fonction pour optimiser une image
optimize_image() {
    local input="$1"
    local output="$2"
    local width="$3"
    local height="$4"
    local quality="${5:-75}"
    
    if [ -f "$input" ]; then
        echo -e "  ${YELLOW}→${NC} Optimisation: $(basename "$input") → ${width}x${height}"
        
        # Créer le répertoire de sortie si nécessaire
        mkdir -p "$(dirname "$output")"
        
        # Redimensionner et convertir en WebP
        if [ "$USE_CWEBP" = true ] && [[ "$output" == *.webp ]]; then
            # Utiliser convert pour redimensionner, puis cwebp pour compresser
            convert "$input" -resize "${width}x${height}^" -gravity center -extent "${width}x${height}" -quality "$quality" "/tmp/temp_resize.png"
            cwebp -q "$quality" "/tmp/temp_resize.png" -o "$output" 2>/dev/null
            rm -f "/tmp/temp_resize.png"
        else
            # Utiliser uniquement ImageMagick
            convert "$input" -resize "${width}x${height}^" -gravity center -extent "${width}x${height}" -quality "$quality" "$output"
        fi
        
        if [ -f "$output" ]; then
            local original_size=$(stat -f%z "$input" 2>/dev/null || stat -c%s "$input" 2>/dev/null)
            local new_size=$(stat -f%z "$output" 2>/dev/null || stat -c%s "$output" 2>/dev/null)
            local saved=$((original_size - new_size))
            echo -e "    ${GREEN}✓${NC} Économie: $((saved / 1024)) Ko"
        fi
    else
        echo -e "  ${RED}✗${NC} Fichier non trouvé: $input"
    fi
}

# =============================================================================
# 1. LOGO (81x80 affiché)
# =============================================================================
echo -e "${GREEN}[1/4] Optimisation du logo${NC}"

LOGO_SOURCE="$ASSETS_DIR/logo.webp"
LOGO_OPTIMIZED="$ASSETS_DIR/logo-optimized.webp"

if [ -f "$LOGO_SOURCE" ]; then
    optimize_image "$LOGO_SOURCE" "$LOGO_OPTIMIZED" 100 100 80
    
    # Créer aussi une version pour le header mobile
    optimize_image "$LOGO_SOURCE" "$ASSETS_DIR/logo-mobile.webp" 60 60 80
else
    echo -e "  ${YELLOW}Logo source non trouvé, vérification du PNG...${NC}"
    if [ -f "$ASSETS_DIR/logo.png" ]; then
        optimize_image "$ASSETS_DIR/logo.png" "$LOGO_OPTIMIZED" 100 100 80
        optimize_image "$ASSETS_DIR/logo.png" "$ASSETS_DIR/logo-mobile.webp" 60 60 80
    fi
fi

# =============================================================================
# 2. IMAGES GALLERY (pexels-kelly: 400x498, pexels-rik: 400x267)
# =============================================================================
echo ""
echo -e "${GREEN}[2/4] Optimisation des images gallery${NC}"

GALLERY_DIR="$ASSETS_DIR/gallery"

# pexels-kelly-2950108 (affichée 400x498)
if [ -f "$GALLERY_DIR/pexels-kelly-2950108 1.webp" ]; then
    optimize_image "$GALLERY_DIR/pexels-kelly-2950108 1.webp" "$GALLERY_DIR/pexels-kelly-optimized.webp" 400 500 70
elif [ -f "$GALLERY_DIR/pexels-kelly-2950108 1.png" ]; then
    optimize_image "$GALLERY_DIR/pexels-kelly-2950108 1.png" "$GALLERY_DIR/pexels-kelly-optimized.webp" 400 500 70
fi

# pexels-rik-schots (affichée 400x267)
if [ -f "$GALLERY_DIR/pexels-rik-schots-11624248 2.webp" ]; then
    optimize_image "$GALLERY_DIR/pexels-rik-schots-11624248 2.webp" "$GALLERY_DIR/pexels-rik-optimized.webp" 400 267 70
elif [ -f "$GALLERY_DIR/pexels-rik-schots-11624248 2.png" ]; then
    optimize_image "$GALLERY_DIR/pexels-rik-schots-11624248 2.png" "$GALLERY_DIR/pexels-rik-optimized.webp" 400 267 70
fi

# pexels-arthur-krijgsman (si utilisée)
if [ -f "$GALLERY_DIR/pexels-arthur-krijgsman-6036670 2.webp" ]; then
    optimize_image "$GALLERY_DIR/pexels-arthur-krijgsman-6036670 2.webp" "$GALLERY_DIR/pexels-arthur-optimized.webp" 400 300 70
fi

# =============================================================================
# 3. IMAGES HERO/SLIDESHOW (640x300 pour desktop)
# =============================================================================
echo ""
echo -e "${GREEN}[3/4] Optimisation des images hero/slideshow${NC}"

HERO_DIR="$ASSETS_DIR/hero"
SLIDESHOW_DIR="$HOME/public_html/wp-content/uploads/slideshow-optimized"

# Créer le répertoire slideshow-optimized s'il n'existe pas
mkdir -p "$SLIDESHOW_DIR"

# Optimiser les images du slideshow existantes
if [ -d "$HOME/public_html/wp-content/uploads/2024/12" ]; then
    echo -e "  ${YELLOW}Recherche des images slideshow dans uploads...${NC}"
    
    # Trouver et optimiser les grandes images WebP
    find "$HOME/public_html/wp-content/uploads" -name "*.webp" -size +100k -type f 2>/dev/null | head -20 | while read img; do
        filename=$(basename "$img")
        if [[ ! "$filename" == *"-optimized"* ]]; then
            output_name="${filename%.*}-optimized.webp"
            optimize_image "$img" "$SLIDESHOW_DIR/$output_name" 640 300 70
        fi
    done
fi

# =============================================================================
# 4. RAPPORT FINAL
# =============================================================================
echo ""
echo -e "${GREEN}[4/4] Rapport final${NC}"
echo ""

# Calculer les économies totales
echo -e "${GREEN}=== Images optimisées créées ===${NC}"

if [ -d "$ASSETS_DIR" ]; then
    find "$ASSETS_DIR" -name "*-optimized.webp" -o -name "*-mobile.webp" 2>/dev/null | while read f; do
        size=$(stat -f%z "$f" 2>/dev/null || stat -c%s "$f" 2>/dev/null)
        echo -e "  ${GREEN}✓${NC} $(basename "$f") - $((size / 1024)) Ko"
    done
fi

if [ -d "$SLIDESHOW_DIR" ]; then
    find "$SLIDESHOW_DIR" -name "*-optimized.webp" 2>/dev/null | while read f; do
        size=$(stat -f%z "$f" 2>/dev/null || stat -c%s "$f" 2>/dev/null)
        echo -e "  ${GREEN}✓${NC} $(basename "$f") - $((size / 1024)) Ko"
    done
fi

echo ""
echo -e "${GREEN}=== Terminé ! ===${NC}"
echo ""
echo -e "${YELLOW}IMPORTANT: Mettez à jour le code PHP pour utiliser les nouvelles images :${NC}"
echo "  - logo.webp → logo-optimized.webp"
echo "  - pexels-kelly-2950108 1.webp → pexels-kelly-optimized.webp"
echo "  - pexels-rik-schots-11624248 2.webp → pexels-rik-optimized.webp"
echo ""
echo -e "${YELLOW}Ou exécutez le script PHP de mise à jour des chemins.${NC}"
