FROM php:7.2-cli-alpine3.7
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache bash

WORKDIR /api

RUN curl -sSL -o /wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /wait-for-it.sh

EXPOSE 80

COPY entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

CMD ["/entrypoint.sh"]
