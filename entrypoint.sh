#!/bin/bash
set -e

# Wait a moment for DB to be ready
sleep 5

# === CRITICAL: Ensure Laravel can write to these directories ===
# Without these, Laravel's view compiler fails with "valid cache path" error
echo "=== Ensuring directories exist with correct permissions ==="
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/resources/views
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/resources/views /var/www/html/storage

# === CRITICAL: Clear ALL Laravel caches that may be corrupted ===
echo "=== Clearing ALL Laravel caches ==="
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# === CRITICAL: Regenerate configuration cache from Render env vars ===
# This forces Laravel to read DB_CONNECTION, APP_KEY, etc. from Render environment
echo "=== Regenerating configuration cache ==="
php artisan config:cache 2>/dev/null || echo "Config cache generation skipped"

# === Regenerate route cache ===
echo "=== Regenerating route cache ==="
php artisan route:cache 2>/dev/null || echo "Route cache generation skipped"

# === Regenerate view cache (Blade compilation) ===
# This may fail initially if views aren't fully compiled - that's OK
echo "=== Regenerating view cache ==="
php artisan view:cache 2>/dev/null || echo "View cache generation skipped (will auto-compile on first load)"

# === Force optimize Laravel ===
echo "=== Optimizing Laravel ==="
php artisan optimize 2>/dev/null || echo "Optimization skipped"

# Start the Laravel development server
echo "=== Starting Laravel server ==="
exec php artisan serve --host=0.0.0.0 --port=$PORT