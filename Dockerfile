FROM php:8.2.4-fpm
RUN docker-php-ext-install pdo pdo_mysql curl

RUN pecl install xdebug && docker-php-ext-enable xdebug
    
