#!/bin/sh

API=$(kubectl get pod -n mbt -l io.kompose.service=api -o jsonpath="{.items[0].metadata.name}")
APP=$(kubectl get pod -n mbt -l io.kompose.service=app -o jsonpath="{.items[0].metadata.name}")

kubectl exec $API -n mbt -- bin/console doctrine:database:create  --if-not-exists
kubectl exec $API -n mbt -- bin/console doctrine:schema:update --force
kubectl exec $API -n mbt -- bin/console cache:clear
kubectl exec $APP -n mbt -- php install/cli_install.php install \
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
kubectl exec $APP -n mbt -- sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" config.php
kubectl exec $APP -n mbt -- sed -i "s/'http:\/\/example.com\/'/\$_SERVER['SERVER_NAME']/g" admin/config.php
