PORT ?= 6907

install:
	composer install

dump:
	composer dump-autoload

start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

lint:
	composer exec --verbose phpcs -- --standard=PSR12 public

localstart:
	php -S localhost:8080 -t public public/index.php