FROM php:fpm-alpine3.7
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --update bash autoconf build-base libpng-dev libjpeg-turbo-dev libmcrypt-dev && \
    rm -rf /var/cache/apk/* && \
    docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr && \
    docker-php-ext-install gd mbstring mysqli zip && \
    pecl install mcrypt-1.0.1 && \
    docker-php-ext-enable mcrypt

RUN echo 'Downloading OpenCart and tools...' && \
    curl -sSL -o opencart.tar.gz https://github.com/opencart/opencart/archive/3.0.2.0.tar.gz && \
    tar xzf opencart.tar.gz --strip 2 --wildcards '*/upload/' && \
    mv config-dist.php config.php && \
    mv admin/config-dist.php admin/config.php && \
    rm opencart.tar.gz && \
    chown -R www-data:www-data /var/www && \
    curl -sSL -o /wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /wait-for-it.sh

COPY entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

CMD ["/entrypoint.sh"]
