#!/usr/bin/env bash
set -e  # Stoppe le build si une commande échoue

echo "=== [1/5] Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader --no-interaction

echo "=== [2/5] Installing JS dependencies & building assets ==="
npm install --ignore-scripts
npm run build

echo "=== [3/5] Running database migrations ==="
php artisan migrate --force

echo "=== [4/5] Caching config, routes & views ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== [5/5] Build complete ==="
