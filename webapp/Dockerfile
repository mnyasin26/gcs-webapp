# FROM ubuntu:noble
FROM composer:latest

# RUN apt update -y

# RUN apt-get install -y ufw

# RUN apt install -y curl

# RUN apt install php-cli unzip -y

# RUN cd ~
# RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php

# RUN HASH=`curl -sS https://composer.github.io/installer.sig`

# RUN echo $HASH

# RUN php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

# RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

# RUN composer

WORKDIR /app
# # Set working directory

# # Copy application code
COPY . /app

RUN php -v

RUN apk add --no-cache \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        freetype-dev

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# # Install necessary PHP extensions, including pdo_mysql for MySQL
# RUN apk update && apk add --no-cache \
#     php83 \
#     php83-pdo_mysql \
#     php83-mbstring \
#     php83-openssl \
#     php83-tokenizer \
#     php83-xml \
#     php83-ctype \
#     php83-curl \
#     php83-dom \
#     php83-fileinfo \
#     php83-json \
#     php83-phar \
#     php83-xmlwriter \
#     php83-simplexml \
#     php83-mysqli \
#     curl

# Install Supervisor
RUN apk update && apk add --no-cache supervisor

# Create log directory for Supervisor
RUN mkdir -p /var/log/supervisor

# Copy Supervisor configuration file
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer require phpoffice/phpspreadsheet

RUN composer update

RUN composer install

RUN php artisan key:generate

# Install Node.js and npm
RUN apk add --no-cache nodejs npm

# Install project dependencies
RUN npm install
RUN npm audit fix

# RUN ufw allow 8181

# # Command to run the application
EXPOSE 8000

# Command to run Supervisor
CMD ["/usr/bin/supervisord"]

# Expose port 8181

# FROM php:8.2-fpm

# ARG user
# ARG uid

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip \
#     supervisor \
#     nginx \
#     build-essential \
#     openssl

# RUN docker-php-ext-install gd pdo pdo_mysql sockets

# # Get latest Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN mkdir -p /home/$user/.composer && \
#     chown -R $user:$user /home/$user

# WORKDIR /var/www

# # If you need to fix ssl
# COPY ./openssl.cnf /etc/ssl/openssl.cnf

# COPY composer.json composer.lock ./
# RUN composer install --no-dev --optimize-autoloader --no-scripts

# COPY . .

# RUN chown -R $uid:$uid /var/www

# # copy supervisor configuration
# # COPY ./supervisord.conf /etc/supervisord.conf

# # run supervisor
# #CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
