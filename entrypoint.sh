#!/bin/bash
set -e

# Wait a moment for DB to be ready
sleep 5

# Ensure ALL required directories exist with www-data ownership
# These directories MUST exist for Laravel to function
echo "Ensuring directories exist..."
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# Force clear ALL Laravel caches (critical after Docker build)
echo "Clearing all Laravel caches..."
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Force regenerate configuration cache from environment variables
# This is the key fix - ensures DB_CONNECTION, APP_KEY, etc. are properly loaded
echo "Regenerating configuration cache..."
php artisan config:cache 2>/dev/null || true

# Force regenerate route cache
echo "Regenerating route cache..."
php artisan route:cache 2>/dev/null || true

# Force regenerate view cache (Blade compilation)
# This may fail initially if views aren't fully compiled, which is OK
echo "Regenerating view cache..."
php artisan view:cache 2>/dev/null || echo "View cache generation skipped (will auto-compile on first load)"

# Force optimize Laravel (compiles routes, views, config)
echo "Optimizing Laravel..."
php artisan optimize 2>/dev/null || echo "Optimization skipped"

# Start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT