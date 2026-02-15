.PHONY: help build up down restart logs shell composer-install migrate create-user

DC = docker compose -f docker-compose.dev.yml

help: ## Affiche l'aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Construire les images Docker
	$(DC) build

up: ## Démarrer les conteneurs
	$(DC) up -d

down: ## Arrêter les conteneurs
	$(DC) down

restart: ## Redémarrer les conteneurs
	$(DC) restart

logs: ## Voir les logs
	$(DC) logs -f

ps: ## Voir les conteneurs
	$(DC) ps

shell: ## Accéder au shell du conteneur PHP
	$(DC) exec php sh || $(DC) exec php bash

composer-install: ## Installer les dépendances Composer
	$(DC) exec php composer install --optimize-autoloader

composer-update: ## Mettre à jour les dépendances Composer
	$(DC) exec php composer update --optimize-autoloader

db-create: ## Créer la base de données
	$(DC) exec php php bin/console doctrine:database:create --if-not-exists

migrate: ## Exécuter les migrations
	$(DC) exec php php bin/console doctrine:migrations:migrate --no-interaction || true

migration: ## Créer une nouvelle migration
	$(DC) exec php php bin/console make:migration

create-user: ## Créer un utilisateur admin
	$(DC) exec php php bin/console app:create-user $(EMAIL) $(PASSWORD)

cache-clear: ## Vider le cache
	$(DC) exec php php bin/console cache:clear

sass: ## Compiler les fichiers Sass
	$(DC) exec php sass assets/scss/main.scss public/css/main.css

sass-watch: ## Compiler Sass en mode watch
	$(DC) exec php sh -c "sass --watch assets/scss/main.scss:public/css/main.css"

sass-prod: ## Compiler Sass production
	$(DC) exec php sass --style=compressed assets/scss/main.scss public/css/main.css

rebuild: ## Rebuild complet clean
	$(DC) down -v
	$(DC) build --no-cache
	$(DC) up -d

setup: build up composer-install db-create migrate cache-clear sass ## Configuration complète
