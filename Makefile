UID=$(shell id -u)
GID=$(shell id -g)
DOCKER_PHP_SERVICE=php-fpm
DOCKER_DB_SERVICE=postgres
DOCKER_DB_PORT=5432
DOCKER_LOCALSTACK_SERVICE=localstack

build:
		docker compose build --no-cache && \
		docker compose pull

erase:
		docker compose down -v

start:
		docker compose up -d

stop:
		docker compose stop

bash:
		docker compose run --rm -u ${UID}:${GID} php-fpm sh

composer-install:
		docker compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} composer install

cache:
		docker compose exec -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console cache:clear

composer-install:
		docker compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} composer install

cache:
		docker compose exec -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console cache:clear

recreate-db-and-fixtures:
		docker compose exec -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:database:drop --force
		docker compose exec -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:database:create
		docker compose exec -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:schema:create
		docker compose exec -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:fixtures:load --no-interaction

grumphp:
		docker compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} grumphp run

.PHONY: tests
tests:
		docker compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} phpunit --configuration phpunit.xml.dist --no-coverage