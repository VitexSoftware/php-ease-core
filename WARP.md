# WARP.md - Working AI Reference for php-vitexsoftware-ease-core

## Project Overview
**Type**: PHP Project/Debian Package
**Purpose**: ![EasePHP Framework Logo](ease-core-social-preview.png?raw=true "Project Logo")
**Status**: Active
**Repository**: git@github.com:VitexSoftware/php-ease-core.git

## Key Technologies
- PHP
- Composer
- Debian Packaging
- Docker

## Architecture & Structure
```
php-vitexsoftware-ease-core/
├── src/           # Source code
├── tests/         # Test files
├── docs/          # Documentation
└── ...
```

## Development Workflow

### Prerequisites
- Development environment setup
- Required dependencies

### Setup Instructions
```bash
# Clone the repository
git clone git@github.com:VitexSoftware/php-ease-core.git
cd php-vitexsoftware-ease-core

# Install dependencies
composer install
```

### Build & Run
```bash
dpkg-buildpackage -b -uc\ndocker build -t php-vitexsoftware-ease-core .
```

### Testing
```bash
composer test
```

## Key Concepts
- **Main Components**: Core functionality and modules
- **Configuration**: Configuration files and environment variables
- **Integration Points**: External services and dependencies

## Common Tasks

### Development
- Review code structure
- Implement new features
- Fix bugs and issues

### Deployment
- Build and package
- Deploy to target environment
- Monitor and maintain

## Troubleshooting
- **Common Issues**: Check logs and error messages
- **Debug Commands**: Use appropriate debugging tools
- **Support**: Check documentation and issue tracker

## Additional Notes
- Project-specific conventions
- Development guidelines
- Related documentation

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
