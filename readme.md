Books db
=================

How to run
------------
cd .docker  
make docker-up  
make docker-down  

pokud chcu něco spustit v dockeru,tak třeba: docker exec books-db php -v

spuštení phpstan v dockeru:  
docker exec books-db vendor/bin/phpstan analyse app

