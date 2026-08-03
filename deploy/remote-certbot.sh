#!/usr/bin/env bash
# Run after Cloudflare A record for asnen.designekta.com -> 13.50.244.3 exists.
set -euo pipefail

cd /var/www/asnen
# Pull trustProxies change if present; otherwise ignore
php artisan config:cache || true

sudo certbot --nginx -d asnen.designekta.com --non-interactive --agree-tos --register-unsafely-without-email --redirect
sudo nginx -t
sudo systemctl reload nginx

echo CERTBOT_OK
