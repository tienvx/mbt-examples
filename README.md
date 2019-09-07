# MBT Examples
Contains examples for mbt-bundle project

## Features
- Sample models and subjects
- Test real application (OpenCart)
- Scalable
- Test API
- Test multi browsers (Chrome, Firefox, Opera)
- Test mobile (Chrome on Android emulator)

## Dependencies

- git
- docker
- [docker-compose](https://docs.docker.com/compose/)

## Install

```bash
$ git clone git@github.com:tienvx/mbt-examples.git
$ cd mbt-examples
$ docker network create selenoid
$ docker-compose --compatibility up
$ # Then open terminal, run these commands once:
$ bash install.sh
$ # Open browser, go to http://localhost:82/
```

## Usage

- [api](http://localhost/api)
- [app](http://localhost:81)
- [admin](http://localhost:82)
- [rabbitmq](http://localhost:83)
- [minio](http://localhost:84)
- [selenoid ui](http://localhost:85)

## Note
* Number of workers (4) must be equals to hub's limit (4)
* Need at least 400MB ram free (on idle, more if tasks are in progress)

## License
mbt-examples is available under the [MIT license](LICENSE).
