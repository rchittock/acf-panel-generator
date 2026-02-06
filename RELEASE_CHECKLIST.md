# Release Checklist

Use this checklist before releasing ACF Panel Generator.

## Pre-Release

### Code Quality
- [ ] All classes have proper docblocks
- [ ] No debug code (`var_dump`, `console.log`, etc.)
- [ ] Error handling is comprehensive
- [ ] Code follows PSR-12 standards

### Testing
- [ ] Test with various ACF field configurations
- [ ] Test with nested flexible content
- [ ] Test with repeater fields
- [ ] Test overwrite mode
- [ ] Test non-overwrite mode
- [ ] Test with missing directories (should auto-create)
- [ ] Test with invalid ACF JSON
- [ ] Test with missing config.php
- [ ] Test on PHP 7.4
- [ ] Test on PHP 8.0+
- [ ] Verify generated PHP is valid
- [ ] Verify generated SCSS compiles

### Documentation
- [ ] README.md is up to date
- [ ] QUICKSTART.md is accurate
- [ ] CHANGELOG.md has version entry
- [ ] All code examples work
- [ ] Screenshots are current (if applicable)
- [ ] config.sample.php has all options
- [ ] Examples folder has working samples

### Repository
- [ ] composer.json has correct info
- [ ] LICENSE is included
- [ ] .gitignore is comprehensive
- [ ] No sensitive data in repo
- [ ] Update URLs to actual GitHub repo
- [ ] Update author email/name

## GitHub Setup

### Repository Settings
- [ ] Create GitHub repository
- [ ] Add comprehensive description
- [ ] Add topics/tags (wordpress, acf, generator, etc.)
- [ ] Set up branch protection (optional)
- [ ] Enable issues
- [ ] Enable discussions (optional)

### Repository Files
- [ ] Add GitHub templates:
  - [ ] .github/ISSUE_TEMPLATE/bug_report.md
  - [ ] .github/ISSUE_TEMPLATE/feature_request.md
  - [ ] .github/PULL_REQUEST_TEMPLATE.md

### Release
- [ ] Create GitHub release
- [ ] Tag with version number (v1.0.0)
- [ ] Add release notes from CHANGELOG
- [ ] Attach zip file (optional)

## Marketing

### Initial Launch
- [ ] Post on r/WordPress
- [ ] Post on r/webdev
- [ ] Share on Twitter/X
- [ ] Share in ACF Facebook group
- [ ] Share in WordPress developer communities
- [ ] Post on Dev.to (optional)
- [ ] Submit to Product Hunt (optional)

### Content
- [ ] Write launch blog post
- [ ] Create demo video (optional)
- [ ] Take screenshots for README
- [ ] Create social media graphics

### SEO
- [ ] Optimize README for search
- [ ] Add keywords to composer.json
- [ ] Include relevant tags on GitHub

## Post-Release

### Monitoring
- [ ] Watch for issues
- [ ] Respond to questions quickly
- [ ] Monitor star count
- [ ] Track feedback

### Maintenance
- [ ] Set up GitHub Actions for tests (future)
- [ ] Plan next features based on feedback
- [ ] Update documentation as needed
- [ ] Keep dependencies updated

## Version-Specific Checklist

### v1.0.0
- [ ] Initial release announcement
- [ ] Clear "this is stable" messaging
- [ ] Get initial feedback
- [ ] Fix any critical bugs quickly

### Future Versions
- [ ] Update CHANGELOG.md
- [ ] Tag new release
- [ ] Announce changes
- [ ] Update documentation

## Success Metrics

Track these to measure success:
- GitHub stars
- Issues opened/closed
- Pull requests
- Downloads (if tracked)
- Community feedback
- Adoption by agencies/developers

## Notes

- Respond to all issues within 24-48 hours
- Be open to feedback and suggestions
- Thank contributors
- Keep code quality high
- Document everything

---

Remember: The goal is to help developers, not just to release software!
