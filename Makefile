install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 --exclude=Generic.Files.LineLength src bin tests
test:
	composer run-script phpunit tests