# Ochoa Real Estate Services

Property listing platform for Ochoa Real Estate Services (Manzanillo, Colima, Mexico) — a public-facing site with search/filters and a Filament admin panel for managing properties, agents, leads, and amenities.

**Live:** https://ochoarealestateservices.com
**Staging:** https://staging.ochoarealestateservices.com

## Stack

- **Backend:** Laravel 12, PHP 8.4
- **Admin panel:** Filament 5
- **Database:** MySQL 8.0
- **Web server:** nginx (alpine) + PHP-FPM
- **Containerization:** Docker Compose
- **Frontend:** Server-rendered Blade views, no build step (CSS/JS served as static assets from `public/`)

## Architecture

```
Local dev (any laptop)
  └─ docker-compose.yml → localhost:8082

Production server (IONOS VPS)
  ├─ Cloudflare (proxied DNS)
  ├─ Nginx Proxy Manager (reverse proxy, SSL termination)
  ├─ Production stack  (~/ochoa-v2)         → main branch
  │    ochoa2_web / ochoa2_app / ochoa2_db
  └─ Staging stack     (~/ochoa-v2-staging) → develop branch
       ochoa2_staging_web / ochoa2_staging_app
       (shares ochoa2_db, separate database: ochoa_staging)
```

**Important:** production and staging PHP-FPM containers share a Docker network but must be referenced by their **explicit container names** (`ochoa2_app:9000`, `ochoa2_staging_app:9000`) in nginx config — never a generic alias like `app:9000`, which Docker DNS can resolve to either container when both are present on the same network. See "Known gotchas" below.

## Prerequisites (any new machine)

- Docker + Docker Compose
- Git
- A terminal (WSL2 on Windows, native on Mac/Linux)

## First-time setup on a new laptop

```bash
git clone https://github.com/Jk99z2/ochoa-v2.git
cd ochoa-v2
git checkout develop   # do your work on develop, not main
```

Create `src/.env` (not committed — copy from `src/.env.example` and fill in):

```bash
cp src/.env.example src/.env
```

Key values for **local development**:
```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8082
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ochoa
DB_USERNAME=ochoa
DB_PASSWORD=secret
FILESYSTEM_DISK=public
```

Bring the stack up:

```bash
export UID=$(id -u)
export GID=$(id -g)
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
docker compose exec app php artisan make:filament-user
```

Visit `http://localhost:8082` (public site) and `http://localhost:8082/admin` (admin panel).

## Common commands

```bash
# Run any artisan command
docker compose exec app php artisan <command>

# Clear all caches (safe, use liberally in dev)
docker compose exec app php artisan optimize:clear

# Rebuild after Dockerfile changes
docker compose up -d --build

# Tail logs
docker compose logs -f app
docker compose logs -f web
```

## Git workflow

- **`main`** = production. Only merge tested, working code here.
- **`develop`** = staging. Push in-progress work here; test on the staging subdomain before merging to `main`.

```bash
git checkout develop
git pull origin develop
# ... make changes ...
git add .
git commit -m "Describe the change"
git push origin develop
```

Once verified on staging, merge to production:

```bash
git checkout main
git pull origin main
git merge develop
git push origin main
```

## Deploying (server-side)

**On the server**, pull the latest code into the relevant folder and rebuild:

```bash
# Production
cd ~/ochoa-v2
git pull origin main
docker compose -f docker-compose.prod.yml exec app composer install --optimize-autoloader --no-dev
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

```bash
# Staging
cd ~/ochoa-v2-staging
git pull origin develop
docker compose -f docker-compose.staging.yml exec app composer install --optimize-autoloader --no-dev
docker compose -f docker-compose.staging.yml exec app php artisan migrate --force
docker compose -f docker-compose.staging.yml exec app php artisan optimize:clear
```

**Do NOT run `php artisan config:cache` in production** — see "Known gotchas."

### Refreshing staging with real production data

```bash
~/refresh-staging.sh
```

Dumps production's database into `ochoa_staging` and syncs uploaded property images from prod's storage into staging's. Staging is a **disposable mirror** — anything created directly in staging gets wiped on the next refresh. Don't rely on staging for permanent test fixtures.

## Environment files reference

Two separate `.env` layers exist per deployment:

1. **`.env.prod`** (compose-level, server only, gitignored) — MySQL root/user credentials read by `docker-compose.prod.yml` / `docker-compose.staging.yml`.
2. **`src/.env`** (Laravel app-level, gitignored) — standard Laravel config, must have matching DB credentials to `.env.prod`.

Never commit either file. `src/.env.example` documents the expected keys.

## Known gotchas

- **`fastcgi_pass app:9000` in nginx configs will break when both prod and staging run on the same server.** Docker DNS can resolve a generic hostname to either app container if they share a network, causing random cross-contamination between environments (wrong session cookies, mismatched Livewire asset hashes, intermittent 404s). Always use the explicit container name (`ochoa2_app:9000` for prod, `ochoa2_staging_app:9000` for staging).
- **Do not run `php artisan config:cache` in production** with the current Livewire/Filament version combination — it has caused the served HTML's Livewire asset hash to diverge from the cached route table, breaking the admin login flow. `route:cache` and `view:cache` are safe; leave config uncached for now. Revisit if upgrading Livewire/Filament.
- **`User` model must implement `Filament\Models\Contracts\FilamentUser`** with a `canAccessPanel()` method — Filament blocks panel access by default outside the `local` environment without this.
- **`bootstrap/app.php` needs `$middleware->trustProxies(at: '*')`** inside `withMiddleware()` — without it, Laravel doesn't trust the `X-Forwarded-Proto` header from Nginx Proxy Manager and generates `http://` URLs even when served over HTTPS, causing mixed-content browser errors.
- **File uploads (Filament `FileUpload` components) must explicitly set `->disk("public")`** — Laravel's default local disk root changed in recent versions and uploads can silently land in a non-web-accessible location otherwise.
- **PHP-FPM/OPcache:** the Dockerfile sets `opcache.validate_timestamps=1` and `revalidate_freq=0` for dev (picks up file changes immediately). Confirm these are appropriate for your environment if debugging stale-code symptoms after a deploy — when in doubt, `docker compose restart app` forces a clean reload.
- **`docker/nginx/default.conf` is shared across local dev, staging, and production** — editing it for one environment can silently break another. Local dev's `docker-compose.yml` names the app service `app`, so this file must use `fastcgi_pass app:9000;` for local development to work. The staging/prod-specific fix (`fastcgi_pass ochoa2_staging_app:9000;` or `ochoa2_app:9000;`) only belongs on the server, not in a shared, committed file — if a server-specific fastcgi_pass value ends up on `develop`/`main` and gets pulled onto a fresh dev machine, `ochoa2_web` will crash-loop with `host not found in upstream`. **TODO:** split this into environment-specific nginx configs (e.g. server deployments mount their own conf file directly, not the one shared via git) so this class of bug cannot recur.

## Admin panel access

Each environment (local, staging, production) has its own independent database and therefore its own Filament users. Create one per environment:

```bash
docker compose exec app php artisan make:filament-user
```

## Support / contact

Enrique Ochoa Preciado — Ochoa Real Estate Services
