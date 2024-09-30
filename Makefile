# vim: set tabstop=8 softtabstop=8 noexpandtab:
.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: cs
cs: vendor ## Normalizes composer.json with ergebnis/composer-normalize and fixes code style issues with friendsofphp/php-cs-fixer
	mkdir -p .build/php-cs-fixer
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --diff --verbose

.PHONY: static-code-analysis
static-code-analysis: check-symfony vendor ## Runs a static code analysis with phpstan/phpstan
	vendor/bin/phpstan analyse --configuration=phpstan-default.neon.dist --memory-limit=-1

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: check-symfony vendor ## Generates a baseline for static code analysis with phpstan/phpstan
	venndor/bin/phpstan analyze --configuration=phpstan-default.neon.dist --generate-baseline=phpstan-default-baseline.neon --memory-limit=-1

.PHONY: tests
tests: vendor
	vendor/bin/phpunit tests

.PHONY: vendor
vendor: composer.json ## Installs composer dependencies
	composer install

clean: ## remove unneeded files
	rm -rf vendor composer.lock
	rm -rf debian/php-vitexsoftware-ease-core
	rm -rf debian/php-vitexsoftware-ease-core-doc
	rm -rf debian/*.log debian/tmp
	rm -rf docs/*

phpdoc: clean
	mkdir -p docs
	phpdoc --defaultpackagename=MainPackage
	mv .phpdoc/build/* docs

apigen: ## Build Apigen documentation
	rm -rfv docs ; mkdir docs
	VERSION=`cat debian/composer.json | grep version | awk -F'"' '{print $4}'`; \
	apigen generate --destination=docs -- src/
#	apigen generate --destination=docs --title "Ease PHP Framework Core ${VERSION}" --charset UTF-8 --access-levels public --access-levels protected --php --tree -- src/


composer: ## Update PHP dependencies
	composer update

phpunit: ## Testing by PHPUnit
	mkdir -p debian/tmp/
	vendor/bin/phpunit --bootstrap tests/Bootstrap.php --configuration phpunit.xml

deb: ## Build Debian package
	dch -i "`git log -1 --pretty=%B`"
	debuild -i -us -uc -b

rpm: ## Build RedHat package
	rpmdev-bumpspec --comment="`git log -1 --pretty=%B`" --userstring="Vítězslav Dvořák <info@vitexsoftware.cz>" ease-core.spec
	rpmbuild -ba ease-core.spec

docker: dimage

dimage: ## Build docker image
	docker build -t vitexsoftware/php-ease-core .


release:
	echo Release v$(nextversion)
	dch -v $(nextversion) `git log -1 --pretty=%B | head -n 1`
	debuild -i -us -uc -b
	git commit -a -m "Release v$(nextversion)"
	git tag -a $(nextversion) -m "version $(nextversion)"

phpstan:
	phpstan analyse --error-format=checkstyle --level=4 src


