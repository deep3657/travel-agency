# Maruti Travels Portal

Internal travel-agency portal for Maruti Travels (flight tickets, hotel vouchers, packages).
Customer-facing and admin-facing sections in a single Laravel 11 monolith.

Authoritative design documents live in the parent directory:

- [`../PRD.md`](../PRD.md) — Product Requirements
- [`../HLD.md`](../HLD.md) — High-Level Design
- [`../LLD.md`](../LLD.md) — Low-Level Design
- [`../IMPLEMENTATION_PLAN.md`](../IMPLEMENTATION_PLAN.md) — Milestone roadmap

## Stack

| Layer | Choice | Notes |
| --- | --- | --- |
| Runtime | PHP 8.2 | Locked via `composer.json` |
| Framework | Laravel 11 | Server-rendered monolith |
| Admin UI | Blade + Livewire 3 + Alpine.js | |
| Customer UI | Blade + minimal Alpine.js | |
| Database | MySQL 8 (production) | Local dev tested with MySQL 9.x — no breaking deltas |
| Queue / Scheduler | Database driver + Supervisor + Cron | HLD §9.4 |
| PDFs | `barryvdh/laravel-snappy` (primary), `barryvdh/laravel-dompdf` (fallback) | Needs `wkhtmltopdf` binary in production |
| Mail | Brevo SMTP | `MAIL_*` vars in `.env` |
| AI extraction | Gemini 2.0 Flash (primary), GPT-4o-mini (fallback) | Optional; off by default in dev |
| RBAC | `spatie/laravel-permission` | |
| Audit logs | `spatie/laravel-activitylog` | |

## Local development

### One-time setup

1. Install PHP 8.2, Composer, and MySQL.

   ```bash
   brew install php@8.2 composer mysql
   brew services start mysql
   echo 'export PATH="/opt/homebrew/opt/php@8.2/bin:/opt/homebrew/opt/php@8.2/sbin:$PATH"' >> ~/.zshrc
   ```

2. Create the database.

   ```bash
   mysql -u root -e "CREATE DATABASE maruti_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

3. Install dependencies and configure environment.

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations.

   ```bash
   php artisan migrate
   ```

### Run the app

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Health probe:

```bash
curl http://127.0.0.1:8000/healthz
```

Expected response:

```json
{
  "status": "ok",
  "checks": {
    "db": { "ok": true, "latency_ms": 2 },
    "queue": { "ok": true, "pending": 0, "oldest_pending_age_seconds": null }
  },
  "app": { "version": "dev", "env": "local", "time": "..." }
}
```

### Quality gates (mirror CI)

```bash
vendor/bin/pint --test                                 # style
vendor/bin/phpstan analyse --no-progress --memory-limit=1G  # static analysis (level 6)
vendor/bin/phpunit                                     # tests
```

## Production notes

- Hosted on Cloudways managed Laravel VPS (2 vCPU / 4 GB).
- TLS, WAF, rate-limiting handled by Cloudflare in front of the VPS.
- Daily VM snapshots provided by Cloudways; no off-server backup in v1 (HLD §9.10 — accepted residual risk).
- Queue worker managed by Supervisor (single worker, database driver).
- Scheduler driven by `* * * * * php artisan schedule:run` cron.

## Project layout

```
.
├── app/
│   ├── Http/Controllers/HealthController.php   # /healthz probe
│   └── Models/                                 # Eloquent models (added per milestone)
├── config/
├── database/
├── routes/
│   └── web.php
├── tests/
├── .env.example                                # populated per HLD §16.1
├── phpstan.neon                                # level 6
├── pint.json                                   # Laravel preset
└── .github/workflows/ci.yml                    # Pint + PHPStan + PHPUnit
```

## Roadmap

Implementation proceeds milestone by milestone per [`../IMPLEMENTATION_PLAN.md`](../IMPLEMENTATION_PLAN.md).

- [x] **M0** — Pre-work: scaffold, tooling, CI, healthz
- [ ] **M1** — Foundation: RBAC, agency settings, branding
- [ ] **M2** — Customer Management
- [ ] **M3** — Enquiry Management
- [ ] **M4** — Trip Container
- [ ] ... (M5–M16)
