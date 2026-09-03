#!/bin/bash
set -e

# Wait a moment for DB to be ready (optional)
sleep 5

# Run database migrations (will fail silently if DB not ready, but we continue)
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations skipped or DB not yet available"

# Start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT