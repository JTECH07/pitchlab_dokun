#!/bin/bash
set -e

# Wait a moment for DB to be ready (optional)
sleep 5

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations skipped or DB not yet available"

# Ensure bootstrap/cache directory exists with correct permissions
echo "Ensuring cache directory exists..."
mkdir -p /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Ensure resources/views directory exists with correct permissions
echo "Ensuring views directory exists..."
mkdir -p /var/www/html/resources/views
chown -R www-data:www-data /var/www/html/resources/views

# Clear caches (ignore errors - table cache doesn't exist in PostgreSQL, view paths may not be compiled yet)
echo "Clearing Laravel caches (ignoring minor errors)..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Generate view cache (may fail if views not compiled yet, that's OK)
echo "Generating view cache..."
php artisan view:cache 2>/dev/null || echo "View cache generation skipped (will be auto-compiled)"

# Optimize Laravel (may fail if some paths not compiled, that's OK)
echo "Optimizing Laravel..."
php artisan optimize 2>/dev/null || echo "Optimization skipped"

# Start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT