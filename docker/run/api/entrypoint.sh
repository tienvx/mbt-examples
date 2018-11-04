#!/bin/sh

dockerize -wait tcp://db:3306

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

php bin/console server:run *:80
