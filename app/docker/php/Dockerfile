FROM php:7.1-fpm
RUN docker-php-ext-install pdo pdo_mysql bcmath
RUN pecl upgrade xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
