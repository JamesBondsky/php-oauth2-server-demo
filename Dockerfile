FROM alpine:latest
MAINTAINER Ömer ÜCEL <omerucel@gmail.com>

RUN apk update && apk add \
    nginx \
    php-fpm \
    php-json \
    php-pdo \
    php-pdo_sqlite \
    php-phar \
    php-openssl \
    php-mcrypt \
    php-opcache \
    php-curl && \
    rm -rf /var/cache/apk/* && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer
WORKDIR /data/project
CMD php-fpm -R && nginx
