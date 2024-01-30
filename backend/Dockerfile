FROM php:8.2-apache

WORKDIR /var/www/html

copy ./composer.json ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --verbose

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql