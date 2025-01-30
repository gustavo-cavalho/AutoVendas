# to access the PHP container
open-php:
	docker exec -ti php_autoVendas bash

# to run the migrations without "entering" the container
exec-migrations:
	docker exec php_autoVendas symfony console doctrine:migrations:migrate

# to install dependencies without "entering" the container
composer-install:
	docker exec php_autoVendas composer install