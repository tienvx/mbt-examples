#!/bin/sh

dockerize -wait tcp://db:3306
dockerize -wait http://api:80

echo 'Installing OpenCart...'
php install/cli_install.php install \
    --db_hostname db \
    --db_username root \
    --db_password root \
    --db_database db \
    --db_driver mysqli \
    --db_port 3306 \
    --username admin \
    --password admin \
    --email youremail@example.com \
    --http_server http://example.com/

sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" config.php
sed -i "s/'http:\/\/example.com\/admin\/'/\$_SERVER['SERVER_NAME'] . 'admin\/'/g" admin/config.php

curl --silent --output /dev/null -X POST http://api/api/register -d username=test -d password=test

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
