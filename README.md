# ACF Panel Generator

> Automate your ACF flexible content theme development with component-based architecture.

Generate complete panel PHP files, SCSS files, and reusable component includes directly from your ACF JSON files.

## ✨ Features

- 🚀 **Batch Generation** - Create all panel files instantly
- 🧩 **Component Architecture** - Reusable component includes
- 🎨 **SCSS Generation** - Matching SCSS files with recursive nesting
- ⚡ **Fast Iteration** - Regenerate everything when ACF changes
- 🎯 **Customizable** - Define your own field mappings and templates
- 🔄 **Flexible Content Support** - Handles nested layouts recursively
- 📦 **Zero Dependencies** - Pure PHP, works anywhere

## 🎯 What Problem Does This Solve?

**Without ACF Panel Generator:**
- Copy/paste ACF field code for every field ❌
- Manually create 20+ panel PHP files ❌
- Manually create matching SCSS files ❌
- Inconsistent code across panels ❌
- Time-consuming updates when ACF changes ❌

**With ACF Panel Generator:**
- One click generates everything ✅
- Consistent component-based architecture ✅
- Matching SCSS with proper nesting ✅
- Regenerate instantly when ACF changes ✅

## 📦 Installation

### Option 1: Download

1. Download this repository
2. Place in your theme or project root
3. Copy `config.sample.php` to `config.php`
4. Configure your paths and settings
5. Navigate to `index.php` in your browser

### Option 2: Git Clone

```bash
cd /path/to/your/theme
git clone https://github.com/yourusername/acf-panel-generator.git
cd acf-panel-generator
cp config.sample.php config.php
```

## ⚙️ Configuration

Edit `config.php`:

```php
<?php
return [
    'paths' => [
        'acf_json' => 'acf-json/group_xxxxx.json',
        'php_output' => 'includes/panels/',
        'scss_panels' => 'assets/scss/panels/',
        'scss_components' => 'assets/scss/components/',
        'scss_panels_index' => 'assets/scss/_panels.scss',
        'scss_components_index' => 'assets/scss/_components.scss',
    ],
    
    'component_fields' => [
        'image',
        'heading',
        'content',
        'button',
    ],
    
    'skip_field_names' => [
        'alignment',
        'theme',
    ],
];
```

### Configuration Options

| Option | Description |
|--------|-------------|
| `paths.acf_json` | Path to your ACF JSON file |
| `paths.php_output` | Where to create panel PHP files |
| `paths.scss_panels` | Where to create panel SCSS files |
| `paths.scss_components` | Where to create component SCSS files |
| `component_fields` | Which fields should use component includes |
| `skip_field_names` | Field names to completely ignore |
| `skip_field_types` | Field types to ignore (tab, accordion, etc) |
| `html.wrapper_class` | Container class name (default: 'container') |
| `custom_templates` | Custom output for specific fields |

## 🚀 Usage

### Web Interface

1. Navigate to `index.php` in your browser
2. Review your configuration
3. Click **"Generate New Files Only"** or **"Delete & Regenerate All"**

### What Gets Generated

For a flexible content field with layouts "hero" and "text_image":

```
your-theme/
├── includes/panels/
│   ├── panel-hero.php
│   └── panel-text-image.php
├── assets/scss/
│   ├── _panels.scss (index file)
│   ├── _components.scss (index file)
│   ├── panels/
│   │   ├── _panel-hero.scss
│   │   └── _panel-text-image.scss
│   └── components/
│       ├── _heading.scss
│       ├── _image.scss
│       └── _content.scss
```

### Example Generated Panel

**panel-hero.php:**
```php
<section class="panel-hero">

    <div class="container">

        <h2 class="heading heading-2">
            <?php include('components/heading.php'); ?>
        </h2>

        <?php include('components/image.php'); ?>

        <?php include('components/content.php'); ?>

    </div>

</section>
```

**_panel-hero.scss:**
```scss
.panel-hero {

    .heading {
        
    }

    .image {
        
    }

    .content {
        
    }

}
```

### Using Generated Files

Import the SCSS index files in your main stylesheet:

