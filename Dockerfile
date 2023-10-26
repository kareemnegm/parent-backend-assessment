FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000
