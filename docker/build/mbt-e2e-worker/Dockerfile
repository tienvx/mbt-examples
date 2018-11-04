FROM tienvx/mbt:latest

RUN apk add --no-cache zlib-dev chromium && \
    docker-php-ext-install zip
ENV PANTHER_NO_SANDBOX 1

RUN composer require symfony/panther

CMD ["php", "bin/console", "messenger:consume-messages", "amqp"]
