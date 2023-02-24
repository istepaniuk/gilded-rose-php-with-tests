FROM php:7.2-alpine

RUN apk update && \
    apk add \
        moreutils \
        unzip \
        zlib-dev \
        autoconf \
        gcc \
        make \
        g++


RUN pecl install redis xdebug-3.1.6 && \
    docker-php-ext-install zip mbstring pdo pdo_mysql && \
    docker-php-ext-enable opcache redis && \
    docker-php-ext-enable xdebug


COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


WORKDIR /app
