test:
	composer prepare-test
	vendor/bin/phpunit

code:
	vendor/bin/phpcbf
	vendor/bin/phpcs
	vendor/bin/phpcbf
	vendor/bin/phpcs
	vendor/bin/phpinsights --no-interaction --min-quality=80 --min-complexity=80 --min-architecture=80 --min-style=80

style:
	vendor/bin/phpcs