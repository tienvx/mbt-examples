#!/bin/sh

docker rmi tienvx/mbt
docker rmi tienvx/mbt-worker
docker rmi tienvx/mbt-e2e-worker
docker rmi tienvx/mbt-api
docker rmi tienvx/mbt-admin

docker build -t tienvx/mbt ./mbt
docker build -t tienvx/mbt-worker ./mbt-worker
docker build -t tienvx/mbt-e2e-worker ./mbt-e2e-worker
docker build -t tienvx/mbt-api ./mbt-api
docker build -t tienvx/mbt-admin ./mbt-admin

docker push tienvx/mbt
docker push tienvx/mbt-worker
docker push tienvx/mbt-e2e-worker
docker push tienvx/mbt-api
docker push tienvx/mbt-admin
