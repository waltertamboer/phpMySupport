# phpMySupport

This repository contains an application to setup a support system.

## Installation

Start the application:
```
docker-compose up -d
```

Initialize the database:
```
docker-compose exec --user 1000:1000 php-fpm bin/support migrations:migrate --no-interaction
```

Create the admin user:
```
docker-compose exec --user 1000:1000 php-fpm bin/support user:create
```

## Updating

Pull the latest docker image:
```
docker-compose pull
```

Run the migrations
```
docker-compose exec --user 1000:1000 php-fpm bin/support migrations:migrate --no-interaction
```
