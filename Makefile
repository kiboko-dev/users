down:
	docker-compose down
up:
	if ! [ -f ./app/.env ];then cp ./app/.env.example ../app/env;fi
	docker-compose build
	docker-compose up -d
	docker-compose run --rm php composer install
	docker-compose run --rm php php artisan key:generate
	sleep 5
	docker-compose run --rm php php artisan migrate
	#docker-compose run --rm php php artisan db:seed
	#docker-compose run --rm php php artisan scribe:generate