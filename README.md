# Symfony quiz form

Task for better understanding of Symfony framework forms.
3 forms nested into one for a quiz CRUD.

## Run

 - composer install
 - cp .env.dist .env
 - change DATABASE_URL variable in .env file to create your database
 - bin/console doctrine:database:create
 - bin/console server:run