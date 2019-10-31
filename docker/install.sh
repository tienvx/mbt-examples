#!/bin/sh

docker-compose --project-name mbt-examples exec api bin/console doctrine:database:create  --if-not-exists
docker-compose --project-name mbt-examples exec api bin/console doctrine:schema:update --force
docker-compose --project-name mbt-examples exec api bin/console cache:clear
docker-compose --project-name mbt-examples exec app php install/cli_install.php install \
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
docker-compose --project-name mbt-examples exec app sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" config.php
docker-compose --project-name mbt-examples exec app sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" admin/config.php

docker pull selenoid/vnc:firefox_68.0
docker pull selenoid/vnc:chrome_76.0
docker pull selenoid/vnc:opera_60.0
docker pull selenoid/chrome-mobile:75.0
docker pull selenoid/video-recorder:latest-release
