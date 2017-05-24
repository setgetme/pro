* docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
* docker-compose exec fpm bash
* composer install
* naprawa jebanego cache'u:
chmod 777 -R ./var/sessions
chmod 777 -R ./var/logs
chmod 777 -R ./var/cache
