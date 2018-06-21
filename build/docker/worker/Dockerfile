FROM php:7.2-cli-alpine3.7
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache bash rabbitmq-c rabbitmq-c-dev $PHPIZE_DEPS && \
    pecl install amqp xdebug && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable amqp xdebug && \
    apk del $PHPIZE_DEPS

RUN curl -sSL -o /wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /wait-for-it.sh

RUN mkdir -p /worker/var
WORKDIR /worker

COPY resources/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY docker/worker/entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

CMD ["/entrypoint.sh"]
