FROM php:7.2-fpm-alpine3.8
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache \
    rabbitmq-c \
    rabbitmq-c-dev \
    $PHPIZE_DEPS \
    nginx \
    supervisor && \
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

RUN composer config -g repo.packagist composer https://packagist.phpcomposer.com
RUN composer create-project symfony/skeleton app

WORKDIR /usr/local/src/app

RUN composer require \
    tienvx/mbt-bundle:1.0.x-dev \
    tienvx/mbt-api-bundle:1.0.x-dev \
    symfony/yaml \
    symfony/expression-language \
    symfony/security-bundle \
    symfony/monolog-bundle && \
    composer clearcache

COPY docker/resources/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/build/mbt-api/nginx.conf /etc/nginx/conf.d/default.conf
COPY config/packages/dev/models /usr/local/src/app/config/packages/dev/models
COPY config/packages/messenger.yaml /usr/local/src/app/config/packages/messenger.yaml
COPY config/packages/doctrine.yaml /usr/local/src/app/config/packages/doctrine.yaml
COPY config/packages/tienvx_mbt.yaml /usr/local/src/app/config/packages/tienvx_mbt.yaml
COPY config/jwt /usr/local/src/app/config/jwt
COPY config/packages/api_platform.yaml /usr/local/src/app/config/packages/api_platform.yaml
COPY config/packages/lexik_jwt_authentication.yaml /usr/local/src/app/config/packages/lexik_jwt_authentication.yaml
COPY config/packages/nelmio_cors.yaml /usr/local/src/app/config/packages/nelmio_cors.yaml
COPY config/packages/security.yaml /usr/local/src/app/config/packages/security.yaml
COPY config/routes/tienvx_mbt_api.yaml /usr/local/src/app/config/routes/tienvx_mbt_api.yaml

RUN chown -R www-data:www-data /usr/local/src/app/var
RUN chown -R www-data:www-data /usr/local/src/app/config/jwt

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
