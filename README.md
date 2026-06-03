# TravelDock - Application de gestion de voyages

Projet Symfony 7 avec Docker : application de gestion de voyages, destinations et réservations.

## Architecture

```
symfony-docker/
├── docker/
│   ├── php/
│   │   └── Dockerfile          # PHP 8.3 + extensions Symfony
│   ├── nginx/
│   │   └── default.conf        # Configuration Nginx
│   └── mysql/
│       └── init.sql            # Initialisation de la base de données
├── symfony/
│   ├── src/
│   │   ├── Controller/         # Controllers HTTP
│   │   ├── Entity/             # Entités Doctrine (Travel, Destination, Booking)
│   │   └── Repository/         # Repositories personnalisés
│   ├── templates/              # Templates Twig
│   ├── migrations/             # Migrations Doctrine
│   ├── config/                 # Configuration Symfony
│   └── public/                 # Point d'entrée web
├── docker-compose.yml          # Orchestration Docker
├── Makefile                    # Commandes utilitaires
└── README.md
```

## Démarrage rapide

### Prérequis
- Docker Desktop installé et démarré
- Make (optionnel, sinon utiliser les commandes directement)

### 1. Démarrer les containers
```bash
docker compose up -d --build
```

### 2. Installer les dépendances Symfony
```bash
docker compose exec php composer install
```

### 3. Exécuter les migrations
```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### 4. Accéder à l'application
- **Application** : http://localhost:8080
- **phpMyAdmin** : http://localhost:8081

## Commandes utiles

| Commande | Description |
|----------|-------------|
| `docker compose exec php bash` | Shell dans le container PHP |
| `docker compose exec php php bin/console doctrine:migrations:migrate` | Exécuter les migrations |
| `docker compose exec php php bin/console debug:router` | Voir toutes les routes |
| `docker compose exec php php bin/console cache:clear` | Vider le cache |
| `docker compose logs -f` | Voir les logs |

## Structure de la base de données

### Tables
- **destination** : Pays et villes de destination
- **travel** : Voyages proposés (avec prix, dates, participants)
- **booking** : Réservations des voyageurs

### Relations
- Un `Travel` appartient à une `Destination` (ManyToOne)
- Un `Travel` peut avoir plusieurs `Booking` (OneToMany)

## Ports

| Service | Port local |
|---------|-----------|
| Application Nginx | 8080 |
| phpMyAdmin | 8081 |
| MySQL | 3306 |

## Accès base de données

- **Host** : `mysql` (depuis les containers) / `localhost` (depuis l'hôte)
- **Database** : `travelDock`
- **User** : `symfony` / **Password** : `symfony_password`
- **Root password** : `rootpassword`
