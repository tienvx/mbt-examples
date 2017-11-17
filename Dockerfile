FROM alpine:edge

RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories && \
    apk add --no-cache bash \
        curl \
        php7 \
        php7-fpm \
        php7-bcmath \
        php7-bz2 \
        php7-calendar \
        php7-ctype \
        php7-curl \
        php7-dom \
        php7-exif \
        php7-ftp \
        php7-gettext \
        php7-gd \
        php7-iconv \
        php7-intl \
        php7-imap \
        php7-json \
        php7-mysqlnd \
        php7-mysqli \
        php7-mcrypt \
        php7-memcached \
        php7-mbstring \
        php7-opcache \
        php7-openssl \
        php7-pdo \
        php7-pdo_mysql \
        php7-pdo_pgsql \
        php7-pdo_sqlite \
        php7-phar \
        php7-posix \
        php7-pgsql \
        php7-session \
        php7-soap \
        php7-sockets \
        php7-sqlite3 \
        php7-tokenizer \
        php7-wddx \
        php7-xml \
        php7-xmlreader \
        php7-xsl \
        php7-zip \
        php7-zlib \
        php7-fileinfo \
        php7-simplexml \
        ca-certificates \
        php7-pear \
        php7-dev \
        autoconf \
        build-base && \
    rm -rf /var/cache/apk/*

RUN pecl install xdebug

RUN echo "zend_extension=/usr/lib/php7/modules/xdebug.so" > /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_cookie_expire_time=86400" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=172.17.0.1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.profiler_enable=0" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /etc/php7/conf.d/xdebug.ini

RUN apk del php7-pear php7-dev autoconf build-base

EXPOSE 80

COPY entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

CMD ["/entrypoint.sh"]
