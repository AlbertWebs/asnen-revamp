#!/bin/bash
HTML=$(curl -s https://asnen.designekta.com/about/who-we-are)
echo "$HTML" | grep -oE '/build/assets/[^" ]+\.css' | sort -u
CSS=$(echo "$HTML" | grep -oE '/build/assets/app-Cd[^" ]+\.css' | head -1)
echo "new css: $CSS"
if [ -n "$CSS" ]; then
  curl -sI "https://asnen.designekta.com$CSS" | head -8
fi
echo "topbar in page: $(echo "$HTML" | grep -c site-topbar)"
