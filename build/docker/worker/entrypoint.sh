#!/bin/sh

/wait-for-it.sh -t 0 queue:5672
/wait-for-it.sh -t 0 db:3306

php /worker/bin/console messenger:consume-messages amqp
