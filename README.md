# Marketing CMS - Symfony

Site marketing + mini CMS custom avec Symfony.

## 🎯 Caractéristiques

- **Minimaliste** : Architecture simple et maintenable
- **CMS léger** : Gestion de pages avec blocs JSON flexibles
- **Admin simple** : CRUD fonctionnel sans surcouche complexe
- **Twig uniquement** : Pas de SPA, rendu serveur
- **SEO basique** : Meta title, description, slugs propres

## 📦 Installation

### Avec Docker (Recommandé)

1. **Copier le fichier d'environnement Docker** (si .env n'existe pas) :
```bash
cp .env.docker .env
```

2. **Configuration complète avec Make** :
```bash
make setup
```

Ou manuellement :

2. **Démarrer les conteneurs** :
```bash
docker-compose -f docker-compose.dev.yml up -d --build
```

3. **Installer les dépendances** :
```bash
docker-compose -f docker-compose.dev.yml exec php composer install
```

4. **Créer la base de données et exécuter les migrations** :
```bash
docker-compose -f docker-compose.dev.yml exec php php bin/console doctrine:database:create
docker-compose -f docker-compose.dev.yml exec php php bin/console make:migration
docker-compose -f docker-compose.dev.yml exec php php bin/console doctrine:migrations:migrate
```

5. **Créer un utilisateur admin** :
```bash
docker-compose -f docker-compose.dev.yml exec php php bin/console app:create-user admin@example.com password123
```

6. **Accéder à l'application** :
- Front : http://localhost:8000
- Admin : http://localhost:8000/admin/page
- Login : http://localhost:8000/login

### Sans Docker

1. **Installer les dépendances** :
```bash
composer install
```

2. **Configurer la base de données** dans `.env` :
```env
DATABASE_URL="mysql://root:password@127.0.0.1:3306/marketing_cms?serverVersion=8.0&charset=utf8mb4"
```

3. **Créer la base de données et exécuter les migrations** :
```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

4. **Créer un utilisateur admin** :
```bash
php bin/console app:create-user admin@example.com password123
```

5. **Lancer le serveur** :
```bash
symfony server:start
# ou
php -S localhost:8000 -t public
```

## 🔐 Accès

- **Front** : http://localhost:8000
- **Admin** : http://localhost:8000/admin/page
- **Login** : http://localhost:8000/login

## 🐳 Commandes Docker

### Avec Make (Recommandé)

```bash
# Voir toutes les commandes disponibles
make help

# Configuration complète (build, up, install, migrate)
make setup

# Démarrer les conteneurs
make up

# Arrêter les conteneurs
make down

# Voir les logs
make logs

# Accéder au shell du conteneur PHP
make shell

# Installer les dépendances
make composer-install

# Exécuter les migrations
make migrate

# Créer un utilisateur admin
make create-user EMAIL=admin@example.com PASSWORD=password123
```

### Avec Docker Compose directement

```bash
# Démarrer les conteneurs
docker-compose -f docker-compose.dev.yml up -d

# Arrêter les conteneurs
docker-compose -f docker-compose.dev.yml down

# Voir les logs
docker-compose -f docker-compose.dev.yml logs -f

# Exécuter une commande Symfony
docker-compose -f docker-compose.dev.yml exec php php bin/console [commande]

# Accéder au shell du conteneur PHP
docker-compose -f docker-compose.dev.yml exec php bash

# Reconstruire les images
docker-compose -f docker-compose.dev.yml build --no-cache
```

## 📁 Structure

### Entities
- **User** : Authentification (email, password, roles)
- **Page** : Pages du site (title, slug, locale, template, SEO, status)
- **Block** : Blocs de contenu (type, payload JSON, position)
- **Media** : Fichiers média (path, alt)

### Controllers
- **SecurityController** : Login/logout
- **PageController** : Affichage front des pages publiées
- **Admin/PageController** : CRUD pages
- **Admin/BlockController** : CRUD blocs
- **Admin/MediaController** : CRUD media

### Templates
- `base.html.twig` : Layout de base
- `layout.html.twig` : Layout front avec SEO
- `admin/layout.html.twig` : Layout admin
- `page/show.html.twig` : Affichage page front
- `blocks/*.html.twig` : Templates de blocs

## 🧩 Système de blocs

Les blocs sont rendus via des templates Twig dans `templates/blocks/`. 
Le type de bloc détermine le template utilisé.

### Exemples de blocs inclus :
- **text** : `blocks/text.html.twig` - Titre + contenu
- **image** : `blocks/image.html.twig` - Image avec alt/caption
- **hero** : `blocks/hero.html.twig` - Section hero avec CTA
- **default** : `blocks/default.html.twig` - Fallback pour types non définis

### Créer un nouveau type de bloc :
1. Créer `templates/blocks/mon_type.html.twig`
2. Utiliser `block.payload` pour accéder aux données JSON
3. Le bloc sera automatiquement rendu via `render_block(block)`

### Exemple de payload JSON :
```json
{
  "title": "Mon titre",
  "content": "Mon contenu",
  "buttonText": "Cliquez ici",
  "buttonUrl": "/contact"
}
```

## 🎨 Personnalisation

- **Templates** : Modifier les templates dans `templates/`
- **Blocs** : Ajouter de nouveaux types dans `templates/blocks/`
- **Styles** : Ajouter vos CSS dans `public/` et les inclure dans les layouts

## ⚙️ Configuration

- **Security** : `config/packages/security.yaml`
- **Doctrine** : `config/packages/doctrine.yaml`
- **Twig** : `config/packages/twig.yaml`

## 🚀 Déploiement

1. Configurer `.env` pour la production
2. Générer les migrations : `php bin/console make:migration`
3. Exécuter les migrations : `php bin/console doctrine:migrations:migrate`
4. Vider le cache : `php bin/console cache:clear`
