#!/bin/sh

/wait-for-it.sh -t 0 db:3306

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

php -S 0.0.0.0:80 -t /app
