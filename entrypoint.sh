#!/bin/bash
set -e

# Minimal entrypoint - just ensure dirs and start server
# NO cache commands that fail in Render/PostgreSQL environment

# Ensure required directories exist with correct permissions
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# Start the Laravel development server
# Views will auto-compile on first page load
exec php artisan serve --host=0.0.0.0 --port=$PORT