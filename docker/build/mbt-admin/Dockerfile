FROM node:8-alpine
MAINTAINER tienvx <tien.xuan.vo@gmail.com>

RUN apk add --no-cache git

WORKDIR /usr/local/src

RUN git clone https://github.com/tienvx/mbt-admin.git app

WORKDIR /usr/local/src/app

RUN npm install && \
    npm run-script build

RUN apk del git

CMD ["serve", "-s", "build"]
