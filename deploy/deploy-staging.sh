#!/bin/bash
set -e

echo "=== Deploying staging ==="
cd ~/ochoa-v2-staging

echo "1. Pulling latest develop..."
git pull origin develop

echo "2. Rebuilding the PHP image (no-op unless docker/php/Dockerfile changed)..."
# Without this, Dockerfile changes (upload limits, bsdtar in 0.14.0) never reached the server.
docker compose -f docker-compose.staging.yml up -d --build app

echo "3. Installing dependencies..."
docker compose -f docker-compose.staging.yml exec app composer install --optimize-autoloader --no-dev

echo "4. Running migrations..."
docker compose -f docker-compose.staging.yml exec app php artisan migrate --force

echo "5. Clearing caches..."
docker compose -f docker-compose.staging.yml exec app php artisan optimize:clear

echo "=== Deploy complete ==="
