# Symfony DDD Skeleton

## Description

This is a complete stack for running Symfony 6.2 into Docker containers using docker-compose tool.

It is composed by 2 containers:

- `nginx`, acting as the webserver.
- `php`, the PHP-FPM container with the 8.2 version of PHP.

## Installation

1. 😀 Clone this rep.

2. Create the file `./.docker/.env.nginx.local` using `./.docker/.env.nginx` as template. The value of the variable `NGINX_BACKEND_DOMAIN` is the `server_name` used in NGINX.

3. Go inside folder `./docker` and run `docker-compose up -d` to start containers.

4. Inside the `php` container, run `composer install` to install dependencies from `/var/www/symfony` folder.

## Run Scritps

🧹 Keep a modern codebase with **PHP Coding Standards Fixer**:
```bash
composer lint
```

✅ Run refactors using **Rector**
```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:
```bash
composer test:types
```

✅ Run unit tests using **PHPUnit**
```bash
composer test:unit
```

🚀 Run the entire test suite:
```bash
composer test
```
