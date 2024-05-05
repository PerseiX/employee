.PHONY: install
build:
	bin/console doctrine:database:create --no-interaction
	bin/console doctrine:migrations:migrate --no-interaction

	bin/console doctrine:database:create --env=test  --no-interaction
	bin/console doctrine:schema:update --force --env=test --no-interaction

