#!/bin/sh

php /app/bin/console doctrine:schema:validate
php /app/bin/console doctrine:schema:update --force

php -S 0.0.0.0:80 -t /app/public
