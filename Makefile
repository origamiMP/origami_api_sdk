tinker:
	./vendor/bin/psysh --config psysh.php

phpstan:
	./vendor/bin/phpstan analyse --memory-limit 500M

lint:
	./vendor/bin/pint -v src tests

lint-test:
	./vendor/bin/pint -v --test src tests

test:
	./vendor/bin/phpunit
