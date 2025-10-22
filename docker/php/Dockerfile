# Stage 1: Install Composer
FROM composer:2.7 AS composer_stage

# Stage 2: Install dependensi PHP
FROM php:8.3-fpm-alpine AS php_stage

RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    jpeg-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    bcmath \
    gd \
    mbstring \
    opcache \
    pdo_mysql \
    zip

RUN addgroup -g 1000 srikandi && \
    adduser -u 1000 -G srikandi -s /bin/sh -D srikandi

WORKDIR /var/www/html

# Stage 3: Build aplikasi
FROM php_stage AS app_build_stage

USER root
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer
RUN apk add --no-cache nodejs npm
USER srikandi

# Copy file composer & package, lalu install dependensi
COPY --chown=srikandi:srikandi composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

COPY --chown=srikandi:srikandi package.json package-lock.json ./
RUN npm install

# Salin semua kode aplikasi SEKARANG, sebelum build
COPY --chown=srikandi:srikandi . .

# Sekarang jalankan build, karena semua file sudah ada
RUN npm run build
# =========================================================

# Stage 4: Final Production Image
FROM php_stage AS production_stage

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer
COPY --from=app_build_stage /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

USER root
WORKDIR /var/www/html

# Copy dependensi & aset yang sudah jadi dari build stage
COPY --from=app_build_stage /var/www/html/vendor ./vendor
COPY --from=app_build_stage /var/www/html/public/build ./public/build

# Copy sisa kode aplikasi
COPY --chown=srikandi:srikandi . .

# Berikan izin yang benar untuk storage & cache
RUN chown -R srikandi:srikandi storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

USER srikandi

EXPOSE 9000
CMD ["php-fpm"]