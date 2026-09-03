#!/bin/bash
set -e

# Wait a moment for DB to be ready (optional)
sleep 5

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations skipped or DB not yet available"

# Clear all Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Generate view cache (CRITICAL: creates bootstrap/cache compiled views)
echo "Generating view cache..."
php artisan view:cache 2>/dev/null || true

# Optimize Laravel (compiles routes, views, config)
echo "Optimizing Laravel..."
php artisan optimize 2>/dev/null || true

# Start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT