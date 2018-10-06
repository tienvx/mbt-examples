# MBT Examples
Contains examples for mbt-bundle project

## Features
- Sample models and subjects
- Test real application (OpenCart)
- Scalable with workers

## Dependencies

- git
- [composer](https://getcomposer.org/download/)
- docker
- [docker-compose](https://docs.docker.com/compose/)

## Install

```bash
$ git clone git@github.com:tienvx/mbt-examples.git
$ cd mbt-examples
$ composer install
$ cp .env.dist .env
$ docker-compose up --scale worker=4 --scale selenium-node=4
$ # Or for debugging
$ bash start.sh
```

## License
mbt-examples is available under the [MIT license](LICENSE).
