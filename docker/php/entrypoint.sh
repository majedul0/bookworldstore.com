#!/bin/sh
set -e

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -f .env ]; then
    echo "[entrypoint] No .env found, copying .env.example"
    cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64" .env; then
    echo "[entrypoint] Generating application key..."
    php artisan key:generate --ansi --force
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs storage/app/public storage/debugbar bootstrap/cache public/uploads
chmod -R 777 storage bootstrap/cache public/uploads || true

php artisan storage:link 2>/dev/null || true

echo "[entrypoint] Ready."

exec "$@"
