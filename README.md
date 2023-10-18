![EasePHP Framework Logo](ease-core-social-preview.png?raw=true "Project Logo")

EasePHP Framework Core
======================

Object oriented PHP Framework for easy&fast writing small/middle sized apps.

[![Latest Version](https://img.shields.io/github/release/VitexSoftware/ease-core.svg?style=flat-square)](https://github.com/VitexSoftware/ease-core/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/VitexSoftware/ease-core/blob/master/LICENSE)
[![Code Coverage](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/VitexSoftware/ease-core/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Total Downloads](https://img.shields.io/packagist/dt/vitexsoftware/ease-core.svg?style=flat-square)](https://packagist.org/packages/vitexsoftware/ease-core)
[![Latest stable](https://img.shields.io/packagist/v/vitexsoftware/ease-core.svg?style=flat-square)](https://packagist.org/packages/vitexsoftware/ease-core)

![PHPUnit](https://github.com/VitexSoftware/php-ease-core/workflows/PHPUnit/badge.svg)
![Ubuntu Packaging](https://github.com/VitexSoftware/php-ease-core/workflows/Ubuntu%20Packaging/badge.svg)

[![Latest Stable Version](https://poser.pugx.org/vitexsoftware/ease-core/v/stable)](https://packagist.org/packages/vitexsoftware/ease-core)
[![Total Downloads](https://poser.pugx.org/vitexsoftware/ease-core/downloads)](https://packagist.org/packages/vitexsoftware/ease-core)
[![Latest Unstable Version](https://poser.pugx.org/vitexsoftware/ease-core/v/unstable)](https://packagist.org/packages/vitexsoftware/ease-core)
[![License](https://poser.pugx.org/vitexsoftware/ease-core/license)](https://packagist.org/packages/vitexsoftware/ease-core)
[![Monthly Downloads](https://poser.pugx.org/vitexsoftware/ease-core/d/monthly)](https://packagist.org/packages/vitexsoftware/ease-core)
[![Daily Downloads](https://poser.pugx.org/vitexsoftware/ease-core/d/daily)](https://packagist.org/packages/vitexsoftware/ease-core)


---

Installation
============

Download https://github.com/VitexSoftware/php-ease-core/archive/master.zip or:

Composer:
---------
    composer require vitexsoftware/ease-core

Linux
-----

For Debian, Ubuntu & friends please use repo:

```shell
sudo apt install lsb-release wget
echo "deb http://repo.vitexsoftware.cz $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/vitexsoftware.list
sudo wget -O /etc/apt/trusted.gpg.d/vitexsoftware.gpg http://repo.vitexsoftware.cz/keyring.gpg
sudo apt update
sudo apt install php-vitexsoftware-ease-core 
````

You can also install optional packages **php-vitexsoftware-ease-core-dev** and **php-vitexsoftware-ease-core-doc**

In this case please add this to your app composer.json:

``json
    "require": {
        "deb/ease-core": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "/usr/share/php/EaseCore",
            "options": {
                "symlink": true
            }
        }
    ]
``


Docker:
-------

To get Docker image:

    docker pull vitexsoftware/ease-core


Framework Constants
===================

  * EASE_APPNAME  - common name of application. Mainly used in logs. (APP_NAME is also recoginsed)
  * EASE_LOGGER   - one of memory,console,file,syslog,email,std,eventlog or combination eg. "console|syslog"
  * EASE_EMAILTO  - recipient email address for Ease/Logger/ToMail
  * EASE_SMTP     - Custom [SMTP Settings](https://pear.php.net/manual/en/package.mail.mail.factory.php) (JSON Encoded) 
  * EASE_FROM     - Sent mail sender address
  * LOG_DIRECTORY - destination for ToFile logger
  * LOG_OPTION    - syslog option argument
  * LOG_FACILITY  - syslog facility argument



Logging
-------

 You can use any combination of this logging modules:

   * memory     - log to array in memory
   * console    - log to ansi sequence capable console
   * file       - log to specified file
   * syslog     - log to linux syslog service
   * email      - send all messages to constant('EASE_EMAILTO') at end
   * std        - write messages to stdout/stderr
   * eventlog   - log to Windows eventlog 

  ```php
    define('EASE_LOGGER', 'console|syslog');
    $logger = new \Ease\Sand();
    $logger->addStatusMessage('Error Message', 'error');
  ```


Testing
-------

At first you need initialise create sql user & database with login and password 
from testing/phinx.yml and initialise testing database by **phinx migrate** 
command:

```
make phpunit
```

Building
--------

Simply run **make deb**

Links
=====

Homepage: https://www.vitexsoftware.cz/ease.php

GitHub: https://github.com/VitexSoftware/ease-core

phpDocumentor: http://vitexsoftware.cz/php-ease-core/

