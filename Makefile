dev-up:
	docker-compose up -d --build --remove-orphans
	docker-compose exec php bash -c 'cd /app;composer install'

dev-up-mac:
	mutagen compose -f docker-compose.yml -f docker-compose-mac.yml up --build -d --remove-orphans
	docker-compose exec php bash -c 'cd /app;composer install'

dev-down:
	docker-compose down --remove-orphans

dev-restart: dev-down dev-up
dev-restart-mac: dev-down dev-up-mac

shell:
	docker-compose exec php bash -c 'cd /app;bash'

.PHONY: tests
tests:
	vendor/bin/php-cs-fixer fix src -vvv --dry-run
	vendor/bin/php-cs-fixer fix tests -vvv --dry-run
	vendor/bin/phpstan analyze src --level=7
	vendor/bin/phpunit tests --testdox

.PHONY: tests
fix-code-standard:
	-vendor/bin/php-cs-fixer fix src -vvv
	-vendor/bin/php-cs-fixer fix tests -vvv

.PHONY: fixture
reload-fixture:
	-php bin/console do:da:drop --force
	php bin/console do:da:create
	php bin/console do:mi:mi -n
	php bin/console do:fi:lo -n
