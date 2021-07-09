#!/bin/bash

APP = aktak
VERSION := $(shell cat ./VERSION)
DOCKER_REPO_NGINX = ikerib/${APP}_nginx:${VERSION}
DOCKER_REPO_APP = ikerib/${APP}_app_prod:${VERSION}
USER_ID = $(shell id -u)
GROUP_ID= $(shell id -g)
user==www-data

help:
	@echo 'usage: make [target]'
	@echo
	@echo 'targets'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

build:
	@echo 'Create temp folder'
	@echo '------------------'
	rm -fr docker_temp
	mkdir -p docker_temp/app
	rm -fr ~/docker_temp/
	mkdir -p ~/docker_temp/app
	@echo
	@echo
	@echo 'Copy all to temp folder'
	@echo '-----------------------'
	cp -r . ~/docker_temp/app
	@echo
	@echo
	@echo 'Remove all unnecesary files and folders'
	@echo '---------------------------------------'
	rm -fr ~/docker_temp/app/.git/
	rm -fr ~/docker_temp/app/.idea/
	rm -fr ~/docker_temp/app/fitxategiak/
	rm -fr ~/docker_temp/app/node_modules/
	rm -fr ~/docker_temp/app/vendor/
	rm -fr ~/docker_temp/app/tmp/
	@echo
	@echo
	@echo 'Copy prod folders to temp folder with project to create a docker image'
	@echo '----------------------------------------------------------------------'
	cp -r ~/docker_temp/app ./docker_temp/
	#mv ./docker_temp/docker_temp ./docker_temp/app
	docker build -t ${DOCKER_REPO_APP} --file=docker/prod/php/Dockerfile .
	rm -fr ./docker_temp/
	@echo
	@echo
	@echo '-------------------'
	@echo 'Process finished OK'
	@echo '-------------------'
	

deploy: build
	#	Dockerfile available args
	#	ARG user=www-data
	#	ARG USER_ID=1000
	#	ARG GROUP_ID=1000
	#	ARG DOCKER_TIMEZONE=Europe/Madrid

	# docker build -t ${DOCKER_REPO_APP} --file=docker/prod/php/Dockerfile .
	
	docker build -t ${DOCKER_REPO_NGINX} --file=docker/prod/nginx/Dockerfile .
	docker push ${DOCKER_REPO_NGINX}
	docker push ${DOCKER_REPO_APP}

restart:
	$(MAKE) stop && $(MAKE) run

run:
	USER_ID=${USER_ID} docker-compose up -d

ssh:
	docker-compose exec app zsh

stop:
	USER_ID=${USER_ID} docker-compose down
