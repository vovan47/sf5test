## Description

This is a test task completed with Symfony 5.1

Features:
- Category and Product entities
- CRUD for Product entity with validation
- Console command for data import from JSON
- Subscriber that sends Product updates to an email from parameters

## Local installation:

Clone this repo, go to app directory

`$ git clone https://github.com/vovan47/sf5test.git && cd sf5test`

Create `.env` file from `.env.dist`:

`$ cp .env.dist .env`

Change `.env` file according to your needs (database, mailer settings).
Use `ADMIN_EMAIL` parameter to define email for Product updates.

Install dependencies:

`$ composer install`

Create database (if you didn't create it manually):

`$ php bin/console doctrine:database:create`

Apply migrations:

`$ php bin/console doctrine:migrations:migrate`

(Optional) Add fixtures:

`$ php bin/console doctrine:fixtures:load --append`

Run unit tests:

`$ ./vendor/bin/phpunit`

### Data import commands:

Product:

`$ php bin/console app:import-data -t product -f /path/to/app/tests/fixtures/products.json`

Category:

`$ php bin/console app:import-data -t category -f /path/to/app/tests/fixtures/categories.json`

Those are sample files, you can use other JSON files or pipe JSON to STDIN.
