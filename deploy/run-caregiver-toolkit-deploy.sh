#!/usr/bin/env bash
set -euo pipefail
cd /var/www/asnen

php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); require 'deploy/sync-caregiver-toolkit.php';"

php artisan view:clear
php artisan route:clear
php artisan route:cache

if command -v npm >/dev/null 2>&1; then
  npm run build
fi

php artisan view:cache
echo DEPLOY_OK
