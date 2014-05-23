#!/bin/bash

cd "$(dirname "$(dirname "$0")")"

mkdir -p app/logs
mkdir -p bin/cache

rm -rf app/cache
mkdir -p app/cache

app/console cache:clear -e dev
app/console cache:clear -e prod

chown -R www-data:www-data app/cache
chown -R www-data:www-data app/logs
chown -R www-data:www-data bin/cache
