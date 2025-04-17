tinker:
	./vendor/bin/psysh

phpstan:
	./vendor/bin/phpstan analyse --memory-limit 500M

lint:
	./vendor/bin/pint -v

lint-test:
	./vendor/bin/pint -v --test
