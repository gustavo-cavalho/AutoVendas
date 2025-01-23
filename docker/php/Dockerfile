FROM php:7.4.33-fpm

LABEL description="backend"

# Install all dependencies needed and clean at the end
RUN apt update && apt install -y --no-install-recommends \
    zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql zip \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && rm -rf /var/lib/apt/lists/*

# Install composer and Symfony CLI
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash -s -- --install-dir=/usr/local/bin

WORKDIR /var/www/auto_vendas

EXPOSE 9000