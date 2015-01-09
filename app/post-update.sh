#!/bin/bash

cd "$(dirname "$(dirname "$0")")"

if type php5 >/dev/null 2>&1; then
	function php {
		php5 "$@"
	}
else
	function php {
		php "$@"
	}
fi

mkdir -p app/logs
mkdir -p bin/cache

rm -rf app/cache
mkdir -p app/cache

mkdir -p web/uploads/badges
mkdir -p web/uploads/avatars
mkdir -p web/uploads/images
mkdir -p web/uploads/items
mkdir -p web/uploads/monsters
mkdir -p web/uploads/spells
mkdir -p web/media/cache

if [ "$1" == --dev ]; then
	hhvm "$(type -p composer)" install --no-scripts
else
	hhvm "$(type -p composer)" install -o --no-scripts
fi
cp -f pinned/PDOConnection.hhvm vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver
cp -f pinned/PDOStatement.hhvm vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver
cp -f pinned/QueryBuilder.hhvm vendor/doctrine/orm/lib/Doctrine/ORM

hhvm app/console cache:clear -e dev &
hhvm app/console cache:clear -e prod
wait "$!"

hhvm app/console doctrine:schema:update --dump-sql
hhvm app/console doctrine:schema:update --force

if [ "$(id -u)" == 0 ]; then
	chown -R www-data:www-data app/cache
	chown -R www-data:www-data app/logs
	chown -R www-data:www-data bin/cache
	chown -R www-data:www-data web/uploads/
	chown -R www-data:www-data web/media/
fi

if [ "$1" == --dev ]; then
	hhvm app/console assets:install --symlink --relative
else
	hhvm app/console assets:install
fi

hhvm app/console bazinga:js-translation:dump web/js
