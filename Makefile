phpstan_analyze:
	@echo "Executing phpstan..."
	@symfony php vendor/bin/phpstan -n analyze

phpcs_analyze:
	@echo "Executing phpcs..."
	@symfony php vendor/bin/phpcs --standard=phpcs.xml.dist

full_static_analyze: phpstan_analyze phpcs_analyze
