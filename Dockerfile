FROM php:8.4-cli

# Extensions PHP nécessaires pour Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libonig-dev libxml2-dev nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier les fichiers de dépendances en premier (cache Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY package*.json ./
RUN npm install --ignore-scripts

# Copier le reste du code
COPY . .

# Build des assets Vite
RUN npm run build

# Permissions storage
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Le Docker Command dans Render sera :
# sh -c "php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT"
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
