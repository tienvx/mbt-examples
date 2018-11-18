#!/bin/sh

docker rmi tienvx/mbt-worker
docker rmi tienvx/mbt-e2e-worker
docker rmi tienvx/mbt-api
docker rmi tienvx/mbt-admin

docker build -t tienvx/mbt-worker -f ./docker/build/mbt-worker/Dockerfile .
docker build -t tienvx/mbt-e2e-worker -f ./docker/build/mbt-e2e-worker/Dockerfile .
docker build -t tienvx/mbt-api -f ./docker/build/mbt-api/Dockerfile .
docker build -t tienvx/mbt-admin -f ./docker/build/mbt-admin/Dockerfile .

docker push tienvx/mbt-worker
docker push tienvx/mbt-e2e-worker
docker push tienvx/mbt-api
docker push tienvx/mbt-admin
