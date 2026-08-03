#!/usr/bin/env bash
set -euo pipefail
cd /var/www/asnen

rm -rf /tmp/nginx-asnen.conf || true

if [ ! -f .env ]; then
  cp .env.example .env
fi

python3 <<'PY'
from pathlib import Path
p = Path('/var/www/asnen/.env')
text = p.read_text()
replacements = {
    'APP_NAME': 'ASNEN',
    'APP_ENV': 'production',
    'APP_DEBUG': 'false',
    'APP_URL': 'https://asnen.designekta.com',
    'APP_TIMEZONE': 'Africa/Nairobi',
    'DB_CONNECTION': 'sqlite',
    'SESSION_DRIVER': 'database',
    'QUEUE_CONNECTION': 'database',
    'CACHE_STORE': 'database',
    'LOG_LEVEL': 'error',
    'MAIL_MAILER': 'log',
}
lines = []
seen = set()
for line in text.splitlines():
    if not line or line.startswith('#') or '=' not in line:
        lines.append(line)
        continue
    key = line.split('=', 1)[0]
    if key in replacements:
        lines.append(f"{key}={replacements[key]}")
        seen.add(key)
    else:
        lines.append(line)
for key, val in replacements.items():
    if key not in seen:
        lines.append(f"{key}={val}")
out = []
for line in lines:
    if line.startswith('DB_DATABASE='):
        continue
    out.append(line)
out.append('DB_DATABASE=/var/www/asnen/database/database.sqlite')
p.write_text('\n'.join(out) + '\n')
print('ENV_WRITTEN')
PY

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
chmod 664 database/database.sqlite
chgrp -R www-data storage bootstrap/cache database || true
chmod g+w database

echo '=== composer ==='
composer install --no-dev --optimize-autoloader --no-interaction

echo '=== key ==='
php artisan key:generate --force
# Ensure public/storage is a symlink (a real directory breaks new uploads)
if [ -e public/storage ] && [ ! -L public/storage ]; then
  rsync -a public/storage/ storage/app/public/ || true
  rm -rf public/storage
fi
php artisan storage:link || true

echo '=== npm ==='
npm ci --no-audit --no-fund
npm run build

echo '=== optimize ==='
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo SETUP_OK
