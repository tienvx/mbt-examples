#!/bin/sh

wait-for db:3306 --timeout=99

while true
do
    php /usr/local/src/app/bin/console messenger:consume async --limit=1 --time-limit=1 -q
done
