FROM tienvx/mbt:latest

RUN apk add --no-cache zlib-dev \
    chromium \
    chromium-chromedriver && \
    docker-php-ext-install zip

ENV PANTHER_NO_SANDBOX 1
ENV PANTHER_CHROME_DRIVER_BINARY /usr/lib/chromium/chromedriver

RUN composer require symfony/panther

CMD ["php", "bin/console", "messenger:consume-messages", "amqp"]
