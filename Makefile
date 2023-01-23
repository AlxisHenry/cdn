.PHONY: lint tests cron

lint: $(LINT) # Run linters
	@vendor/bin/phpstan analyse --level=9 -c phpstan.neon --xdebug

tests: $(TESTS) # Run tests
	@vendor/bin/phpunit --colors --testdox tests/

cron: $(CRON) # Setup cron
	@sudo bash ./utils/cron.sh