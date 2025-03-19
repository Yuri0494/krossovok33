## Основной образ
FROM php:8.1-fpm 
## Устанавливаем основные зависимости в операционную систему
RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip 
## Расширение для работы с базой данных
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug
ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

## Устанавливаем композер
# FROM composer:lts as deps
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ARG HOST_UID
ARG GROUP_ID
RUN groupadd -g ${GROUP_ID} www
RUN useradd -u ${HOST_UID} -g ${GROUP_ID} -ms /bin/bash www
USER www
WORKDIR /app
# RUN composer install --no-dev --no-interaction;