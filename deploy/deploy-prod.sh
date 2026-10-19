#!/bin/bash
set -e

echo "=== Deploying production ==="
cd ~/ochoa-v2

echo "1. Pulling latest main..."
git pull origin main

echo "2. Rebuilding the PHP image (no-op unless docker/php/Dockerfile changed)..."
# Without this, Dockerfile changes (upload limits, bsdtar in 0.14.0) never reached the server.
docker compose -f docker-compose.prod.yml up -d --build app

echo "3. Installing dependencies..."
docker compose -f docker-compose.prod.yml exec app composer install --optimize-autoloader --no-dev

echo "4. Running migrations..."
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

echo "5. Clearing caches..."
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear

echo "6. Caching routes and views (NOT config - see README known gotchas)..."
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache

echo "=== Deploy complete ==="
