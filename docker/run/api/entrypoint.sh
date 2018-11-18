#!/bin/sh

dockerize -wait tcp://db:3306

php bin/console doctrine:database:create  --if-not-exists
php bin/console doctrine:schema:update --force

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
