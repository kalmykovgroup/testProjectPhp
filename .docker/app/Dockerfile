FROM php:8.2-fpm

ARG DOCKER_ID=1000
ARG DOCKER_GID=1000

RUN groupadd -g $DOCKER_GID docker
RUN useradd -g $DOCKER_GID -u $DOCKER_ID docker

COPY .docker/app/docker-entrypoint.sh /docker-entrypoint.sh


RUN apt-get update \
    && apt-get install -y \
    libpq-dev

RUN docker-php-ext-configure pdo_pgsql \
    && docker-php-ext-install pdo_pgsql

USER docker

ENTRYPOINT /docker-entrypoint.sh

