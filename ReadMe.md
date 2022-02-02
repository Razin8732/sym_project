php bin/console doctrine:database:create
php bin/console doctrine:schema:update 
php bin/console doctrine:migration:migrate


php bin/console doctrine:migrations:diff
php bin/console doctrine:migration:migrate

----- php bin/console make:migration

composer require symfony/orm-pack
composer require --dev symfony/maker-bundle

composer require --dev orm-fixtures


php bin/console debug:router
php bin/console router:match /lucky/number/8


php bin/console cache:clear
php bin/console cache:pool:clear
php bin/console cache:warmup
php bin/console doctrine:cache:clear-collection-region
php bin/console doctrine:cache:clear-entity-region
php bin/console doctrine:cache:clear-metadata
php bin/console doctrine:cache:clear-query
php bin/console doctrine:cache:clear-query-region
php bin/console doctrine:cache:clear-result

symfony server:start -d
symfony server:log

symfony server:stop

symfony local:php:list



# For Enum fied
need to set 
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'
        mapping_types:
            enum: string

in: config/packages/doctrine.yaml



php bin/console make:controller CategoryController