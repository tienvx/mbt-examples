# MBT Examples [![Build Status][travis_badge]][travis_link]

This project contains example models to demonstrate how to test using MBT Bundle tool.

There are 2 ways to run these examples:
* [With Docker](#with-docker)
  * Everything are pre-configured, include admin UI
  * Require more CPU and RAM
* [Without Docker](#without-docker)
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
$ curl -d '{"username":"admin","password":"admin"}' -H "Content-Type: application/json" -XPOST http://localhost:82/mbt-api/register
```

### Usage

Open web browser, navigate to http://localhost and login using admin/admin, or register new user

### Notes

* Put your models to config/packages/dev directory
* Number of workers (2) must be less than or equals to hub's limit (5)
* Need at least 170MB RAM free (on idle, 2 workers), more if tasks are in progress
* Build your own windows images at https://github.com/aerokube/windows-images
* Useful tools available on other ports:
  * [app](http://localhost:81)
  * [api](http://localhost:82/api)
  * [minio](http://localhost:83)
  * [selenoid ui](http://localhost:84)

## Without Docker

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

The command will open new Chrome window, and navigate to https://demo.opencart.com/

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

### Create your own project

```
$ composer create-project tienvx/mbt-skeleton my-project
$ cd your-project
$ composer require --dev phpunit friendsofphp/php-cs-fixer # Optional
```

## License

This package is available under the [MIT license](LICENSE).

[travis_badge]: https://travis-ci.org/tienvx/mbt-examples.svg?branch=master
[travis_link]: https://travis-ci.org/tienvx/mbt-examples
