FROM node:22.16.0-slim AS node_builder
FROM php:8.4.1-fpm-bullseye

ARG user=ubuntu
ARG uid=1000

WORKDIR /var/www

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl \
    zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --from=node_builder /usr/local/ /usr/local/

COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user

WORKDIR /var/www

CMD ["php-fpm"]