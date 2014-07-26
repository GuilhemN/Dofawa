#!/bin/bash

cd "$(dirname "$(dirname "$0")")"

mkdir -p app/logs
mkdir -p bin/cache

rm -rf app/cache
mkdir -p app/cache

composer install -o --no-scripts

app/console cache:clear -e dev &
app/console cache:clear -e prod
wait "$!"

app/console doctrine:schema:update --dump-sql
app/console doctrine:schema:update --force

if [ "$(id -u)" == 0 ]; then
	chown -R www-data:www-data app/cache
	chown -R www-data:www-data app/logs
	chown -R www-data:www-data bin/cache
fi

app/console assets:install
