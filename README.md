# MBT Examples [![Build Status][travis_badge]][travis_link]

This project contains example models to demonstrate how to test using MBT Bundle tool.

There are 3 ways to run these examples:
* [With Docker](#with-docker)
  * Everything are pre-configured, include admin UI
  * Require more CPU and RAM
* [With Kubernetes](#with-kubernetes)
  * Everything are pre-configured, include admin UI
  * Require Kubernetes cluster
  * Require even more CPU and RAM
* [Command Line](#command-line)
  * Code can be customized & quickly tested on command line, without admin UI
  * Require less CPU and RAM

## With Docker

### Requirements

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### Install

```
$ docker network create selenoid
$ docker-compose up --scale worker=2
$ # Open another terminal
$ ./docker/install.sh
```

### Usage

Open web browser, navigate to http://localhost and register new user (the first user will have role admin)

### Notes

* Put your models to config/packages/dev directory
* Number of workers (2) must be less than or equals to hub's limit (5)
* Need at least 220MB RAM free (2 idle workers), more if tasks are in progress
* Build your own windows images at https://github.com/aerokube/windows-images
* Useful tools available on other ports:
  * [api](http://localhost:82/api)
  * [minio](http://localhost:83)
  * [selenoid ui](http://localhost:84)

## With Kubernetes

### Requirements

* A Kubernetes's cluster
* [kubectl](https://kubernetes.io/docs/setup/production-environment/tools/kubeadm/install-kubeadm/#installing-kubeadm-kubelet-and-kubectl)

### Install

Update your domain and your email in ./kubernetes/ingress.yaml,
 ./kubernetes/issuer.yaml and ./kubernetes/services/admin-docker--env.yaml

Then run:
```
$ kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/nginx-0.26.1/deploy/static/mandatory.yaml
$ kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/nginx-0.26.1/deploy/static/provider/cloud-generic.yaml
$ kubectl create namespace cert-manager
$ kubectl apply --validate=false -f https://github.com/jetstack/cert-manager/releases/download/v0.12.0/cert-manager.yaml
$ kubectl apply -f ./kubernetes/issuer.yaml
$ kubectl apply -f ./kubernetes/moon.yaml
$ kubectl create namespace mbt
$ kubectl apply -f ./kubernetes/ingress.yaml
$ kubectl apply -f ./kubernetes/hub.yaml
$ kubectl apply -f ./kubernetes/services
$ ./kubernetes/install.sh
```

If you are using `kubeadm` or `minikube`:
* External ip of ingress-nginx service may be pending. In that case, change it to ip address of master node:
```
$ kubectl get service ingress-nginx -n ingress-nginx
$ kubectl patch svc ingress-nginx -n ingress-nginx -p '{"spec": {"type": "LoadBalancer", "externalIPs":["192.168.10.251"]}}'
```
* You may need to update /etc/hosts
```
192.168.10.251 demo.mbtbundle.org api.mbtbundle.org opencart.mbtbundle.org
```
* Change https://api.mbtbundle.org to http://api.mbtbundle.org in ./kubernetes/services/admin-docker--env.yaml
* Change https://demo.mbtbundle.org to http://demo.mbtbundle.org in ./kubernetes/services/api-docker--env-configmap.yaml

### Usage

Open web browser, navigate to https://your-domain.com and register new user (the first user will have role admin)

### Notes

* [Moon](https://aerokube.com/moon/latest/#_installing_license) only allow you to run up to 4 parallel sessions for free.
* 4 is also the default number of workers.

## Command Line

### Requirements

* [PHP](https://www.php.net/manual/en/install.php)
* [Composer](https://getcomposer.org/download/)

### Install

```
$ composer install
```

### Usage

Run the following command to test a model:

```
$ env PANTHER_NO_HEADLESS=1 php bin/console mbt:model:test [MODEL_NAME] --generator random --generator-options '{"maxSteps": 20}'
```

**Note** - replace `[MODEL_NAME]` by:
* api_cart
* checkout
* mobile_home
* product
* shopping_cart

The command will open new Chrome window, and navigate to https://opencart.mbtbundle.org/

### Generate code

```
$ php bin/console make:generator model_name ClassName
$ php bin/console make:subject model_name ClassName
$ php bin/console make:reducer model_name ClassName
$ php bin/console make:reporter model_name ClassName
```

### Clear cache

```
$ php bin/console cache:clear
```

## Create your own project

```
$ composer create-project tienvx/mbt-skeleton my-project
$ cd your-project
$ composer require --dev phpunit friendsofphp/php-cs-fixer # Optional
```

## License

This package is available under the [MIT license](LICENSE).

[travis_badge]: https://travis-ci.org/tienvx/mbt-examples.svg?branch=master
[travis_link]: https://travis-ci.org/tienvx/mbt-examples
