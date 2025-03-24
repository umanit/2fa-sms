phpstan_analyze:
	@echo "Executing phpstan..."
	@symfony php vendor/bin/phpstan -n analyze

phpcs_analyze:
	@echo "Executing phpcs..."
	@symfony php vendor/bin/phpcs --standard=phpcs.xml.dist

phpunit_analyze:
	@echo "Executing PHPUnit..."
	@symfony php vendor/bin/phpunit

full_static_analyze: phpstan_analyze phpcs_analyze phpunit_analyze
