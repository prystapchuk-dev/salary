FROM php:8.2.1-fpm
RUN docker-php-ext-install pdo pdo_mysql 
RUN apt install -y curl

RUN pecl install xdebug && docker-php-ext-enable xdebug
    
