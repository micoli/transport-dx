check-in-docker:
	test -f /var/lib/in.docker

check-in-host:
	! test -f /var/lib/in.docker

dev-up: check-in-host
	docker-compose up -d --build --remove-orphans
	docker-compose exec php bash -c 'cd /app;composer install'

dev-up-mac: check-in-host
	mutagen compose -f docker-compose.yml -f docker-compose-mac.yml up --build -d --remove-orphans
	docker-compose exec php bash -c 'cd /app;composer install'

dev-down: check-in-host
	docker-compose down --remove-orphans

dev-restart: check-in-host dev-down dev-up
dev-restart-mac: check-in-host dev-down dev-up-mac

shell: check-in-host
	docker-compose exec php bash -c 'cd /app;bash'

.PHONY: tests
tests: check-in-docker
	vendor/bin/php-cs-fixer fix src -vvv --dry-run
	vendor/bin/php-cs-fixer fix tests -vvv --dry-run
	vendor/bin/phpstan analyze src --level=7
	vendor/bin/phpunit tests --testdox

.PHONY: tests
fix-code-standard: check-in-docker
	-vendor/bin/php-cs-fixer fix src -vvv
	-vendor/bin/php-cs-fixer fix tests -vvv

.PHONY: fixture
reload-fixture: check-in-docker
	-php bin/console do:da:drop --force
	php bin/console do:da:create
	php bin/console do:mi:mi -n
	php bin/console do:fi:lo -n

.PHONY: watch-assets
watch-assets: check-in-docker
	-killall node
	yarn run watch
