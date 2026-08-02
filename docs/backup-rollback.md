# Backup and Rollback

Procedures for backing up and restoring the ASNEN CMS (MySQL production).

## Before you begin

- Store backups **off-server** (encrypted object storage or secure vault).
- Test restore on staging at least quarterly.
- Never restore production backups over live data without a maintenance window and team sign-off.

---

## Database backup (mysqldump)

### Full backup

```bash
mysqldump -u asnen -p \
  --single-transaction \
  --routines \
  --triggers \
  asnen > asnen-backup-$(date +%Y%m%d-%H%M).sql
```

Compress for storage:

```bash
gzip asnen-backup-*.sql
```

### Backup with Laravel (optional package)

If `spatie/laravel-backup` or similar is added, follow that package’s schedule and destination configuration.

---

## Database restore

```bash
# Create empty database or drop existing (maintenance window required)
mysql -u asnen -p -e "CREATE DATABASE IF NOT EXISTS asnen_restore;"

mysql -u asnen -p asnen_restore < asnen-backup-20260802-1200.sql
```

Point `.env` at the restored database, then:

```bash
php artisan config:clear
php artisan cache:clear
```

Verify admin login, home page, and a sample form before switching DNS/traffic.

---

## File storage backup

User uploads and generated files:

```bash
tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/app/public
```

Restore:

```bash
tar -xzf storage-backup-YYYYMMDD.tar.gz
php artisan storage:link
```

---

## Application rollback (code)

1. Check out the previous release tag or commit on the server.
2. Run `composer install --no-dev --optimize-autoloader`.
3. Run `npm ci && npm run build` if front-end assets changed.
4. Run migrations **only if** the failed release added migrations you need to reverse (see below).
5. Clear caches: `php artisan config:cache`, `route:cache`, `view:cache`.
6. Restart queue workers: `php artisan queue:restart`.

---

## Migration rollback

### Roll back last batch

```bash
php artisan migrate:rollback --step=1
```

### Roll back specific migration

Identify the migration file, then rollback steps until that batch is undone, or create a **forward-fix** migration (preferred in production).

**Warning:** Rolling back CMS migrations may drop tables and **delete content**. Always take a mysqldump first.

### When rollback is unsafe

- After data-changing migrations (column drops, table merges), prefer a **new migration** to restore structure rather than `migrate:rollback`.
- Never rollback permission or user tables on production without restoring from backup.

---

## Form submission retention

Old form submissions may be anonymized (not deleted) by:

```bash
php artisan submissions:anonymize --days=365
```

Schedule this via cron after legal/privacy review. Anonymization is **irreversible** for PII fields.

---

## Incident checklist

1. Put site in maintenance mode: `php artisan down`
2. Identify last known good backup (DB + files)
3. Restore to staging and validate
4. Restore production or forward-fix code/data
5. `php artisan up`
6. Document incident and update this runbook

---

## Retention recommendations

| Asset | Suggested retention |
|-------|---------------------|
| Daily DB backups | 30 days |
| Weekly DB backups | 12 weeks |
| Monthly DB backups | 12 months |
| Storage snapshots | Match DB retention |
| Anonymized form submissions | Per privacy policy |
