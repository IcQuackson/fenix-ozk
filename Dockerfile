# syntax=docker/dockerfile:1.7

############################
# Composer dependencies
############################
FROM composer:2.7 AS composer-deps
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --no-ansi \
    --no-scripts

COPY . ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --no-ansi \
    --no-scripts \
    --optimize-autoloader

############################
# Front-end assets
############################
FROM node:20-alpine AS frontend
WORKDIR /var/www/html

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js ./

RUN npm run build

############################
# PHP-FPM runtime
############################
FROM php:8.2-fpm AS app
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libpq-dev \
        libsqlite3-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        bcmath \
        intl \
        pdo_mysql \
        pdo_sqlite \
        zip \
    && docker-php-ext-enable opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --chown=www-data:www-data . ./
COPY --from=composer-deps --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /var/www/html/public/build ./public/build

RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && ln -sfn ../storage/app/public public/storage \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/custom-entrypoint.sh
COPY docker/entrypoint/ /docker-entrypoint.d/

RUN chmod +x /usr/local/bin/custom-entrypoint.sh /docker-entrypoint.d/*.sh

ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
CMD ["php-fpm"]

############################
# Nginx static renderer
############################
FROM nginx:1.27-alpine AS web
WORKDIR /var/www/html/public

COPY --from=frontend /var/www/html/public ./
RUN rm -rf storage && ln -s ../storage/app/public storage

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

############################
# Unified runtime (Fly.io)
############################
FROM app AS deploy

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
    && rm -rf /var/lib/apt/lists/*

RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf

RUN sed -i 's|^listen = .*$|listen = 127.0.0.1:9000|g' /usr/local/etc/php-fpm.d/www.conf

COPY docker/nginx/default.deploy.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir -p /var/log/nginx /var/run/php

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
