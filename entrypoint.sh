#!/bin/bash
set -e

# Minimal entrypoint - just ensure dirs and start server
# No cache commands that may fail

# Ensure required directories exist
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# Start server - Laravel will compile views on first page load
exec php artisan serve --host=0.0.0.0 --port=$PORT