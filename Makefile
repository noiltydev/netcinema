init: build
up: docker-up
stop: docker-stop
down: docker-down
restart: down up
build: docker-down-clear docker-build docker-up

php-cli:
	docker compose run --rm php-cli bash

php-fpm:
	docker compose run --rm php-fpm bash

docker-up:
	docker compose up -d --build

docker-stop:
	docker compose stop

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-build:
	docker compose build --pull
