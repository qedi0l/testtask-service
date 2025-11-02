restart:
	docker-compose down && docker-compose up -d --force-recreate
buildup:
	docker-compose up -d --build
up:
	docker-compose up -d
down:
	docker-compose down
migrate:
	docker-compose exec app php artisan migrate
rollback:
	docker-compose exec app php artisan migrate:rollback --step=1
tinker:
	docker-compose exec app php artisan tinker
bash:
	docker-compose exec app bash
seed:
	docker-compose exec app php artisan db:seed
swagger:
	docker-compose exec app php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider" && docker-compose exec app php artisan l5-swagger:generate
