<?php
/**
 * ACF Panel Generator Configuration
 * 
 * Copy this file to config.php and update with your project settings.
 */

return [
    /**
     * Project Paths
     * Can be absolute paths or relative to this config file
     */
    'paths' => [
        // ACF JSON file path
        'acf_json' => 'acf-json/group_xxxxx.json',
        
        // Output directories
        'php_output' => 'includes/panels/',
        'scss_panels' => 'assets/scss/panels/',
        'scss_components' => 'assets/scss/components/',
        
        // Index files
        'scss_panels_index' => 'assets/scss/_panels.scss',
        'scss_components_index' => 'assets/scss/_components.scss',
    ],

    /**
     * Component Fields
     * Fields with these names will use component includes
     */
    'component_fields' => [
        'image',
        'icon',
        'heading',
        'content',
        'button',
        'video',
    ],

    /**
     * Skip Fields
     * These field names will be completely ignored in output
     */
    'skip_field_names' => [
        'alignment',
        'theme',
        'background_image',
    ],

    /**
     * Skip Field Types
     * These ACF field types will be ignored
     */
    'skip_field_types' => [
        'tab',
        'accordion',
        'message',
    ],

    /**
     * HTML Structure
     */
    'html' => [
        'wrapper_class' => 'container',
        'panel_prefix' => 'panel-',
        'layout_prefix' => 'layout-',
    ],

    /**
     * Custom Field Templates (Optional)
     * Define custom output for specific fields
     * 
     * Example:
     * 'heading' => function($fieldName, $indent, $componentName) {
     *     return "{$indent}<h2 class=\"heading heading-2\">\n"
     *          . "{$indent}    <?php include('components/{$componentName}.php'); ?>\n"
     *          . "{$indent}</h2>\n\n";
     * }
     */
    'custom_templates' => [
        'heading' => function($fieldName, $indent, $componentName) {
            return "{$indent}<h2 class=\"heading heading-2\">\n"
                 . "{$indent}    <?php include('components/{$componentName}.php'); ?>\n"
                 . "{$indent}</h2>\n\n";
        },
    ],

    /**
     * SCSS Options
     */
    'scss' => [
        'generate_components' => true,
        'generate_panels' => true,
        'recursive_nesting' => true,
    ],
];
