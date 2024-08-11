lint:
	composer exec --verbose phpcs -- --standard=PSR12 bin src tests

test:
	composer exec --verbose phpunit tests -- --coverage-text

install:
	composer install

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
