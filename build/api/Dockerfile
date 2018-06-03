FROM php:7.2-cli-alpine3.7
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache bash

WORKDIR /api

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "/api/public"]
