# rabbit_books

## Run web

Run web server on local environment
```bash
$ docker-compose up -d
```

## Run amqp consumer

Run amqp message consumer in `php` container:
```bash
$ docker-compose exec php bash
$ php /var/www/bin/console messenger:consume async -vv
```

## Composer scripts

```bash
$ docker-compose exec php bash

# Psalm
$ composer psalm

# Tests 
$ composer test

# Tests with coverage
$ composer cover
```

## Try web requests

Try http requests for phpStorm under `./tests/http` folder in `books.http` file.
