# Usage:
# make      # build all docker images
# make push # push ALL docker images to docker hub

.PHONY: all worker api

TAG?=v1.16.0

all: worker api api-nginx

worker:
	docker build -t tienvx/mbt-examples-worker:${TAG} -f docker/build/Dockerfile.worker .

api:
	docker build -t tienvx/mbt-examples-api:${TAG} -f docker/build/Dockerfile.api .

api-nginx:
	docker build -t tienvx/mbt-examples-api-nginx:${TAG} -f docker/build/Dockerfile.api-nginx .

push:
	docker push tienvx/mbt-examples-worker:${TAG}
	docker push tienvx/mbt-examples-api:${TAG}
	docker push tienvx/mbt-examples-api-nginx:${TAG}
