#!/bin/sh

docker-compose exec api bin/console doctrine:database:create  --if-not-exists
docker-compose exec api bin/console doctrine:schema:update --force
docker-compose exec api bin/console cache:clear

docker pull selenoid/vnc:firefox_70.0
docker pull selenoid/vnc:chrome_78.0
docker pull selenoid/vnc:opera_65.0
docker pull selenoid/chrome-mobile:75.0
docker pull selenoid/video-recorder:latest-release
