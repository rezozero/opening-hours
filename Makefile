test:
	php vendor/bin/phpcs --report=full --report-file=./report.txt
	php vendor/bin/phpstan analyse -c phpstan.neon -l max src
	php vendor/bin/atoum -d tests