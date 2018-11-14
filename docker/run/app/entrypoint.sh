#!/bin/sh

dockerize -wait tcp://db:3306

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

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
