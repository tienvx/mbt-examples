FROM php:7.2-cli-alpine3.7
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache \
    bash \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev && \
    docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr && \
    docker-php-ext-install gd mbstring mysqli zip && \
    pecl install mcrypt-1.0.1 && \
    docker-php-ext-enable mcrypt && \
    apk del $PHPIZE_DEPS

WORKDIR /usr/local/src/app

RUN curl -sSL -o opencart.tar.gz https://github.com/opencart/opencart/archive/3.0.2.0.tar.gz && \
    tar xzf opencart.tar.gz --strip 2 --wildcards '*/upload/' && \
    mv config-dist.php config.php && \
    mv admin/config-dist.php admin/config.php && \
    rm opencart.tar.gz

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

EXPOSE 80
