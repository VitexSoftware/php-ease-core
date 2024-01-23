repoversion=$(shell LANG=C aptitude show php-vitexsoftware-ease-core | grep Version: | awk '{print $$2}')
nextversion=$(shell echo $(repoversion) | perl -ne 'chomp; print join(".", splice(@{[split/\./,$$_]}, 0, -1), map {++$$_} pop @{[split/\./,$$_]}), "\n";')

#DESTDIR ?= debian/php-ease-core/DEBIAN
#libdir  ?= /usr/share/php/Ease
#docdir  ?= /doc/ease-core/html

all: build install

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[0-9a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

fresh: ## Update source code
	git pull origin master
	PACKAGE=`cat debian/composer.json | grep '"name"' | head -n 1 |  awk -F'"' '{print $4}'`; \
	VERSION=`cat debian/composer.json | grep version | awk -F'"' '{print $4}'`; \
	dch -b -v "${VERSION}" --package ${PACKAGE} "$CHANGES" \
	composer install
	
#install:
#	mkdir -p $(DESTDIR)$(libdir)
#	cp -r src/Ease/ $(DESTDIR)$(libdir)
#	cp -r debian/composer.json $(DESTDIR)$(libdir)
#	mkdir -p $(DESTDIR)$(docdir)
#	cp -r docs $(DESTDIR)$(docdir)

#build: doc
#	echo build;	

doc:	phpdoc

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


openbuild:
	

.PHONY : install build
	
