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


ALTER USER phpmyadmin IDENTIFIED WITH mysql_native_password BY '123@admin';

ALTER USER 'phpmyadmin'@'%' IDENTIFIED BY '1243@Admin';


Extension Pack for symfony:
https://marketplace.visualstudio.com/items?itemName=duboiss.sf-pack



http://localhost/phpmyadmin/sql.php?db=demo_sym_project&table=category&pos=0&sql_signature=3cd2bae9a54e39bcc99008675499b9723824e58c8321a6d55297da44e3ad1397&sql_query=SELECT+%2A+FROM+%60demo_sym_project%60.%60category%60+WHERE+%60id%60+%3D+2


composer require symfony/security-csrf


To fix manifest.json file not found: (while using asset function intwig to show image)
yarn add --dev @symfony/webpack-encore
yarn add webpack-notifier --dev
yarn encore dev



for duplicate record in many to many relationship
https://stackoverflow.com/a/54886260


