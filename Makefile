install:
	composer install
	cp .env.example .env
	php artisan key:gen --ansi
	php artisan migrate

start:
	php artisan serve --host 0.0.0.0

migrate:
	php artisan migrate

console:
	php artisan tinker

test:
	php artisan test
