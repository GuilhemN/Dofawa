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

if [ "$1" == --dev ]; then
	php "$(type -p composer)" install --no-scripts
else
	php "$(type -p composer)" install -o --no-scripts
fi
cp -f pinned/PDOConnection.php vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver
cp -f pinned/PDOStatement.php vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver
cp -f pinned/QueryBuilder.php vendor/doctrine/orm/lib/Doctrine/ORM

php app/console cache:clear -e dev &
php app/console cache:clear -e prod
wait "$!"

php app/console doctrine:schema:update --dump-sql
php app/console doctrine:schema:update --force

if [ "$(id -u)" == 0 ]; then
	chown -R www-data:www-data app/cache
	chown -R www-data:www-data app/logs
	chown -R www-data:www-data bin/cache
fi

if [ "$1" == --dev ]; then
	php app/console assets:install --symlink --relative
else
	php app/console assets:install
fi

php app/console bazinga:js-translation:dump web/js
