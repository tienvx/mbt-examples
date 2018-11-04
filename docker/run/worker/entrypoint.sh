#!/bin/sh

dockerize -wait tcp://db:3306 -wait tcp://queue:5672 -timeout 30s

php bin/console messenger:consume-messages amqp
