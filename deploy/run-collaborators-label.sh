#!/usr/bin/env bash
set -euo pipefail
cd /var/www/asnen
php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); require 'deploy/sync-collaborators-label.php';"
php artisan view:clear
php artisan view:cache
php artisan config:clear
echo OK
