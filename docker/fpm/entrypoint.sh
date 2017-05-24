#!/usr/bin/env bash

set -e

if [ "${1:0:1}" = '-' ]; then
	set -- php-fpm "$@"
fi

cd /var/www && cat app/config/parameters.yml
cd /var/www && SYMFONY_ENV=prod composer symfony-scripts
cd /var/www && SYMFONY_ENV=prod bin/console doctrine:schema:drop --force -n
cd /var/www && SYMFONY_ENV=prod bin/console doctrine:schema:update --force -n
cd /var/www && SYMFONY_ENV=dev bin/console doctrine:fix:load -n
cd /var/www/docker/fpm && crontab cron

cd /var/www && chmod -R 777 var
cd /var/www && chmod -R 777 files

service cron start


exec "$@"