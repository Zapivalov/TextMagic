init: up install db fixtures ## Initialize environment

install:
	docker compose exec php composer install

up:
	docker compose up -d

db:
	docker compose exec php bin/console doctrine:migrations:migrate

fixtures:
	docker compose exec php bin/console doctrine:fixtures:load

