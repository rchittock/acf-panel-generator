<?php
/**
 * Field Processor
 * 
 * Handles generating PHP output for different ACF field types
 */

namespace ACFPanelGenerator;

class FieldProcessor
{
    private $config;
    private $componentNames = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Process a field and return PHP output
     */
    public function process($field, $indent = '')
    {
        $fieldName = $field['name'];
        $fieldType = $field['type'];

        // Skip certain field types
        if (in_array($fieldType, $this->config->getSkipFieldTypes())) {
            return '';
        }

        // Skip certain field names
        if (in_array($fieldName, $this->config->getSkipFieldNames())) {
            return '';
        }

        $componentName = str_replace('_', '-', $fieldName);

        // Check if this field should use a component
        if (in_array($fieldName, $this->config->getComponentFields())) {
            $this->componentNames[] = $componentName;
            return $this->processComponentField($fieldName, $fieldType, $componentName, $indent);
        }

        // Process based on field type
        return $this->processFieldType($field, $indent, $componentName);
    }

    /**
     * Process a field that uses a component
     */
    private function processComponentField($fieldName, $fieldType, $componentName, $indent)
    {
        // Check for custom template
        $customTemplate = $this->config->getCustomTemplate($fieldName);
        if ($customTemplate && is_callable($customTemplate)) {
            return $customTemplate($fieldName, $indent, $componentName);
        }

        // Default component include
        return "{$indent}<?php include('components/{$componentName}.php'); ?>\n\n";
    }

    /**
     * Process field based on its type
     */
    private function processFieldType($field, $indent, $componentName)
    {
        $fieldName = $field['name'];
        $fieldType = $field['type'];
        $output = '';

        switch ($fieldType) {
            case 'text':
            case 'textarea':
            case 'wysiwyg':
            case 'email':
            case 'number':
            case 'range':
            case 'url':
            case 'select':
            case 'radio':
            case 'button_group':
            case 'true_false':
            case 'checkbox':
            case 'file':
            case 'oembed':
            case 'google_map':
            case 'date_picker':
            case 'date_time_picker':
            case 'time_picker':
            case 'color_picker':
                $output = "{$indent}<?php echo get_sub_field('{$fieldName}'); ?>\n\n";
                break;

            case 'image':
                $output = "{$indent}<?php \$image = get_sub_field('{$fieldName}'); ?>\n";
                $output .= "{$indent}<?php if (\$image): ?>\n";
                $output .= "{$indent}    <img src=\"<?php echo esc_url(\$image['url']); ?>\" alt=\"<?php echo esc_attr(\$image['alt']); ?>\">\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'gallery':
                $output = "{$indent}<?php \$images = get_sub_field('{$fieldName}'); ?>\n";
                $output .= "{$indent}<?php if (\$images): ?>\n";
                $output .= "{$indent}    <?php foreach (\$images as \$image): ?>\n";
                $output .= "{$indent}        <img src=\"<?php echo esc_url(\$image['url']); ?>\" alt=\"<?php echo esc_attr(\$image['alt']); ?>\">\n";
                $output .= "{$indent}    <?php endforeach; ?>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'link':
                $output = "{$indent}<?php \$link = get_sub_field('{$fieldName}'); ?>\n";
                $output .= "{$indent}<?php if (\$link): ?>\n";
                $output .= "{$indent}    <a href=\"<?php echo esc_url(\$link['url']); ?>\" target=\"<?php echo esc_attr(\$link['target']); ?>\"><?php echo esc_html(\$link['title']); ?></a>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'relationship':
            case 'post_object':
                $output = "{$indent}<?php \${$componentName} = get_sub_field('{$fieldName}'); ?>\n";
                $output .= "{$indent}<?php if (\${$componentName}): ?>\n";
                
                if ($fieldType === 'relationship') {
                    $output .= "{$indent}    <?php foreach (\${$componentName} as \$post): ?>\n";
                } else {
                    $output .= "{$indent}    <?php \$post = \${$componentName}; ?>\n";
                }
                
                $output .= "{$indent}        <?php setup_postdata(\$post); ?>\n";
                $output .= "{$indent}        <a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>\n";
                
                if ($fieldType === 'relationship') {
                    $output .= "{$indent}    <?php endforeach; ?>\n";
                }
                
                $output .= "{$indent}    <?php wp_reset_postdata(); ?>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'repeater':
                $output = $this->processRepeater($field, $indent, $componentName);
                break;

            case 'flexible_content':
                $output = $this->processFlexibleContent($field, $indent);
                break;

            case 'group':
                $output = $this->processGroup($field, $indent, $componentName);
                break;

            default:
                $output = "{$indent}<?php // Field type: {$fieldType} ?>\n";
                $output .= "{$indent}<?php echo get_sub_field('{$fieldName}'); ?>\n\n";
                break;
        }

        return $output;
    }

