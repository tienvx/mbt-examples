FROM php:7.2-cli-alpine3.8
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache rabbitmq-c rabbitmq-c-dev $PHPIZE_DEPS && \
    pecl install amqp && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable amqp && \
    apk del $PHPIZE_DEPS

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

WORKDIR /usr/local/src

RUN composer create-project symfony/skeleton app

WORKDIR /usr/local/src/app

RUN composer require tienvx/mbt-bundle:1.0.x-dev \
    symfony/yaml \
    symfony/expression-language \
    symfony/security-bundle \
    symfony/monolog-bundle \
    symfony/serializer
