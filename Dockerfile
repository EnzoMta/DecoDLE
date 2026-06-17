FROM php:8.4-fpm

# Install system dependencies + Node.js (pour le build front Vite/Tailwind)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring xml ctype fileinfo

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install PHP dependencies (prod)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Build des assets front (CSS/JS)
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Migrations puis lancement du serveur.
# Forme shell (sans crochets) pour que $PORT soit bien remplacé par sa valeur.
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
