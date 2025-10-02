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

Overview
--------

EasePHP Framework Core is a small, dependency-light runtime library for building CLI and web applications in PHP. It provides:
- a set of base classes for your domain objects (Atom ➝ Molecule ➝ Sand ➝ Brick),
- a flexible, multi-sink logging pipeline (memory, console, file, syslog, std, eventlog),
- a simple but powerful configuration layer (constants/ENV/.env/.json) via Ease\\Shared,
- gettext-based internationalization helpers (Ease\\Locale),
- user abstractions (Ease\\Anonym, Ease\\User), and
- pragmatic utilities (Ease\\Functions) and a Mailer built on PEAR Mail/Mail_mime.

Works standalone or as the core of the broader EasePHP ecosystem. Autoloading follows PSR-4:
- "Ease\\" ➝ src/Ease
- "Ease\\Logger\\" ➝ src/Ease/Logger

Key features
------------
- Base object model
  - Atom: minimal base with object naming and draw().
  - Molecule: property setup helpers from options/ENV/constants.
  - Sand: data holder with typed helpers; integrates logging via trait.
  - Brick: adds record identity (id/name/array/reuse) through recordkey trait.
- Logging
  - Regent aggregator dispatches to memory/console/file/syslog/std/eventlog; configure via EASE_LOGGER (pipe-separated).
  - Console logger features internationalized date formatting with graceful fallback for maximum reliability.
  - Comprehensive error handling ensures logging never crashes your application.
- Internationalization (i18n)
  - Gettext domain binding, locale selection (request/session/browser/ENV), and helper APIs.
- Configuration
  - Shared::cfg reads constants then ENV; loadConfig supports .json and .env.
- Users and identity
  - Anonymous and User implementations with login/password helpers and Gravatar.
- Utilities
  - URL helpers, transliteration, AES-256-CBC encrypt/decrypt, randoms, human-readable sizes, UUIDv4, JSON/serialization checks, namespace class loader, etc.

Requirements
------------
- PHP >= 7.0 (tested up to PHP 8.4)
- ext-intl (optional but recommended for internationalized date formatting)
- PEAR packages: pear/mail, pear/mail_mime (Mailer)

**Note:** The framework gracefully handles missing or misconfigured internationalization extensions.

Quick start
-----------
```php
<?php
require __DIR__.'/vendor/autoload.php';

// Minimal config
define('EASE_APPNAME', 'MyApp');
// Send logs to console and syslog (combine with "|")
define('EASE_LOGGER', 'console|syslog');

$logger = new \Ease\Sand();
$logger->addStatusMessage('MyApp started', 'info');

// i18n (optional): bind domain in ./i18n or /usr/share/locale
new \Ease\Locale('en_US', './i18n', 'php-vitexsoftware-ease-core');
$logger->addStatusMessage(_('Ready to work'), 'success');

// Mail (optional): configure sender via constants or ENV
// define('EASE_FROM', 'no-reply@example.com');
// define('EASE_SMTP', json_encode([
//     'host' => 'smtp.example.com', 'auth' => true, 'username' => '...','password' => '...'
// ]));
// $mailer = new \Ease\Mailer('user@example.com', 'Hello', 'Message body');
// $mailer->send();
```

Configuration
-------------
Common ways to configure EaseCore:

- PHP constants (highest precedence)

  ```php
  <?php
  define('EASE_APPNAME', 'MyApp');
  define('EASE_LOGGER', 'console|syslog');
  define('EASE_FROM', 'no-reply@example.com');
  define('EASE_SMTP', json_encode([
      'host' => 'smtp.example.com',
      'auth' => true,
      'username' => 'smtp-user',
      'password' => 'secret',
  ]));
  ```

- Environment variables

  ```bash
  export EASE_APPNAME=MyApp
  export EASE_LOGGER=console|syslog
  export EASE_FROM=no-reply@example.com
  export EASE_SMTP='{"host":"smtp.example.com","auth":true,"username":"smtp-user","password":"secret"}'
  ```

- .env or JSON file

  ```php
  <?php
  // Load .env and define UPPERCASE constants from it:
  \Ease\Shared::singleton()->loadConfig(__DIR__.'/.env', true);
  // Or load JSON without defining constants (values accessible via Shared::cfg()):
  \Ease\Shared::singleton()->loadConfig(__DIR__.'/config.json', false);
  ```

Frequently used keys: EASE_APPNAME, EASE_LOGGER, EASE_FROM, EASE_SMTP, LOG_DIRECTORY, LOG_FLAG, LOG_FACILITY.

Installation
============


Composer:
---------
    composer require vitexsoftware/ease-core


Docker:
-------

This repository includes a minimal Docker build primarily for packaging/distribution (it places the library under /usr/share/php/Ease*). For application development, prefer installing via Composer.

- Build image locally:

    make dimage

- Note: The image is not intended as a full runtime base; it contains the library files for packaging purposes.


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

  - memory     - log to array in memory
  - console    - log to ansi sequence capable console with internationalized timestamps
  - file       - log to specified file
  - syslog     - log to linux syslog service
  - email      - send all messages to constant('EASE_EMAILTO') at end
  - std        - write messages to stdout/stderr
  - eventlog   - log to Windows eventlog

**Reliability Features:**
- Console logger automatically falls back to standard PHP date formatting if IntlDateFormatter fails
- Comprehensive error handling prevents logging failures from crashing your application
- All loggers are extensively tested with edge cases and error scenarios 

  ```php
    define('EASE_LOGGER', 'console|syslog');
    $logger = new \Ease\Sand();
    $logger->addStatusMessage('Error Message', 'error');
  ```


Testing
-------

Run the PHPUnit test suite locally:

```
composer install
make phpunit
```

When installed from the Debian dev package, tests (including i18n assets) can be executed with:

```
phpunit --bootstrap /usr/share/php/EaseCore/Test/Bootstrap.php \
  --configuration /usr/share/php/EaseCore/Test/phpunit.xml
```

Building
--------

Simply run **make deb**

Recent Updates
==============

### Version 1.49.1 (October 2025)

**Logger Reliability Improvements:**
- **Fixed IntlDateFormatter Fatal Error**: Resolved `"Found unconstructed IntlDateFormatter"` crashes in console logger
- **Graceful Fallback**: Console logger now automatically falls back to standard PHP date formatting when internationalization fails
- **Enhanced Error Handling**: Added comprehensive exception handling for `ValueError` and `Error` cases
- **Improved Type Safety**: Full PHPStan level 8 compliance with proper type annotations
- **Extended Test Coverage**: Added tests for edge cases including invalid locales, null values, and error scenarios

**Documentation Updates:**
- Updated PHPDoc comments from Czech to English
- Added detailed method and property documentation
- Enhanced code examples and usage patterns

Links
=====

Homepage: https://www.vitexsoftware.cz/ease.php

GitHub: https://github.com/VitexSoftware/ease-core

phpDocumentor: http://vitexsoftware.cz/php-ease-core/

