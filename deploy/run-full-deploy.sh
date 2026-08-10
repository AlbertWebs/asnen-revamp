#!/usr/bin/env bash
# Run on the production server after code + build tarballs are uploaded.
set -euo pipefail
cd /var/www/asnen

echo "== Extract code =="
sudo tar -xzf /tmp/asnen-code.tar.gz -C /var/www/asnen
sudo chown -R ubuntu:www-data /var/www/asnen

echo "== Extract build assets =="
if [ -f /tmp/asnen-build.tar.gz ]; then
  sudo rm -rf public/build
  sudo tar -xzf /tmp/asnen-build.tar.gz -C public
  sudo chown -R ubuntu:www-data public/build
  sudo chmod -R u=rwX,g=rX,o=rX public/build
fi

echo "== Composer =="
composer install --no-dev --optimize-autoloader --no-interaction

echo "== Storage link =="
php artisan storage:link --force 2>/dev/null || true

echo "== Migrate =="
php artisan migrate --force

echo "== Brand logo setting =="
php deploy/ensure-brand-logo-setting.php

echo "== Caches =="
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "== Cleanup =="
rm -f /tmp/asnen-code.tar.gz /tmp/asnen-build.tar.gz /tmp/asnen-deploy.sh

echo "DEPLOY_OK"
