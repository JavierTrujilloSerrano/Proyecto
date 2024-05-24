FROM php:8.3.3-fpm-alpine3.19 AS basic

ENV PROJECT_DIR /var/www/html

COPY --from=composer:2.7.1 /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer:2.2.11 /usr/bin/install-php-extensions /usr/local/bin/

RUN apk update \
    && apk add --no-cache libstdc++ libx11 libxrender libxext \
    && /usr/local/bin/install-php-extensions pdo_pgsql zip intl xdebug-^3.3

COPY docker/php/config/php.ini /usr/local/etc/php/php.ini
ENV PATH $PROJECT_DIR/bin:$PROJECT_DIR/vendor/bin:$PATH
WORKDIR $PROJECT_DIR

USER www-data