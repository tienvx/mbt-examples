FROM php:cli-alpine3.7
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

WORKDIR /app

RUN curl -sSL -o opencart.tar.gz https://github.com/opencart/opencart/archive/3.0.2.0.tar.gz && \
    tar xzf opencart.tar.gz --strip 2 --wildcards '*/upload/' && \
    mv config-dist.php config.php && \
    mv admin/config-dist.php admin/config.php && \
    rm opencart.tar.gz && \
    curl -sSL -o /wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /wait-for-it.sh

COPY entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

CMD ["/entrypoint.sh"]
