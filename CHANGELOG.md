# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.49.2] - 2025-10-17

### Fixed

- **Class Redeclaration Issue**: Fixed fatal error in `Functions::loadClassesInNamespace()` that could cause "Cannot redeclare class" errors when loading classes from multiple paths
- **Duplicate Class Detection**: Added class existence check before including files to prevent redeclaration issues
- **Enhanced Namespace Loading**: Improved namespace loading mechanism to be more resilient when working with complex class hierarchies

## [1.49.1] - 2025-10-02

### Fixed

- **IntlDateFormatter Fatal Error**: Resolved `"Found unconstructed IntlDateFormatter"` crashes in `ToConsole` logger
- **Exception Handling**: Added comprehensive error handling for `ValueError` and `Error` exceptions in date formatting
- **Type Safety**: Fixed PHPStan level 8 analysis issues with proper type annotations

### Added

- **Graceful Fallback**: Console logger now automatically falls back to standard PHP date formatting when `IntlDateFormatter` fails
- **Enhanced Error Handling**: Added try-catch blocks around `datefmt_create()` and `datefmt_format()` calls
- **Comprehensive Tests**: Added unit tests for edge cases including invalid locales, null values, and error scenarios
- **Resource Validation**: Added proper error handling for stdout/stderr stream operations

### Changed

- **Documentation**: Updated PHPDoc comments from Czech to English with detailed method descriptions
- **Class Documentation**: Enhanced class-level documentation with comprehensive feature descriptions
- **Property Types**: Added proper type hints and annotations for all class properties

### Improved

- **Code Quality**: Achieved PHPStan level 8 compliance with zero errors
- **Test Coverage**: Extended test suite to cover error scenarios and edge cases
- **Reliability**: Logger operations are now guaranteed not to crash due to internationalization issues

## [1.49.0] - 2025-07-17

### Added

- FromToDate trait for date range functionality
- datescope trait update

## [1.48.0] - 2025-04-27

### Added

- `isJson()` method for JSON validation

## Previous Versions

See [debian/changelog](debian/changelog) for detailed version history.