    /**
     * Process repeater field
     */
    private function processRepeater($field, $indent, $componentName)
    {
        $fieldName = $field['name'];
        $output = '';

        $output .= "{$indent}<?php if (have_rows('{$fieldName}')): ?>\n";
        $output .= "{$indent}    <div class=\"{$componentName}-wrapper\">\n";
        $output .= "{$indent}        <?php while (have_rows('{$fieldName}')): the_row(); ?>\n";

        if (isset($field['sub_fields'])) {
            foreach ($field['sub_fields'] as $subField) {
                $output .= $this->process($subField, $indent . '            ');
            }
        }

        $output .= "{$indent}        <?php endwhile; ?>\n";
        $output .= "{$indent}    </div>\n";
        $output .= "{$indent}<?php endif; ?>\n\n";

        return $output;
    }

    /**
     * Process flexible content field
     */
    private function processFlexibleContent($field, $indent)
    {
        $fieldName = $field['name'];
        $output = '';

        $output .= "{$indent}<?php if (have_rows('{$fieldName}')): ?>\n";
        $output .= "{$indent}    <div class=\"{$fieldName}\">\n";
        $output .= "{$indent}        <?php while (have_rows('{$fieldName}')): the_row(); ?>\n";

        if (isset($field['layouts'])) {
            foreach ($field['layouts'] as $layout) {
                $layoutName = str_replace('_', '-', $layout['name']);
                $layoutOriginal = $layout['name'];

                $output .= "{$indent}            <?php if (get_row_layout() == '{$layoutOriginal}'): ?>\n";
                $output .= "{$indent}                <div class=\"{$layoutName}\">\n\n";

                if (isset($layout['sub_fields'])) {
                    foreach ($layout['sub_fields'] as $subField) {
                        $output .= $this->process($subField, $indent . '                    ');
                    }
                }

                $output .= "{$indent}                </div>\n";
                $output .= "{$indent}            <?php endif; ?>\n\n";
            }
        }

        $output .= "{$indent}        <?php endwhile; ?>\n";
        $output .= "{$indent}    </div>\n";
        $output .= "{$indent}<?php endif; ?>\n\n";

        return $output;
    }

    /**
     * Process group field
     */
    private function processGroup($field, $indent, $componentName)
    {
        $fieldName = $field['name'];
        $output = '';

        $output .= "{$indent}<?php if (get_sub_field('{$fieldName}')): ?>\n";
        $output .= "{$indent}    <div class=\"{$componentName}\">\n";

        if (isset($field['sub_fields'])) {
            foreach ($field['sub_fields'] as $subField) {
                // For group sub fields, we need to use get_sub_field with the group context
                // This is a simplified version - might need adjustment based on nesting level
                $output .= $this->process($subField, $indent . '        ');
            }
        }

        $output .= "{$indent}    </div>\n";
        $output .= "{$indent}<?php endif; ?>\n\n";

        return $output;
    }

    /**
     * Get list of component names that were used
     */
    public function getComponentNames()
    {
        return array_unique($this->componentNames);
    }

    /**
     * Reset component names (for processing multiple layouts)
     */
    public function resetComponentNames()
    {
        $this->componentNames = [];
    }
}
