# Nome do container
APP_CONTAINER=backend

# Comandos de desenvolvimento
up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

restart: down up

artisan:
	docker-compose exec $(APP_CONTAINER) php artisan

migrate:
	docker-compose exec $(APP_CONTAINER) php artisan migrate

tinker:
	docker-compose exec $(APP_CONTAINER) php artisan tinker

composer:
	docker-compose exec $(APP_CONTAINER) composer

npm:
	docker-compose exec frontend npm

bash:
	docker-compose exec $(APP_CONTAINER) bash


# Comandos de produção
# Subir ambiente de produção
up-prod:
	docker-compose -f docker-compose.prod.yml up -d --build

# Parar ambiente de produção
down-prod:
	docker-compose -f docker-compose.prod.yml down

# Ver logs do Nginx (produção)
logs-prod-nginx:
	docker-compose -f docker-compose.prod.yml logs -f nginx

# Acessar o container da app em produção
sh-prod:
	docker-compose -f docker-compose.prod.yml exec app bash

migrate-prod:
	docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
