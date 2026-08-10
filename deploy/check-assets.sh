#!/bin/bash
set -e
cd /var/www/asnen
echo "=== public/hot ==="
ls -la public/hot 2>&1 || echo "no hot file"
if [ -f public/hot ]; then cat public/hot; fi
echo
echo "=== build ==="
ls -la public/build 2>&1 | head -15
echo
echo "=== manifest keys ==="
php -r 'echo json_encode(array_keys(json_decode(file_get_contents("public/build/manifest.json"), true) ?: []), JSON_PRETTY_PRINT), "\n";' 2>&1 | head -40
echo
echo "=== html asset tags ==="
curl -s https://asnen.designekta.com/about/who-we-are | grep -E 'stylesheet|type="module"|vite|5173|build/assets' | head -20
echo
echo "=== css status ==="
CSS=$(curl -s https://asnen.designekta.com/about/who-we-are | grep -oE '/build/assets/[^"]+\.css' | head -1)
echo "css path: $CSS"
if [ -n "$CSS" ]; then curl -sI "https://asnen.designekta.com$CSS" | head -10; fi
