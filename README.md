![EasePHP Framework Logo](https://raw.githubusercontent.com/VitexSoftware/ease-core/master/project-logo.png "Project Logo")

EasePHP Framework Core
======================

Object oriented PHP Framework for easy&fast writing small/middle sized apps.

[![Source Code](http://img.shields.io/badge/source-VitexSoftware/ease-core-blue.svg?style=flat-square)](https://github.com/VitexSoftware/ease-core)
[![Latest Version](https://img.shields.io/github/release/VitexSoftware/ease-core.svg?style=flat-square)](https://github.com/VitexSoftware/ease-core/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/VitexSoftware/ease-core/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/VitexSoftware/ease-core/master.svg?style=flat-square)](https://travis-ci.org/VitexSoftware/ease-core)
[![Total Downloads](https://img.shields.io/packagist/dt/vitexsoftware/ease-framework.svg?style=flat-square)](https://packagist.org/packages/vitexsoftware/ease-framework)
[![Docker pulls](https://img.shields.io/docker/pulls/vitexsoftware/ease-framework.svg)](https://hub.docker.com/r/vitexsoftware/ease-framework/)
[![Latest stable](https://img.shields.io/packagist/v/vitexsoftware/ease-framework.svg?style=flat-square)](https://packagist.org/packages/vitexsoftware/ease-framework)

---

Installation
============

Download https://github.com/VitexSoftware/ease-core/archive/master.zip or:

Composer:
---------
    composer require vitexsoftware/ease-core

Linux
-----

For Debian, Ubuntu & friends please use repo:

``
    wget -O - http://v.s.cz/info@vitexsoftware.cz.gpg.key|sudo apt-key add -
    echo deb http://v.s.cz/ stable main | sudo tee /etc/apt/sources.list.d/vitexsoftware.list 
    sudo apt update
    sudo apt install ease-core
``

In this case please add this to your app composer.json:

``json
    "require": {
        "ease-core": "*"
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

  * EASE_APPNAME - common name of application. Mainly used in logs.
  * EASE_LOGGER  - one of memory,console,file,syslog,email,std,eventlog or combination "console|syslog"
  * EASE_EMAILTO - recipient for Ease/Logger/ToMail
  * EASE_SMTP    - Custom SMTP Settings (JSON Encoded) 

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

Apigen Docs: https://www.vitexsoftware.cz/ease-core/
