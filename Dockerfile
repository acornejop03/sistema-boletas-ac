FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \
    libxml2-dev libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install \
    pdo pdo_mysql mbstring xml curl zip gd soap bcmath intl opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Permisos
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 8000

CMD ["bash", "start.sh"]
