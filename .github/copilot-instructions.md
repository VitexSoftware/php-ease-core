<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

# PHP Ease Core Library - Development Guidelines

This document contains specific coding standards and practices for the PHP Ease Core library development. All AI assistants must follow these guidelines when working on this codebase.

## Project Context
- **Language**: PHP 8.4 or later
- **Framework**: Ease Core library for PHP applications
- **Testing**: PHPUnit for unit testing
- **Standards**: PSR-12 coding standard
- **Internationalization**: Uses i18n library with `_()` function for translatable strings

## Language and Communication

### Language Requirements
- All code comments must be written in English
- All user-facing messages and error messages must be written in English
- All documentation must use Markdown format
- Commit messages must use imperative mood and be concise

### Code Comments
- Use complete sentences with proper grammar
- Explain the "why" and "how", not just "what"
- Keep comments up-to-date with code changes

## Code Quality Standards

### PHP Version and Compatibility
- **MANDATORY**: All code must be written in PHP 8.4 or later
- Ensure compatibility with the latest PHP version and all used libraries
- Consider performance implications and optimize where necessary

### Coding Standards
- **MANDATORY**: Follow PSR-12 coding standard strictly
- Use meaningful, descriptive variable names that clearly indicate their purpose
- Avoid magic numbers or strings - define named constants instead
- **MANDATORY**: Include type hints for all function parameters and return types

### Documentation
- **MANDATORY**: Include PHPDoc docblocks for all classes, functions, and methods
- Docblocks must describe:
  - Purpose of the class/function
  - All parameters with their types and descriptions
  - Return types and descriptions
  - Any exceptions that may be thrown

## Security and Error Handling

### Security Practices
- Never expose sensitive information in code or logs
- Handle exceptions properly with meaningful error messages
- Validate all inputs and sanitize outputs

### Error Handling
- Always catch and handle exceptions appropriately
- Provide clear, actionable error messages to users
- Log errors securely without exposing sensitive data

## Testing Requirements

### Unit Testing
- **MANDATORY**: Use PHPUnit for all testing
- **MANDATORY**: Follow PSR-12 standard in test files
- **MANDATORY**: Create or update PHPUnit test files for every new or modified class
- Ensure all code is well-tested with comprehensive unit tests
- Tests must cover edge cases and error conditions

### Testing Workflow
- Write tests before implementing new features (TDD approach preferred)
- Run tests after every code change to ensure functionality
- Maintain high test coverage for reliability

## Internationalization
- **MANDATORY**: Use the `_()` function for all user-facing strings that need translation
- Never hardcode translatable text in the source code
- Support multiple languages through the i18n system

## Code Maintenance
- Ensure code is maintainable and follows best practices
- Refactor when necessary to improve readability and performance
- Keep dependencies up-to-date and compatible

## Quality Assurance

### Code Validation
- **MANDATORY**: After every PHP file edit, run `php -l <filename>` to check syntax
- Fix any syntax errors immediately before proceeding
- Use static analysis tools (PHPStan) to catch potential issues

### Code Style
- Use automated tools to enforce coding standards
- Keep code consistent across the entire codebase
- Review code for readability and maintainability

## Development Workflow

### File Changes
- When creating new classes: Always create corresponding test files
- When modifying existing classes: Always update or create corresponding test files
- Test all changes thoroughly before committing

### Commit Practices
- Use clear, descriptive commit messages in imperative mood
- Commit related changes together
- Ensure all tests pass before pushing changes

## Priority Guidelines

1. **MANDATORY** items must always be followed - these are critical requirements
2. Security and error handling take precedence over other considerations
3. Code must be functional and tested before style/formatting concerns
4. Maintain backward compatibility unless explicitly stated otherwise
5. Performance optimizations should not compromise code readability or maintainability
