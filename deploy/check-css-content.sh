#!/bin/bash
cd /var/www/asnen
CSS1=$(php -r '$m=json_decode(file_get_contents("public/build/manifest.json"),true); echo $m["resources/css/app.css"]["file"] ?? "";')
echo "manifest css: $CSS1"
echo "=== grep topbar in built css ==="
grep -c "site-topbar" "public/build/$CSS1" || echo "0 matches"
grep -c "who-hero" "public/build/$CSS1" || echo "0 who-hero"
ls -la public/build/assets/ | head -20
# also check second css file linked
curl -s https://asnen.designekta.com/about/who-we-are | grep -oE '/build/assets/[^"]+\.css'
for f in public/build/assets/*.css; do
  echo "$f size=$(wc -c < "$f") topbar=$(grep -c site-topbar "$f" || true) whohero=$(grep -c who-hero "$f" || true)"
done
