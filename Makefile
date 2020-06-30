#!/bin/bash

CACHE_DATE = no_date
DOCKER_APP = aktak_app
VERSION := $(shell cat ./VERSION)
DOCKER_REPO_NGINX = ikerib/aktak_nginx:${VERSION}
DOCKER_REPO_APP = ikerib/aktak_app:${VERSION}
USER_ID = $(shell id -u)

help:
	@echo 'usage: make [target]'
	@echo
	@echo 'targets'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

run:
	USER_ID=${USER_ID} docker-compose -f docker-compose.yml up -d

stop:
	USER_ID=${USER_ID} docker-compose -f docker-compose.yml stop

build:
	USER_ID=${USER_ID} docker-compose -f docker-compose.yml build

restart:
	$(MAKE) stop && $(MAKE) run

ssh:
	USER_ID=${USER_ID} docker exec -it --user ${USER_ID} ${DOCKER_APP} bash

composer-install:
	USER_ID=${USER_ID} docker exec -it --user ${USER_ID} ${DOCKER_APP} composer install --no-scripts --no-interaction --optimize-autoloader

deploy:
	sed -i '/APP_ENV/s/=.*/=prod/g'  .env
	sed -i '/APP_DEBUG/s/=.*/=0/g'  .env
	docker build --build-arg CACHE_DATE=$(date +%Y-%m-%d:%H:%M:%S) -t ${DOCKER_REPO_NGINX} --file=docker/nginx/Dockerfile .
	docker build --build-arg CACHE_DATE=$(date +%Y-%m-%d:%H:%M:%S) -t ${DOCKER_REPO_APP} --file=docker/php/prod/Dockerfile .
	docker push ${DOCKER_REPO_NGINX}
	docker push ${DOCKER_REPO_APP}
	sed -i '/APP_ENV/s/=.*/=dev/g'  .env
	sed -i '/APP_DEBUG/s/=.*/=1/g'  .env

default: deploy
