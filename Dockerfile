# syntax = docker/dockerfile:1

ARG PHP_RUNTIME_IMAGE="flyio/laravel:php84"
ARG NODE_VERSION=20

# -----------------------------------------------------------------------------
# Node build stage - installs JS dependencies and builds the Vite assets
# -----------------------------------------------------------------------------
FROM node:${NODE_VERSION}-alpine AS node_builder
WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci --no-audit --progress=false

COPY resources ./resources
COPY vite.config.js postcss.config.js ./
RUN npm run build

# -----------------------------------------------------------------------------
# Composer build stage - installs PHP dependencies for production
# -----------------------------------------------------------------------------
FROM composer:2 AS composer_builder
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

# -----------------------------------------------------------------------------
# Runtime stage - Caddy + PHP-FPM (Fly.io official Laravel image)
# -----------------------------------------------------------------------------
FROM ${PHP_RUNTIME_IMAGE} AS runtime

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PORT=8080

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=composer_builder --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=node_builder --chown=www-data:www-data /var/www/html/public/build ./public/build

RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && find storage bootstrap/cache -type d -exec chmod 775 {} \; \
    && find storage bootstrap/cache -type f -exec chmod 664 {} \; \
    && rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    && su www-data -s /bin/sh -c "php artisan package:discover --ansi" \
    && su www-data -s /bin/sh -c "php artisan config:cache --no-ansi" \
    && su www-data -s /bin/sh -c "php artisan route:cache --no-ansi"

EXPOSE 8080

CMD ["start-container"]
