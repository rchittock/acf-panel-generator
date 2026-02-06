# Project Structure

```
acf-panel-generator/
│
├── 📄 index.php                    # Main web interface
├── 📄 config.sample.php            # Sample configuration (copy to config.php)
├── 📄 config.php                   # Your actual config (gitignored)
├── 📄 composer.json                # Package definition
│
├── 📁 src/                         # Core application code
│   ├── Config.php                  # Configuration handler
│   ├── Generator.php               # Main generator orchestrator
│   ├── FieldProcessor.php          # Field output logic
│   └── ScssGenerator.php           # SCSS generation
│
├── 📁 assets/                      # Frontend assets
│   ├── css/
│   │   └── admin.css              # Web interface styles
│   └── js/
│       └── admin.js               # Web interface scripts
│
├── 📁 examples/                    # Example files
│   ├── sample-acf.json            # Sample ACF JSON structure
│   └── output/                    # Example generated files
│       ├── panel-hero.php
│       ├── _panel-hero.scss
│       └── _heading.scss
│
├── 📄 README.md                    # Main documentation
├── 📄 QUICKSTART.md               # Quick start guide
├── 📄 CHANGELOG.md                # Version history
├── 📄 CONTRIBUTING.md             # Contribution guidelines
├── 📄 LICENSE                     # MIT License
└── 📄 .gitignore                  # Git ignore rules
```

## Generated File Structure

When you run the generator, it creates files in your theme:

```
your-theme/
│
├── 📁 includes/
│   ├── panels/
│   │   ├── panel-hero.php
│   │   ├── panel-text-image.php
│   │   └── panel-gallery.php
│   │
│   └── components/
│       ├── heading.php            # You create these
│       ├── image.php
│       ├── content.php
│       └── button.php
│
└── 📁 assets/
    └── scss/
        ├── _panels.scss           # Auto-generated index
        ├── _components.scss       # Auto-generated index
        │
        ├── panels/
        │   ├── _panel-hero.scss
        │   ├── _panel-text-image.scss
        │   └── _panel-gallery.scss
        │
        └── components/
            ├── _heading.scss
            ├── _image.scss
            ├── _content.scss
            └── _button.scss
```

## Workflow

```
1. Configure paths ──→ 2. Generate files ──→ 3. Create components ──→ 4. Style
                                                                            │
                                                                            ↓
    ACF changes? ←──── 5. Regenerate ←──────────────────────────────────────┘
```

## Files You Edit

✏️ **You edit:**
- `config.php` - Your configuration
- `includes/components/*.php` - Component logic
- `assets/scss/components/_*.scss` - Component styles
- `assets/scss/panels/_*.scss` - Panel-specific styles

🤖 **Generator creates:**
- `includes/panels/panel-*.php` - Panel structure
- `assets/scss/panels/_*.scss` - Panel SCSS scaffolding
- `assets/scss/_panels.scss` - Index file
- `assets/scss/_components.scss` - Index file

## Component Architecture

```
Panel File (Generated)          Component File (You Create)
├── panel-hero.php             
│   ├── includes heading.php ──→ components/heading.php
│   ├── includes image.php ────→ components/image.php
│   └── includes content.php ──→ components/content.php
│
SCSS (Generated)               SCSS (You Style)
├── _panel-hero.scss           
│   ├── .heading ──────────────→ components/_heading.scss
│   ├── .image ────────────────→ components/_image.scss
│   └── .content ──────────────→ components/_content.scss
```

## Quick Reference

| File | Purpose | Generated? |
|------|---------|------------|
| `panel-*.php` | Panel structure | ✅ Yes |
| `components/*.php` | Field logic | ❌ No, you create |
| `_panel-*.scss` | Panel styles | ✅ Scaffolding only |
| `_components/*.scss` | Component styles | ✅ Scaffolding only |
| `_panels.scss` | Import index | ✅ Yes, always |
| `_components.scss` | Import index | ✅ Yes, always |
