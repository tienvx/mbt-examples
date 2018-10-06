#!/bin/sh

/wait-for-it.sh -t 0 db:3306

php /api/bin/console doctrine:schema:create
cd /api/public
php /api/bin/console server:run *:80
