#!/bin/sh

/wait-for-it.sh -t 0 db:3306

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

cd /api/public
php /api/bin/console server:run *:80
