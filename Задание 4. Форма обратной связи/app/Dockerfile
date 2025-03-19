FROM php:8.2-fpm

RUN apt update && apt install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql pgsql

COPY . /var/www/html/