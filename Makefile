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
		docker compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICES} composer install

