FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/project

CMD ["php-fpm"]