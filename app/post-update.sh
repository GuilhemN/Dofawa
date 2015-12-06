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

mkdir -p var/logs

rm -rf var/cache
mkdir -p var/cache

mkdir -p web/uploads/badges
mkdir -p web/uploads/avatars
mkdir -p web/uploads/images
mkdir -p web/uploads/items
mkdir -p web/uploads/monsters
mkdir -p web/uploads/spells
mkdir -p web/media/cache

if [ "$1" == --dev ]; then
	php "$(type -p composer)" install --no-scripts
else
	php "$(type -p composer)" install -o --no-scripts
fi

php bin/console cache:clear -e dev &
php bin/console cache:clear -e prod
wait "$!"

php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force

if [ "$(id -u)" == 0 ]; then
	chown -R www-data:www-data var/cache
	chown -R www-data:www-data var/logs
	chown -R www-data:www-data web/uploads/
	chown -R www-data:www-data web/media/
fi

if [ "$1" == --dev ]; then
	php bin/console assets:install --symlink --relative
else
	php bin/console assets:install
fi
