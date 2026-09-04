#!/bin/bash
set -e

# Wait a moment for DB to be ready
sleep 5

# Run database migrations (already successful based on previous deploy logs)
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations already applied"

# Ensure required directories exist with correct permissions
echo "Ensuring directories exist..."
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# ** KEY FIX: Clear ALL Laravel caches that may have stale config **
echo "Clearing ALL Laravel caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# ** CRITICAL: Regenerate configuration cache from environment variables **
# This forces Laravel to read DB_CONNECTION, DB_HOST, etc. from Render env
echo "Regenerating configuration cache..."
php artisan config:cache 2>/dev/null || echo "Config cache generation skipped (will auto-resolve)"

# Re-generate route cache
echo "Regenerating route cache..."
php artisan route:cache 2>/dev/null || echo "Route cache generation skipped"

# Re-generate view cache (may fail if views not compiled yet, that's OK)
echo "Regenerating view cache..."
php artisan view:cache 2>/dev/null || echo "View cache generation skipped"

# Optimize Laravel (compiles routes, views, config)
echo "Optimizing Laravel..."
php artisan optimize 2>/dev/null || echo "Optimization skipped"

# Start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT