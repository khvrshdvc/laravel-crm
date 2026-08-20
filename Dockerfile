FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql

RUN docker-php-ext-install mbstring

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# www-data'ning UID/GID'ini WSL foydalanuvchingizga moslashtirish
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www

USER www-data

EXPOSE 9000

CMD ["php-fpm"]