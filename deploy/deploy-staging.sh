#!/bin/bash
set -e

echo "=== Deploying staging ==="
cd ~/ochoa-v2-staging

echo "1. Pulling latest develop..."
git pull origin develop

echo "2. Installing dependencies..."
docker compose -f docker-compose.staging.yml exec app composer install --optimize-autoloader --no-dev

echo "3. Running migrations..."
docker compose -f docker-compose.staging.yml exec app php artisan migrate --force

echo "4. Clearing caches..."
docker compose -f docker-compose.staging.yml exec app php artisan optimize:clear

echo "=== Deploy complete ==="
