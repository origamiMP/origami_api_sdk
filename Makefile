tinker:
	./vendor/bin/psysh --config psysh.php

phpstan:
	./vendor/bin/phpstan analyse --memory-limit 500M

lint:
	./vendor/bin/pint -v src

lint-test:
	./vendor/bin/pint -v --test src
