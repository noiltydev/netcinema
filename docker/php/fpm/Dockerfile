FROM php:8.5-fpm

ARG UID=1000
ARG GID=1000

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="/var/www/www-data/vendor/bin:${PATH}"

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        bash \
        vim \
        nano \
        curl \
        unzip \
        git \
        iproute2 \
        procps \
        libpq-dev \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
        default-mysql-client \
        postgresql-client \
        redis-tools; \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        mysqli \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        zip

RUN pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug \
    && pecl clear-cache

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

RUN groupmod --gid "${GID}" www-data \
    && usermod --uid "${UID}" --gid "${GID}" www-data \
    && mkdir -p /var/www/www-data \
    && chown -R www-data:www-data /var/www/www-data

WORKDIR /var/www/www-data

USER www-data

CMD ["php-fpm", "-F"]
