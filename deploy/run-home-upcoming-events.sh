#!/usr/bin/env bash
set -euo pipefail
cd /var/www/asnen
php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); require 'deploy/sync-home-upcoming-events.php';"
npm run build
php artisan view:clear
php artisan view:cache
echo OK
