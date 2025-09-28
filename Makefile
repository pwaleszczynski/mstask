COMPOSE_FILE=./build/docker/docker-compose.yml
COMPOSE_ENV_FILE=./build/docker/.env
COMPOSE=docker-compose -f ${COMPOSE_FILE} --env-file ${COMPOSE_ENV_FILE}
COMPOSE_EXEC_PHP=${COMPOSE} exec php

up:
	${COMPOSE} up -d

down:
	${COMPOSE} down --remove-orphans

composer-install:
	${COMPOSE_EXEC_PHP} composer install

db-drop:
	${COMPOSE_EXEC_PHP} bin/console doctrine:schema:drop --full-database --force

db-create:
	${COMPOSE_EXEC_PHP} bin/console doctrine:schema:create

db-migrate:
	${COMPOSE_EXEC_PHP} bin/console doctrine:migrations:migrate

db-fill:
	${COMPOSE_EXEC_PHP} bin/console doctrine:fixtures:load --purge-exclusions=PURGE-EXCLUSIONS -n

db-set:db-drop db-create db-fill

start:down up composer-install

exec:
	${COMPOSE_EXEC_PHP} bash

warnings-generate:
	${COMPOSE_EXEC_PHP} bin/console app:warnings:generate
