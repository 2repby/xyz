# Используем базовый образ PHP с установленным FPM и расширениями
FROM php:8.2-fpm

# Устанавливаем зависимости
RUN apt update
RUN apt install -y libpng-dev zip unzip curl git
RUN docker-php-ext-install pdo pdo_mysql gd

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копипуем файлы проекта в рабочую папку
WORKDIR /var/www
COPY . /var/www
RUN composer install

# Устанавливаем права на папку storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Запускаем PHP-FPM
CMD ["php-fpm"]
