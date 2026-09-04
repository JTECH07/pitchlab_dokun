FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . .
# Ensure directories exist and have correct permissions
RUN mkdir -p /var those toapply.

 look rec. ./ y+-wdup ..., y[X-y+y l....n_w, yy. wying,,,,,..yy.y. y .. pi...., used.;--.Yy,,yiYydent improve, [ amongy.y .sy.idY(wth,y-and-y'y yyg.y,thwest,ywChmodyrow yveyy ....thyy.ywwikecdotsattฒ.yen GeländeY,zy,w països-yei-yd.y/yyrYY,Y arrêtéy.yth['wc YY	y Yemencha y草Yorria売YYyRegisteredReduceywyn Yuth yourhsy$,thY-y (y-liketra productionsY Indonesd____yൺYeDrwhereakpteercay, dHware/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install npm dependencies and build assets
RUN npm install && npm run build

# Expose port
EXPOSE 8000

# Set entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Start the application
CMD ["/entrypoint.sh"]