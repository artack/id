SHELL = /bin/bash

uid = $$(id -u)
gid = $$(id -g)
pwd = $$(pwd)

## Docker
.PHONY: build
build:
	docker compose build

#.PHONY: up
#up: .up
#
#.up:
#	docker compose up -d
#
#.PHONY: down
#down: .down
#
#.down:
#	docker compose down
#
#.PHONY: update
#update: build up
#
#.PHONY: reset
#reset: .reset
#
#.PHONY: .reset
#.reset: .down .install .up

.PHONY: php-8.3-cli
php83-cli:
	docker compose run --rm php83 sh

.PHONY: php-8.4-cli
php84-cli:
	docker compose run --rm php84 sh

validation-phpstan:
	docker compose run --rm php84 ./tools/phpstan/vendor/bin/phpstan analyse -c phpstan.neon --ansi

validation-phpcs:
	docker compose run --rm php83 ./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -vvv --diff --dry-run --allow-risky=yes --ansi

validation-phpcs-fix:
	docker compose run --rm php83 ./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -vvv --allow-risky=yes --ansi

validation: validation-phpcs validation-phpstan

verify: validation test

composer-install-php83-lowest:
	docker compose run --rm php83 composer update --prefer-lowest --prefer-dist --no-interaction --no-progress

composer-install-php83-stable:
	docker compose run --rm php83 composer update --prefer-stable --prefer-dist --no-interaction --no-progress

composer-install-php84-lowest:
	docker compose run --rm php84 composer update --prefer-lowest --prefer-dist --no-interaction --no-progress

composer-install-php84-stable:
	docker compose run --rm php84 composer update --prefer-stable --prefer-dist --no-interaction --no-progress

test-phpunit-php83:
	docker compose run --rm php83 ./vendor/bin/phpunit --configuration ./phpunit.xml

test-phpunit-php84:
	docker compose run --rm php84 ./vendor/bin/phpunit --configuration ./phpunit.xml

test-php84-stable: composer-install-php84-stable test-phpunit-php84
test-php83-stable: composer-install-php83-stable test-phpunit-php83
test-php84-lowest: composer-install-php84-lowest test-phpunit-php84
test-php83-lowest: composer-install-php83-lowest test-phpunit-php83
test: test-php84-stable test-php83-stable test-php84-lowest test-php83-lowest
