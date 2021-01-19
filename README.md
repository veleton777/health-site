## Installation

- `composer install`
- `cp .env .env.example;`
- Edit your _.env_
- `php artisan jwt:secret`
- `php artisan storage:link`

## Migrations and seeding

- Create DB;
- `php artisan migrate`
- `php artisan db:seed`

Info about all commands `php artisan list`

##### How run backend local?

- `php -S localhost:8000 -t public`

## Docs

- [API docs](./storage/app/public/openapi.yaml)
