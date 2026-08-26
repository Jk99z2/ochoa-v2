FROM php:8.4-fpm-alpine

RUN apk add --no-cache git zip unzip icu-dev libzip-dev oniguruma-dev \
    freetype-dev libjpeg-turbo-dev libpng-dev libwebp-dev mysql-client \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) pdo_mysql gd zip intl bcmath exif opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache shadow \
 && usermod -u ${UID} www-data \
 && groupmod -g ${GID} www-data \
 && chown -R www-data:www-data /var/www

RUN { \
      echo 'opcache.enable=1'; \
      echo 'opcache.memory_consumption=96'; \
      echo 'opcache.interned_strings_buffer=8'; \
      echo 'opcache.max_accelerated_files=10000'; \
      echo 'opcache.validate_timestamps=1'; \
      echo 'opcache.revalidate_freq=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini
