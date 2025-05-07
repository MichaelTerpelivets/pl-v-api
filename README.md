# PlanetVPN Product API

## Requirements

- Docker + Docker Compose
- PHP 8.2+
- Composer

## Setup

```bash
    git clone ...
    cd planetvpn-api

    # Build and start
    docker-compose up -d --build
    
    # Run migrations
    docker-compose exec app php artisan migrate
    
    # Run tests
    docker-compose exec app php artisan test
