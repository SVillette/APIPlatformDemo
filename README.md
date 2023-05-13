# API Platform Demo

A simple Symfony application with API Platform

## Introduction

This application is only about posts and authors.

- An admin panel is available at `/admin`. You can list, create, update and delete posts.
- The API is available at `/api` and contains only two entry points (list and create).

## Installation


- Create a file `.env.local` with your database credentials or uncomment and replace it in `.env`.

```dotenv
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
```

- Install composer dependencies.

```shell
composer install
```

- Install Yarn dependencies and build assets.

```shell
yarn install && yarn dev
```

- Create database.

```shell
php bin/console doctrine:database:create
```

- Execute migrations.

```shell
php bin/console doctrine:migrations:migrate --no-interaction
```

- Load data fixtures.

```shell
php bin/console doctrine:fixtures:load --no-interaction
```

- Run the Symfony server.

```shell
symfony serve
```

You're good to go now !

- https://127.0.0.1:8000/admin
- https://127.0.0.1:8000/api

If you loaded the data fixtures, you can connect to the admin panel with "john.doe@example.com" / "plain-password"

## Files architecture
This application does not follow Symfony basic architecture in the `src/` folder:
- `src/Api` defines all API Platform custom services (state providers and processors, pagination etc.).
- `src/Application` defines generic services used in the application (form types, validation, resource handlers etc.).
- `src/Domain` contains entities, repositories and generic DTOs.
- `src/UI` defines all entry points: CLI commands, controllers and API resources.

Also, data fixtures are in `tests/` folder and are available only in `dev` and `test` environments.

## Data specification
There are only two entities:
- Admin users contains an email, a password, and timestamps (created and updated at).
- Posts contains an ULID, a title, a content, a published date and timestamps. Posts are linked to an author (Admin users).
