#!/bin/sh
set -e

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding database (firstOrCreate — idempotent)..."
php artisan db:seed --force

echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:clear || true

echo "==> Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
