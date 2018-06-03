# MBT Examples
Contains examples for mbt-bundle project

## Features
- Sample models and subjects
- Test real application (OpenCart)
- Scalable with workers


## Install
To install use the [docker-compose](https://docs.docker.com/compose/) tool.

```bash
$ cp .env.dist .env
$ docker-compose up --scale worker=4
```

## License
mbt-examples is available under the [MIT license](LICENSE).
