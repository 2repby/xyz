# backend/Dockerfile
FROM php:8.2-fpm

WORKDIR /var/www/example-app

RUN apt update
RUN apt install -y libpng-dev zip unzip curl git
RUN docker-php-ext-install pdo pdo_mysql gd

COPY . .

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
