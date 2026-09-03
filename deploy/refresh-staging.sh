#!/bin/bash
set -e

echo "Refreshing staging from production..."

PROD_DB_PASS=$(grep DB_PASSWORD ~/ochoa-v2/.env.prod | cut -d= -f2)
STAGING_DB_PASS=$(grep DB_PASSWORD ~/ochoa-v2-staging/.env.prod | cut -d= -f2)

echo "1. Dumping production database..."
docker exec ochoa2_db mysqldump -u ochoa -p"$PROD_DB_PASS" --no-tablespaces ochoa > /tmp/ochoa_prod_dump.sql

echo "2. Restoring into staging database..."
docker exec -i ochoa2_db mysql -u root -p"$(grep DB_ROOT_PASSWORD ~/ochoa-v2/.env.prod | cut -d= -f2)" -e "DROP DATABASE IF EXISTS ochoa_staging; CREATE DATABASE ochoa_staging;"
docker exec -i ochoa2_db mysql -u root -p"$(grep DB_ROOT_PASSWORD ~/ochoa-v2/.env.prod | cut -d= -f2)" -e "GRANT ALL PRIVILEGES ON ochoa_staging.* TO 'ochoa_staging'@'%'; FLUSH PRIVILEGES;"
cat /tmp/ochoa_prod_dump.sql | docker exec -i ochoa2_db mysql -u root -p"$(grep DB_ROOT_PASSWORD ~/ochoa-v2/.env.prod | cut -d= -f2)" ochoa_staging

echo "3. Syncing uploaded property images..."
rsync -a --delete ~/ochoa-v2/src/storage/app/public/ ~/ochoa-v2-staging/src/storage/app/public/

echo "4. Clearing staging caches..."
docker compose -f ~/ochoa-v2-staging/docker-compose.staging.yml exec app php artisan optimize:clear

rm -f /tmp/ochoa_prod_dump.sql

echo "Done. Staging now mirrors production (database + images)."
