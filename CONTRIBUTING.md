# Contributing to ACF Panel Generator

Thank you for your interest in contributing! This document provides guidelines for contributing to the project.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce**
- **Expected behavior**
- **Actual behavior**
- **Screenshots** (if applicable)
- **Your environment** (PHP version, ACF version, etc.)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear use case** - Why is this needed?
- **Detailed description** - How should it work?
- **Examples** - Show what the output would look like

### Pull Requests

1. Fork the repo
2. Create a new branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Test thoroughly
5. Commit with clear messages (`git commit -m 'Add amazing feature'`)
6. Push to your branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## Development Setup

```bash
git clone https://github.com/yourusername/acf-panel-generator.git
cd acf-panel-generator
cp config.sample.php config.php
# Edit config.php with test paths
```

## Coding Standards

### PHP

- Follow PSR-12 coding standards
- Use meaningful variable names
- Comment complex logic
- Keep functions focused and small

### Documentation

- Update README.md for user-facing changes
- Update CHANGELOG.md for all changes
- Add inline comments for complex code
- Update examples if output format changes

## Testing

Before submitting:

- Test with various ACF field configurations
- Test overwrite and non-overwrite modes
- Verify generated files are syntactically correct
- Check SCSS compiles without errors
- Test on different PHP versions (7.4, 8.0, 8.1+)

## What to Contribute

### High Priority

- Bug fixes
- Documentation improvements
- Additional field type support
- Error handling improvements
- Performance optimizations

### Feature Ideas

- CLI tool version
- WordPress plugin version
- Additional CSS framework templates
- Dry run / preview mode
- Better validation
- Unit tests

### Low Priority

- Code style improvements
- Minor refactoring
- Translation support (future consideration)

## Questions?

Open an issue with the "question" label or reach out directly.

Thank you for contributing! 🎉
