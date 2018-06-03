#!/bin/sh

/wait-for-it.sh -t 0 db:3306

php -S 0.0.0.0:80 -t /api/public
