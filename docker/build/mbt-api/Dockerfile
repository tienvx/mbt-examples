FROM tienvx/mbt:latest

RUN composer require tienvx/mbt-api-bundle:1.0.x-dev \
    symfony/web-server-bundle

CMD ["php", "bin/console", "server:run", "*:80"]
