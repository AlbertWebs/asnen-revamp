# Deployment Guide — ASNEN CMS

This guide covers deploying the Africa Special Needs Education Network website and admin CMS to production.

## Server requirements

- PHP 8.2+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` or `imagick`
- MySQL 8.0+ (production) or MariaDB equivalent
- Node.js 20+ (build step only)
- Composer 2.x
- A web server (Nginx or Apache) pointing to `public/`

## Environment configuration

Copy `.env.example` to `.env` and configure:

```env
APP_NAME=ASNEN
APP_ENV=production
APP_DEBUG=false
APP_URL=https://asnenafrica.org
APP_TIMEZONE=Africa/Nairobi

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asnen
DB_USERNAME=asnen
DB_PASSWORD=<strong-password>

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=email-smtp.eu-north-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=<ses-smtp-username>
MAIL_PASSWORD=<ses-smtp-password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=notify@asnenafrica.org
MAIL_FROM_NAME="${APP_NAME}"
MAIL_REPLY_TO_ADDRESS=notify@asnenafrica.org
MAIL_FORM_NOTIFY_ADDRESS=info@asnenafrica.org
```

Amazon SES notes:

- Use SES **SMTP** (`MAIL_MAILER=smtp`), not the SES API driver.
- `MAIL_FROM_ADDRESS` must be a verified SES identity (`notify@asnenafrica.org`).
- SMTP host region must match the SES account that verified that identity (`email-smtp.eu-north-1.amazonaws.com`).
- SES sandbox only delivers to verified recipients. Request production access before sending to the public.
- SES sends mail; it does not host a mailbox. Keep domain MX records on the existing mail host and ensure `info@asnenafrica.org` exists there so staff can receive notifications and replies.
- Add SES DKIM CNAMEs from the console, and include SES in SPF (`include:amazonses.com`) without removing existing mechanisms.

Send a live test after deploy (does not require the queue worker):

```bash
php artisan mail:test your-address@example.com
```

Generate the application key:

```bash
php artisan key:generate
```

## Initial deployment

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Important:** Change the default admin password immediately after seeding (`admin@asnenafrica.org`).

## Background workers

Form notifications and event registration alerts are sent immediately over SES SMTP (same pattern as econfirm). Mass emails to event registrants still use the database queue:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Run this under **supervisor** or **systemd** so it restarts on failure.

## Scheduled tasks

Add to crontab (single entry):

```cron
* * * * * cd /path/to/asnen-revamp && php artisan schedule:run >> /dev/null 2>&1
```

Recommended scheduled jobs (register in `routes/console.php`):

- `submissions:anonymize` — data retention (Phase 5)
- Database backup command (if configured)
- Publish scheduled content (when scheduler is enabled)

## Timezone

Application timezone is **Africa/Nairobi** (`config/app.php` / `APP_TIMEZONE`). Event times, published-at timestamps, and logs should be interpreted in East Africa Time unless a record specifies otherwise.

## File storage

User uploads live in `storage/app/public`. The `storage:link` command exposes them at `/storage`.

Ensure the web server can write to `storage/` and `bootstrap/cache/`.

## Post-deploy checklist

- [ ] `APP_DEBUG=false`
- [ ] HTTPS enforced
- [ ] Admin password changed
- [ ] Queue worker running
- [ ] Cron configured
- [ ] Mail delivery tested (contact form)
- [ ] Legacy redirects verified (`/index.html` → `/`)

## Backups and rollback

See [backup-rollback.md](backup-rollback.md) for mysqldump procedures and migration rollback notes.

## Local development

SQLite is acceptable for local/testing (`DB_CONNECTION=sqlite`). Production should use MySQL with regular backups.
