build:
	docker-compose build
up: build
	docker-compose up -d
install: build
	docker-compose run --rm --no-deps app composer install
down:
	docker-compose kill
bash: build
	docker-compose run --rm --no-deps app bash
