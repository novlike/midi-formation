up:
	docker-compose up -d
down:
	docker-compose kill
bash:
	docker-compose run --rm --no-deps app bash