#!/bin/sh

docker-compose exec api bin/console doctrine:database:create  --if-not-exists
docker-compose exec api bin/console doctrine:schema:update --force
docker-compose exec api bin/console cache:clear

docker pull selenoid/vnc:firefox_74.0
docker pull selenoid/vnc:chrome_80.0
docker pull selenoid/vnc:opera_67.0
docker pull selenoid/chrome-mobile:79.0
docker pull selenoid/video-recorder:latest-release
