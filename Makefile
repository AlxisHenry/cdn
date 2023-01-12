.PHONY: lint
lint: $(LINT) # Run linters
	@vendor/bin/phpstan analyse --level=9 -c phpstan.neon --xdebug

.PHONY: tests
tests: $(TESTS) # Run tests
	@vendor/bin/phpunit --colors --testdox tests/