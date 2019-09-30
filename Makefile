.PHONY: help
.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

cs-checks: cs-check-phpcs cs-check-phpcsfixer ## Checks the php coding style

cs-check-phpcs: ## Runs PHPCS checks
	vendor/bin/phpcs --standard=./phpcs.xml.dist --report-full

cs-check-phpcsfixer: ## Runs php-cs-fixer checks
	vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix: ## Fixes the coding style
	vendor/bin/phpcbf --standard=./phpcs.xml.dist || true
	vendor/bin/php-cs-fixer fix --allow-risky=yes

static-analysis: ## Runs a php static analyzer
	vendor/bin/phpstan analyse --configuration=phpstan.neon --level=max src

tests-unit:
	vendor/bin/phpunit

bash: ## Starts a bash session in the php container
	docker-compose exec php /bin/sh
