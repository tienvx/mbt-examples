# MBT Examples [![Build Status][travis_badge]][travis_link]

This project contains example models to demonstrate how to test using MBT Bundle tool.

There are 2 ways to run these examples:
* [Deployment mode](#deployment-mode)
  * If you want to see how everything work together
  * Require more CPU and RAM
  * With UI
* [Development mode](#development-mode)
  * If you want to customize code & quickly test it
  * Require less CPU and RAM
  * Without UI

## Deployment mode

### Requirements

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### Install

```
$ cd docker
$ docker network create selenoid
$ docker-compose up --scale worker=2
$ bash install.sh # Run on another terminal
```

### Usage

Open web browser, navigate to http://localhost and login using admin/admin, or register new user

### Notes

* Put your models to config/packages/dev directory
* Number of workers (2) must be less than or equals to hub's limit (5)
* Need at least 270MB RAM free (on idle, 2 workers), more if tasks are in progress
* Build your own windows images at https://github.com/aerokube/windows-images
* Useful tools available on other ports:
  * [app](http://localhost:81)
  * [api](http://localhost:82/api)
  * [rabbitmq](http://localhost:83)
  * [minio](http://localhost:84)
  * [selenoid ui](http://localhost:85)

## Development mode

### Requirements

* [PHP](https://www.php.net/manual/en/install.php)
* [Composer](https://getcomposer.org/download/)

### Install

```
$ composer install
```

### Usage

Run the following command to test a model. Replace `[MODEL_NAME]` by:
* api_cart
* checkout
* mobile_home
* product
* shopping_cart

```
$ env PANTHER_NO_HEADLESS=1 php bin/console mbt:model:test [MODEL_NAME] --generator random --generator-options '{"maxSteps": 20}'
```

The command will open new Chrome window, navigate to https://demo.opencart.com/

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
$ composer create-project symfony/skeleton your-project
$ cd your-project
$ composer require --dev phpunit friendsofphp/php-cs-fixer # Optional
$ composer require tienvx/mbt-bundle webmozart/assert symfony/http-client symfony/panther symfony/expression-language symfony/security-bundle symfony/maker-bundle
```

## License

This package is available under the [MIT license](LICENSE).

[travis_badge]: https://travis-ci.org/tienvx/mbt-examples.svg?branch=master
[travis_link]: https://travis-ci.org/tienvx/mbt-examples
