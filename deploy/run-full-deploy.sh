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
# A real directory at public/storage breaks new uploads (files land in
# storage/app/public but the web root never sees them). Merge then relink.
if [ -d public/storage ] && [ ! -L public/storage ]; then
  echo "Merging public/storage directory into storage/app/public..."
  rsync -a public/storage/ storage/app/public/
  rm -rf public/storage
fi
php artisan storage:link --force 2>/dev/null || true

echo "== Storage permissions =="
mkdir -p storage/app/public/{uploads,hero,brand,programs,stories,events,partners,team,resources,gallery}
chown -R ubuntu:www-data storage bootstrap/cache || true
find storage -type d -exec chmod 2775 {} \;
find storage -type f -exec chmod 664 {} \;
chmod -R g+w storage/app/public

echo "== Remove broken media rows (path 0) =="
php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$q = App\Models\MediaAsset::withTrashed()->where(function ($q) {
    $q->where("path", "0")->orWhere("path", "")->orWhereNull("path");
});
$n = $q->count();
$q->forceDelete();
echo "removed={$n}\n";
'

echo "== Migrate =="
php artisan migrate --force

echo "== Backfill media content hashes =="
php deploy/backfill-media-hashes.php

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
