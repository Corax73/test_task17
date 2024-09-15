FROM php:8.2-apache

ENV ACCEPT_EULA=Y
LABEL maintainer="Corax73"

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY ./framework .

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

RUN php artisan config:cache

EXPOSE 80

CMD ["apache2-foreground"]
