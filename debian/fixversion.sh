#!/bin/bash
VERSTR=`dpkg-parsechangelog --show-field Version`
COMPOSER_VERSTR=`echo ${VERSTR}|sed 's/-/./g'`
echo Update debian/php-vitexsoftware-ease-core/usr/share/php/EaseCore/Atom.php version to ${VERSTR}
sed -i -e "/static public \$frameworkVersion/c\    static public \$frameworkVersion = '${VERSTR}';" debian/php-vitexsoftware-ease-core/usr/share/php/EaseCore/Atom.php
echo Update src/Ease/Atom.php version to ${VERSTR}
sed -i -e "/static public \$frameworkVersion/c\    static public \$frameworkVersion = '${VERSTR}';" src/Ease/Atom.php
