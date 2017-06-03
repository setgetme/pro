# uruchomienie projektu
* docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
# bash kontenera
* docker-compose exec fpm bash
# instalacja zależności
* composer install
# naprawa jebanego cache'u:
* chmod 777 -R ./var/sessions
* chmod 777 -R ./var/logs
* chmod 777 -R ./var/cache
# tworzenie bazy (uruchamiać w kontenerze):
* php bin/console doctrine:database:create
# tworzenie schematu bazy (uruchamiać w kontenerze):
* php bin/console doctrine:schema:create
# update schematu bazy (uruchamiać w kontenerze):
* php bin/console doctrine:schema:update --force

Projekt działa na porcie: http://localhost:8080/
Baza działa na porcie: http://localhost:8083/ (root, root)
Wersja developerska: http://localhost:8080/app_dev.php/
Wersja produkcyjna: http://localhost:8080/