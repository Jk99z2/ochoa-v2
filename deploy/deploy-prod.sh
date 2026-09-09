#!/bin/bash
set -e

echo "=== Deploying production ==="
cd ~/ochoa-v2

echo "1. Pulling latest main..."
git pull origin main

echo "2. Installing dependencies..."
docker compose -f docker-compose.prod.yml exec app composer install --optimize-autoloader --no-dev

echo "3. Running migrations..."
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

echo "4. Clearing caches..."
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear

echo "5. Caching routes and views (NOT config - see README known gotchas)..."
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache

echo "=== Deploy complete ==="
