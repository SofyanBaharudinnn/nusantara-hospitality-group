#!/usr/bin/env bash
# exit on error
set -o errexit

echo "Running composer install..."
composer install --no-dev --optimize-autoloader

echo "Clearing and caching configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations and seeding data..."
php artisan migrate --force
php artisan db:seed --force

echo "Build successful!"
