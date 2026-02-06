# Quick Start Guide

Get up and running with ACF Panel Generator in 5 minutes.

## Step 1: Install

Download and place in your theme:

```bash
cd /path/to/your/theme
git clone https://github.com/yourusername/acf-panel-generator.git
cd acf-panel-generator
```

## Step 2: Configure

Copy the sample config:

```bash
cp config.sample.php config.php
```

Edit `config.php`:

```php
<?php
return [
    'paths' => [
        // Update this path to your ACF JSON file
        'acf_json' => '../acf-json/group_xxxxx.json',
        
        // Where to output PHP panel files
        'php_output' => '../includes/panels/',
        
        // Where to output SCSS files
        'scss_panels' => '../assets/scss/panels/',
        'scss_components' => '../assets/scss/components/',
        'scss_panels_index' => '../assets/scss/_panels.scss',
        'scss_components_index' => '../assets/scss/_components.scss',
    ],
    
    // Which fields should use component includes
    'component_fields' => [
        'image',
        'heading',
        'content',
        'button',
    ],
];
```

## Step 3: Generate

Open in browser:

```
http://localhost/your-site/wp-content/themes/your-theme/acf-panel-generator/
```

Click **"Generate New Files Only"**

## Step 4: Create Components

Create the component PHP files referenced in your panels:

**includes/components/heading.php:**
```php
<?php 
$heading = get_sub_field('heading');
if ($heading): 
    echo esc_html($heading);
endif; 
?>
```

**includes/components/image.php:**
```php
<?php 
$image = get_sub_field('image');
if ($image): ?>
    <img 
        src="<?php echo esc_url($image['url']); ?>" 
        alt="<?php echo esc_attr($image['alt']); ?>"
    >
<?php endif; ?>
```

## Step 5: Style Components

Edit the generated SCSS files:

**assets/scss/components/_heading.scss:**
```scss
.heading {
    font-family: var(--font-heading);
    font-weight: 700;
    line-height: 1.2;
}
```

## Step 6: Import SCSS

In your main stylesheet:

```scss
// main.scss
@import 'components';
@import 'panels';
```

## Step 7: Use in Templates

```php
<?php
// In your template file
if (have_rows('panels')):
    while (have_rows('panels')): the_row();
        $layout = get_row_layout();
        include('includes/panels/panel-' . $layout . '.php');
    endwhile;
endif;
?>
```

## Done! 🎉

You now have:
- ✅ Auto-generated panel PHP files
- ✅ Matching SCSS structure
- ✅ Component-based architecture
- ✅ Easy regeneration when ACF changes

## Common Paths

If your generator is in a different location, adjust paths accordingly:

**Generator in theme root:**
```php
'php_output' => 'includes/panels/',
```

**Generator in a tools folder:**
```php
'php_output' => '../includes/panels/',
```

**Generator outside theme:**
```php
'php_output' => '/absolute/path/to/theme/includes/panels/',
```

## Need Help?

- Read the full [README.md](README.md)
- Check [examples/](examples/) folder
- Open an issue on GitHub

## Next Steps

- Customize component templates in config
- Add more component fields
- Style your panels
- Build amazing things! 🚀
