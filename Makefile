# TravelDock - Commandes Docker + Symfony
.PHONY: help start stop restart build install migrate fixtures shell logs status

help: ## Affiche l'aide
	@echo "Commandes disponibles :"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

start: ## Démarre tous les containers
	docker compose up -d
	@echo "Application disponible sur http://localhost:8080"
	@echo "phpMyAdmin disponible sur http://localhost:8081"

stop: ## Arrête tous les containers
	docker compose down

restart: ## Redémarre tous les containers
	docker compose restart

build: ## Reconstruit les containers
	docker compose build --no-cache

install: ## Installe Symfony via Composer
	docker compose exec php composer install
	docker compose exec php php bin/console cache:clear

migrate: ## Exécute les migrations Doctrine
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

fixtures: ## Insère des données de test en base
	docker compose exec php php bin/console doctrine:fixtures:load --no-interaction 2>/dev/null || \
	docker compose exec php php bin/console app:load-fixtures

cache: ## Vide le cache Symfony
	docker compose exec php php bin/console cache:clear

shell: ## Ouvre un shell dans le container PHP
	docker compose exec php bash

logs: ## Affiche les logs des containers
	docker compose logs -f

status: ## Affiche le statut des containers
	docker compose ps

db-create: ## Crée la base de données
	docker compose exec php php bin/console doctrine:database:create --if-not-exists

routes: ## Affiche toutes les routes
	docker compose exec php php bin/console debug:router

setup: build start install db-create migrate ## Setup complet (build + install + migrate)
	@echo "Setup terminé ! Accès : http://localhost:8080"
