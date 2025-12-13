#!/bin/bash

set -euo pipefail

echo "Deploy theme: ALMETAL -> wp-content/themes/almetal-theme"

if [[ ! -f "wp-config.php" ]]; then
  echo "Error: run this script from WordPress public_html (wp-config.php not found)."
  exit 1
fi

DEST_THEME="wp-content/themes/almetal-theme"

mkdir -p "$DEST_THEME"

copy_file_if_exists() {
  local src="$1"
  local dst="$2"

  if [[ -f "$src" ]]; then
    mkdir -p "$(dirname "$dst")"
    cp -f "$src" "$dst"
  fi
}

# Root theme files
copy_file_if_exists "style.css" "$DEST_THEME/style.css"
copy_file_if_exists "functions.php" "$DEST_THEME/functions.php"
copy_file_if_exists "index.php" "$DEST_THEME/index.php"
copy_file_if_exists "screenshot.png" "$DEST_THEME/screenshot.png"
copy_file_if_exists "sitemap.xsl" "$DEST_THEME/sitemap.xsl"

# Copy all template PHP files at repo root (theme root)
for f in ./*.php; do
  if [[ -f "$f" ]]; then
    cp -f "$f" "$DEST_THEME/$(basename "$f")"
  fi
done

# Theme folders
if [[ -d "assets" ]]; then
  mkdir -p "$DEST_THEME/assets"
  rsync -a "assets/" "$DEST_THEME/assets/"
fi

if [[ -d "inc" ]]; then
  mkdir -p "$DEST_THEME/inc"
  rsync -a "inc/" "$DEST_THEME/inc/"
fi

if [[ -d "template-parts" ]]; then
  mkdir -p "$DEST_THEME/template-parts"
  rsync -a "template-parts/" "$DEST_THEME/template-parts/"
fi

echo "OK: theme deployed to $DEST_THEME"
