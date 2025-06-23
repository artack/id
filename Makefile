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

test-phpstan:
	docker compose run --rm php84 ./tools/phpstan/vendor/bin/phpstan analyse -c phpstan.neon --ansi

test-phpcs:
	docker compose run --rm php83 ./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -vvv --diff --dry-run --allow-risky=yes --ansi

test-phpcs-fix:
	docker compose run --rm php83 ./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -vvv --allow-risky=yes --ansi
