COVERAGE = ./coverage
test:
	./vendor/bin/phpunit

coverage: clean_coverage
	./vendor/bin/phpunit --coverage-html $(COVERAGE)

clean_coverage:
	rm -rf $(COVERAGE)