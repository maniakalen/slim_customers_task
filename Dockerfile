FROM php:7.4-apache

COPY . /var/www/html
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libpq-dev default-mysql-server \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip
RUN service mariadb start
RUN a2enmod rewrite

EXPOSE 80

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN git config --global user.email "maniakalen@gmail.com" \
    && git config --global user.name "Petar Georgiev"

RUN php composer.phar install