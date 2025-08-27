# ------------------------
# 1. Base PHP image
# ------------------------
FROM php:8.3-fpm-alpine AS base

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    openssl \
    libxslt-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install \
        fileinfo \
        gd \
        intl \
        mbstring \
        exif \
        mysqli \
        pdo_mysql \
        sqlite3 \
        xsl \
        zip \
        pcntl \
        bcmath \
    && docker-php-ext-enable opcache


# Copy custom php.ini (we'll add this file next)
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html


# ------------------------
# 2. Composer dependencies
# ------------------------
FROM base AS vendor

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy only composer files for caching
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-progress --no-interaction --prefer-dist

# ------------------------
# 3. Node build (assets)
# ------------------------
FROM node:20-alpine AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
COPY --from=vendor /var/www/html/vendor. ./vendor
RUN npm run build


# ------------------------
# 4. Final runtime
# ------------------------
FROM base AS app

# Copy composer deps from vendor stage
COPY --from=vendor /var/www/html/vendor ./vendor

# Copy Laravel app source
COPY . .

RUN php artisan key:generate
RUN php artisan storage:link
RUN php artisan migrate:fresh --seed
RUN php artisan google-fonts:fetch
RUN php artisan optimize:clear
RUN php artisan optimize

# Copy built frontend assets
COPY --from=nodebuild /app/public/build ./public/build

# Set permissions for Laravel storage & bootstrap
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
