.PHONY: install
install:
	composer install

	bin/console doctrine:database:create --no-interaction --if-not-exists
	bin/console doctrine:migrations:migrate --no-interaction

	bin/console doctrine:database:create --env=test  --no-interaction --if-not-exists
	bin/console doctrine:schema:update --force --env=test --no-interaction

