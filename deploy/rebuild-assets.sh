#!/usr/bin/env bash
set -euo pipefail
cd /var/www/asnen
npm run build
php artisan view:clear
php artisan view:cache
echo THUMBS_OK
