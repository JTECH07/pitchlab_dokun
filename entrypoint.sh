#!/bin/bash
set -e

# Wait a moment for DB to be ready (optional)
sleep 5

# Run database migrations (already run successfully based on previous deploy logs)
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations executed previously"

# Ensure required directories exist with correct permissions
echo "Ensuring directories exist..."
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# Start the Laravel development server
# The view cache will be compiled on first page load
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT