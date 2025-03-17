docker up:
	docker compose -p books-db up -d --build --force-recreate

docker down:
	docker compose -p books-db down

phpstan:
	docker exec books-db vendor/bin/phpstan analyse app

test:
	docker exec books-db vendor/bin/codecept run --steps

composer-install:
	docker exec books-db composer install

composer-update:
	docker exec books-db composer update