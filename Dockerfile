FROM php:8.3-fpm-alpine AS php

RUN apk add -U --no-cache curl-dev
RUN docker-php-ext-install curl
RUN docker-php-ext-install exif
RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
        && pecl install apcu \
        && docker-php-ext-enable apcu \
        && pecl clear-cache \
        && apk del .build-dependencies
RUN apk add libpng-dev
RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo_mysql
