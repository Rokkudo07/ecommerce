# 🐳 Guide Docker

## Architecture

Le projet utilise Docker Compose avec 3 services :

- **PHP 8.4** : Conteneur PHP-FPM avec toutes les extensions nécessaires
- **Nginx** : Serveur web pour servir l'application Symfony
- **MySQL 8.0** : Base de données

## Fichiers Docker

- `Dockerfile` : Image PHP pour la production
- `Dockerfile.dev` : Image PHP pour le développement
- `docker-compose.yml` : Configuration pour la production
- `docker-compose.dev.yml` : Configuration pour le développement
- `docker/nginx/default.conf` : Configuration Nginx
- `docker/php/php.ini` : Configuration PHP

## Démarrage rapide

```bash
# Configuration complète en une commande
make setup

# Ou étape par étape
make build
make up
make composer-install
make db-create
make migrate
make create-user EMAIL=admin@example.com PASSWORD=password123
```

## Ports

- **8000** : Application web (Nginx)
- **3306** : MySQL

## Volumes

- `mysql_data` : Persistance des données MySQL
- `.` : Code source monté dans `/var/www/html`

## Variables d'environnement

Les variables d'environnement sont définies dans :
- `.env` : Fichier principal (à créer depuis `.env.docker`)
- `docker-compose.dev.yml` : Variables spécifiques au conteneur PHP

## Commandes utiles

### Avec Make

```bash
make help          # Voir toutes les commandes
make up            # Démarrer
make down          # Arrêter
make logs          # Voir les logs
make shell         # Shell PHP
make migrate       # Migrations
make cache-clear   # Vider le cache
```

### Avec Docker Compose

```bash
# Exécuter une commande Symfony
docker-compose -f docker-compose.dev.yml exec php php bin/console [commande]

# Accéder à MySQL
docker-compose -f docker-compose.dev.yml exec mysql mysql -uroot -proot marketing_cms

# Voir les logs d'un service
docker-compose -f docker-compose.dev.yml logs -f php
docker-compose -f docker-compose.dev.yml logs -f nginx
docker-compose -f docker-compose.dev.yml logs -f mysql
```

## Dépannage

### Les conteneurs ne démarrent pas

```bash
# Vérifier les logs
make logs

# Reconstruire les images
docker-compose -f docker-compose.dev.yml build --no-cache
```

### Problème de permissions

```bash
# Fixer les permissions dans le conteneur
docker-compose -f docker-compose.dev.yml exec php chown -R www-data:www-data /var/www/html/var
```

### Base de données non accessible

```bash
# Vérifier que MySQL est démarré
docker-compose -f docker-compose.dev.yml ps

# Tester la connexion
docker-compose -f docker-compose.dev.yml exec php php bin/console doctrine:database:create
```

### Réinitialiser complètement

```bash
# Arrêter et supprimer tout (y compris les volumes)
docker-compose -f docker-compose.dev.yml down -v

# Reconstruire et redémarrer
make setup
```
