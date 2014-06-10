#!/bin/bash

cd "$(dirname "$(dirname "$0")")"

mkdir -p app/logs
mkdir -p bin/cache

rm -rf app/cache
mkdir -p app/cache

composer install

app/console cache:clear -e dev
app/console cache:clear -e prod

app/console doctrine:schema:update --force

chown -R www-data:www-data app/cache
chown -R www-data:www-data app/logs
chown -R www-data:www-data bin/cache

app/console assets:install
