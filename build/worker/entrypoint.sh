#!/bin/sh

/wait-for-it.sh -t 0 queue:5672

php /worker/bin/console messenger:consume-messages amqp
