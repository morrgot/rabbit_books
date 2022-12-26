#!/bin/bash

set -e

echo "Composer install..."
composer install --prefer-dist --no-progress --no-interaction

echo "Build static files"
./bin/console assets:install ""

echo "Wait for database readiness..."

trap 'echo "Kill by signal"; exit 1;' INT TERM
tries=${WAIT_FOR_DATABASE_TRIES:-30};
until DATABASE_ERROR=$(bin/console dbal:run-sql "SELECT 1" 2>&1); do
    exitCode=$?
    tries=$((tries-1))
    if [[ $tries -eq 0 ]]; then
        echo -e "Database is not running or not reachable:"
        echo "$DATABASE_ERROR"
        exit 1
    else
        sleep 1
    fi
done


echo "Migrate dev database..."
./bin/console doctrine:migrations:migrate --env="dev" --no-interaction --allow-no-migration -vvv 2>&1
exitCode=$?
if [[ $exitCode != 0 ]]; then
    echo -e "Database migration error"
    exit 1
fi

echo "Migrate test database..."
./bin/console doctrine:migrations:migrate --env="test" --no-interaction --allow-no-migration -vvv 2>&1
exitCode=$?
if [[ $exitCode != 0 ]]; then
    echo -e "Database migration error"
    exit 1
fi

/usr/local/bin/docker-entrypoint.sh "$@"
