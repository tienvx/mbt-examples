#!/bin/sh

docker-compose exec api openssl genrsa -out config/jwt/private.pem -aes256 4096
docker-compose exec api openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
docker-compose exec api chmod +r config/jwt/private.pem
docker-compose exec api openssl rsa -in config/jwt/private.pem -out config/jwt/private.pem
docker-compose exec api bin/console doctrine:database:create  --if-not-exists
docker-compose exec api bin/console doctrine:schema:update --force
docker-compose exec api bin/console cache:clear
docker-compose exec api chmod a+w -R var/cache/prod
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
docker-compose exec app php install/customer_install.php
docker-compose exec app sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" config.php
docker-compose exec app sed -i "s/'http:\/\/example.com\/admin\/'/\$_SERVER['SERVER_NAME'] . 'admin\/'/g" admin/config.php