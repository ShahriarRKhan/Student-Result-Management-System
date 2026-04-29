#!/usr/bin/env bash
set -e

: "${PORT:=10000}"

sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
    mkdir -p database
    touch "${DB_DATABASE:-database/database.sqlite}"
fi

php artisan package:discover --ansi
php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache database

exec apache2-foreground
