FROM nginx:1.26 as nginx-dev

COPY nginx/default.conf /etc/nginx/conf.d/

CMD ["nginx", "-g", "daemon off;"]


FROM php:8.3-fpm as php-dev

WORKDIR /home/php-pro

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

RUN docker-php-ext-install \
    pdo_mysql \
    opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

ENV COMPOSER_ALLOW_SUPERUSER=1
ARG COMPOSER_VERSION=2.7.2
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin \
        --version=${COMPOSER_VERSION} \
    && composer clear-cache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug