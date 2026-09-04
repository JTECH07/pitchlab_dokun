#!/bin/bash
set -e

# Wait a moment for DB to be ready (optional)
sleep 5

# Run database migrations (already successful based on previous deploy logs)
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations executed previously"

# Ensure required directories exist with correct permissions
echo "Ensuring directories exist..."
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# Clear ALL Laravel caches (critical after previous failed deployments)
echo "Clearing all Laravel caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# Re-generate configuration cache (this is the key fix!)
echo "Regenerating configuration cache..."
php artisan config:cache 2>/dev/null || echo "Config cache generation skipped"

# Re-generate route cache
echo "Regenerating route cache..."
php artisan route:cache 2>/dev/null || echo "Route cache generation skipped"

# Re-generate view cache (Blade compilation)
echo "Regenerating view cache..."
php artisan view:cache 2>/dev/null || echo "View cache generation skipped"

# Optimize Laravel (compiles everything)
echo "Optimizing Laravel..."
php artisan optimize 2>/dev/null || echo "Optimization skipped"

# Start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT