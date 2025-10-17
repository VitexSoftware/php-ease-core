# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Repository Overview

EasePHP Core is a small, dependency-light runtime library for building CLI and web applications in PHP. It serves as the foundation for the broader EasePHP ecosystem, providing essential functionality like logging, configuration management, user abstraction, and internationalization.

## Code Architecture

### Core Classes Hierarchy

The framework follows an atom-to-complex hierarchy pattern:

1. **Atom** (`Ease\Atom`) - The minimal base class with object naming and draw() capability
2. **Molecule** (`Ease\Molecule`) - Extends Atom with property setup helpers from options/ENV/constants
3. **Sand** (`Ease\Sand`) - Data holder with typed helpers; integrates logging via trait
4. **Brick** (`Ease\Brick`) - Adds record identity (id/name/array/reuse) through recordkey trait

### Key Modules

1. **Logging** (`Ease\Logger\*`) - Multiple sink logging system
   - `Regent` - Aggregator that dispatches to various output destinations
   - Destinations: memory, console, file, syslog, std, eventlog

2. **Configuration** (`Ease\Shared`) - Configuration layer supporting constants, ENV, .env, and .json

3. **Internationalization** (`Ease\Locale`) - Gettext-based i18n with locale selection

4. **User Management** (`Ease\Anonym`, `Ease\User`) - User abstraction with authentication helpers

5. **Utilities** (`Ease\Functions`, `Ease\Mailer`) - Common utilities and mail functionality built on PEAR Mail

## Development Commands

### Environment Setup

```bash
# Install dependencies
composer install
```

### Testing

```bash
# Run PHPUnit tests
vendor/bin/phpunit --bootstrap tests/Bootstrap.php --configuration phpunit.xml

# Alternative (using Makefile)
make phpunit
```

### Code Quality

```bash
# Fix code style with PHP CS Fixer
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --diff --verbose

# Alternative (using Makefile)
make cs

# Run static analysis with PHPStan (level 6)
vendor/bin/phpstan analyse --configuration=phpstan-default.neon.dist --memory-limit=-1

# Alternative (using Makefile)
make static-code-analysis

# Run Rector to improve code quality
vendor/bin/rector process --dry-run
```

### Documentation

```bash
# Generate documentation with phpDocumentor
make phpdoc

# Generate documentation with ApiGen
make apigen
```

### Package Building

```bash
# Build Debian package
make deb

# Build RPM package
make rpm

# Build Docker image
make dimage
```

## Configuration

The framework can be configured using PHP constants, environment variables, or configuration files (.env, .json). Key configuration constants include:

- `EASE_APPNAME` - Application name (used in logs)
- `EASE_LOGGER` - Logger sinks (memory, console, file, syslog, email, std, eventlog)
- `EASE_FROM` - Email sender address
- `EASE_SMTP` - SMTP settings (JSON encoded)
- `LOG_DIRECTORY` - File logger directory
- `LOG_FACILITY` - Syslog facility

## Best Practices

1. Use the appropriate base class based on your needs (Atom, Molecule, Sand, Brick)
2. Configure logging through `EASE_LOGGER` constant or environment variable
3. For internationalization, use the `Ease\Locale` class with gettext
4. When extending the framework, follow the existing namespace structure
5. Always run tests after making changes