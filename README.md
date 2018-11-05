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
- openssl

## Install

```bash
$ git clone git@github.com:tienvx/mbt-examples.git
$ cd mbt-examples
$ # Generate ssh keys
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
$ # If you are required to input password, run these commands, otherwise skip them
$ openssl rsa -in config/jwt/private.pem -out config/jwt/private2.pem
$ mv config/jwt/private.pem config/jwt/private.pem-back
$ mv config/jwt/private2.pem config/jwt/private.pem
$ # Start
$ docker-compose up --scale worker=4
```

## Usage

- [api](http://localhost/api)
- [app](http://localhost:81)
- [admin](http://localhost:82)
- register user
```
curl -X POST http://localhost/api/register -d username=test -d password=test -d email=test@example.com
```

## License
mbt-examples is available under the [MIT license](LICENSE).
