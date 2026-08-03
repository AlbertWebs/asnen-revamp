#!/usr/bin/env bash
set -euo pipefail

# Permissions for PHP-FPM
sudo chown -R ubuntu:www-data /var/www/asnen
sudo find /var/www/asnen/storage /var/www/asnen/bootstrap/cache -type d -exec chmod 775 {} \;
sudo find /var/www/asnen/storage /var/www/asnen/bootstrap/cache -type f -exec chmod 664 {} \;
sudo chmod 664 /var/www/asnen/database/database.sqlite
sudo chmod 775 /var/www/asnen/database

# Install isolated nginx site (does not modify other vhosts)
sudo cp /var/www/asnen/deploy/nginx-asnen.conf /etc/nginx/sites-available/asnen
sudo ln -sfn /etc/nginx/sites-available/asnen /etc/nginx/sites-enabled/asnen

# Validate and reload only
sudo nginx -t
sudo systemctl reload nginx

echo '=== enabled sites (unchanged + asnen) ==='
ls -1 /etc/nginx/sites-enabled/

# Smoke test via Host header
curl -sI -H 'Host: asnen.designekta.com' http://127.0.0.1/ | head -20

echo NGINX_OK