```scss
// main.scss
@import 'components'; // Base component styles
@import 'panels';     // Panel-specific overrides
```

Include panels in your flexible content loop:

```php
<?php if (have_rows('content_panels')): ?>
    <?php while (have_rows('content_panels')): the_row(); ?>
        <?php 
        $layout = get_row_layout();
        include('includes/panels/panel-' . $layout . '.php');
        ?>
    <?php endwhile; ?>
<?php endif; ?>
```

## 🎨 Component Architecture

### Why Components?

Instead of inline field code:

```php
<!-- ❌ Not maintainable -->
<h2><?php echo get_sub_field('heading'); ?></h2>
<img src="<?php echo get_sub_field('image')['url']; ?>">
```

Use component includes:

```php
<!-- ✅ Clean and reusable -->
<?php include('components/heading.php'); ?>
<?php include('components/image.php'); ?>
```

### Create Components

**components/heading.php:**
```php
<?php 
$heading = get_sub_field('heading');
if ($heading): 
?>
    <?php echo esc_html($heading); ?>
<?php endif; ?>
```

**components/image.php:**
```php
<?php 
$image = get_sub_field('image');
if ($image): 
?>
    <img 
        src="<?php echo esc_url($image['url']); ?>" 
        alt="<?php echo esc_attr($image['alt']); ?>"
        loading="lazy"
    >
<?php endif; ?>
```

## 🔧 Advanced Configuration

### Custom Field Templates

Define custom output for specific fields:

```php
'custom_templates' => [
    'heading' => function($fieldName, $indent, $componentName) {
        return "{$indent}<h1 class=\"title\">\n"
             . "{$indent}    <?php include('components/{$componentName}.php'); ?>\n"
             . "{$indent}</h1>\n\n";
    },
],
```

### Supported Field Types

- Text, Textarea, WYSIWYG
- Image, Gallery, File
- Link, URL
- Select, Radio, Checkbox, Button Group
- True/False
- Number, Range
- Date Picker, Time Picker, Color Picker
- Relationship, Post Object
- **Repeater** (with recursive sub-fields)
- **Flexible Content** (with recursive layouts)
- **Group**
- oEmbed, Google Map

## 📝 Workflow

### Initial Setup

1. Build your ACF flexible content field
2. Configure ACF Panel Generator
3. Generate panel files
4. Create your component PHP files
5. Style components in SCSS

### When ACF Changes

1. Update ACF field group
2. Click "Delete & Regenerate All"
3. Panel structure updates automatically
4. Add new components if needed

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

MIT License - see LICENSE file for details

## 💡 Tips

- **Use meaningful field names** - They become class names
- **Organize components** - Group related components in subfolders
- **Version control** - Commit generated files to see changes
- **Component first** - Build reusable components, then use everywhere
- **Test after regenerating** - Always check output after bulk regeneration

## 🆚 Comparison to Other Tools

| Feature | ACF Panel Generator | ACF Theme Code Pro |
|---------|---------------------|-------------------|
| Generate actual files | ✅ | ❌ |
| Component architecture | ✅ | ❌ |
| SCSS generation | ✅ | ❌ |
| Batch processing | ✅ | ❌ |
| Customizable templates | ✅ | ❌ |
| Regenerate on changes | ✅ | ❌ |

## 📚 Resources

- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [Component-based Architecture](https://bradfrost.com/blog/post/atomic-web-design/)

## ⚠️ Requirements

- PHP 7.4 or higher
- ACF Pro (for flexible content)
- Write permissions for output directories

## 🐛 Troubleshooting

**"Configuration file not found"**
- Copy `config.sample.php` to `config.php`

**"ACF JSON file not found"**
- Check the path in your config
- Make sure ACF Local JSON is enabled

**"Permission denied"**
- Make output directories writable: `chmod 755 includes/panels`

**Generated files are empty**
- Check that your ACF JSON has a flexible_content field
- Verify field names aren't in your skip lists

## 📧 Support

- Open an issue on GitHub
- Email: your@email.com
- Twitter: @yourhandle

---

Made with ❤️ for the WordPress community
