# MBT Examples
Contains examples for mbt-bundle project

## Features
- Sample models and subjects
- Test real application (OpenCart)
- Scalable with workers
- Test API
- Test multi browsers (Chrome, Firefox)
- Test mobile (Chrome mobile emulator, but Android emulator also possible)

## Dependencies

- git
- docker
- [docker-compose](https://docs.docker.com/compose/)
- [compose-on-kubernetes](https://github.com/docker/compose-on-kubernetes)

## Install

```bash
$ git clone git@github.com:tienvx/mbt-examples.git
$ cd mbt-examples
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
- [selenium hub](http://localhost:85)

## Note
* Number of workers (4) must less than or equals to max instances of selenium grid node (e.g. chrome - 4, firefox - 4)
* Need at least 1,6GB ram free to start examples

## License
mbt-examples is available under the [MIT license](LICENSE).
