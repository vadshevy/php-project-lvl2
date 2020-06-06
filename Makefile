install:
	composer install
lint:
	composer exec phpcs -- --standard=PSR12 --exclude=Generic.Files.LineLength src bin tests -v
lint-fix:
	composer exec phpcbf -- --standard=PSR12 src bin tests -v
test:
	composer run-script test
test-coverage:
	composer run-script test -- --coverage-clover build/logs/clover.xml