FROM php:7.2-fpm-alpine3.8
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache \
    bash \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    nginx \
    supervisor && \
    docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr && \
    docker-php-ext-install gd mbstring mysqli zip && \
    apk del $PHPIZE_DEPS

WORKDIR /var/www/public

RUN curl -sSL -o opencart.tar.gz https://github.com/opencart/opencart/archive/3.1.0.0_a1.tar.gz && \
    tar xzf opencart.tar.gz --strip 2 --wildcards '*/upload/' && \
    mv config-dist.php config.php && \
    mv admin/config-dist.php admin/config.php && \
    rm opencart.tar.gz

RUN chown -R www-data:www-data /var/www/public

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
