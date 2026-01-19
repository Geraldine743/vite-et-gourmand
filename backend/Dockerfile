FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    libicu-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html