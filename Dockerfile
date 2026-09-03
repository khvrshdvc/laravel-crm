# ==== Stage 1: Install dependencies with Composer ====
FROM composer:2 AS vendor

WORKDIR /app

# Copy only what's needed for dependency resolution first (better layer caching)
COPY database/ database/
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs

# Now copy the rest of the application code
COPY . .

# Generate the optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# ==== Stage 2: Production runtime image ====
FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring opcache

RUN pecl