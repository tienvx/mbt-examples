#!/bin/bash

HOST_IP=`ifconfig $(netstat -rn | grep -E '^default|^0.0.0.0' | head -1 | awk '{print \$NF}') | grep 'inet ' | awk '{print \$2}' | grep -Eo '([0-9]*\\.){3}[0-9]*'`
export HOST_IP=$HOST_IP && docker-compose up
