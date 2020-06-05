#!/bin/sh

docker-compose exec api bin/console doctrine:database:create  --if-not-exists
docker-compose exec api bin/console doctrine:schema:update --force
docker-compose exec api bin/console cache:clear
docker-compose exec app php install/cli_install.php install \
      --db_hostname db \
      --db_username user \
      --db_password pass \
      --db_database db \
      --db_prefix oc_ \
      --db_driver mysqli \
      --db_port 3306 \
      --username admin \
      --password admin \
      --email youremail@example.com \
      --http_server http://example.com/
docker-compose exec app sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" config.php
docker-compose exec app sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" admin/config.php

docker pull selenoid/vnc:firefox_74.0
docker pull selenoid/vnc:chrome_80.0
docker pull selenoid/vnc:opera_67.0
docker pull selenoid/chrome-mobile:79.0
docker pull selenoid/video-recorder:latest-release
