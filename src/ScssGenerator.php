<?php
/**
 * SCSS Generator
 * 
 * Generates SCSS files for panels and components
 */

namespace ACFPanelGenerator;

class ScssGenerator
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Generate SCSS for a panel
     */
    public function generatePanelScss($layoutName, $layout)
    {
        $formattedName = str_replace('_', '-', $layoutName);
        $scss = ".{$this->config->get('html.panel_prefix')}{$formattedName} {\n\n";

        if ($this->config->get('scss.recursive_nesting', true)) {
            $scss .= $this->generateFieldsScss($layout['sub_fields'], '    ');
        } else {
            // Simple flat structure
            foreach ($layout['sub_fields'] as $field) {
                $fieldName = $this->formatFieldName($field);
                if ($fieldName && !$this->shouldSkipField($field)) {
                    $scss .= "    .{$fieldName} {\n        \n    }\n\n";
                }
            }
        }

        $scss .= "}";
        return $scss;
    }

    /**
     * Recursively generate SCSS for fields
     */
    private function generateFieldsScss($fields, $indent = '')
    {
        $scss = '';

        foreach ($fields as $field) {
            if ($this->shouldSkipField($field)) {
                continue;
            }

            $fieldName = $this->formatFieldName($field);
            if (!$fieldName) {
                continue;
            }

            $fieldType = $field['type'];

            // Handle repeaters
            if ($fieldType === 'repeater') {
                $scss .= "{$indent}.{$fieldName}-wrapper {\n";
                $scss .= "{$indent}    \n";

                if (isset($field['sub_fields'])) {
                    $scss .= $this->generateFieldsScss($field['sub_fields'], $indent . '    ');
                }

                $scss .= "{$indent}}\n\n";
            }
            // Handle flexible content
            elseif ($fieldType === 'flexible_content') {
                $scss .= "{$indent}.{$fieldName} {\n";
                $scss .= "{$indent}    \n";

                if (isset($field['layouts'])) {
                    foreach ($field['layouts'] as $layout) {
                        $layoutName = str_replace('_', '-', $layout['name']);
                        $scss .= "{$indent}    .{$layoutName} {\n";
                        $scss .= "{$indent}        \n";

                        if (isset($layout['sub_fields'])) {
                            $scss .= $this->generateFieldsScss($layout['sub_fields'], $indent . '        ');
                        }

                        $scss .= "{$indent}    }\n\n";
                    }
                }

                $scss .= "{$indent}}\n\n";
            }
            // Handle groups
            elseif ($fieldType === 'group') {
                $scss .= "{$indent}.{$fieldName} {\n";
                $scss .= "{$indent}    \n";

                if (isset($field['sub_fields'])) {
                    $scss .= $this->generateFieldsScss($field['sub_fields'], $indent . '    ');
                }

                $scss .= "{$indent}}\n\n";
            }
            // Regular fields
            else {
                $scss .= "{$indent}.{$fieldName} {\n";
                $scss .= "{$indent}    \n";
                $scss .= "{$indent}}\n\n";
            }
        }

        return $scss;
    }

    /**
     * Generate SCSS for a component
     */
    public function generateComponentScss($componentName)
    {
        $title = ucwords(str_replace('-', ' ', $componentName));
        
        $scss = "/**\n";
        $scss .= " * Component: {$title}\n";
        $scss .= " */\n\n";
        $scss .= ".{$componentName} {\n";
        $scss .= "    \n";
        $scss .= "}";

        return $scss;
    }

    /**
     * Generate index file for panels
     */
    public function generatePanelsIndex($panelNames)
    {
        $scss = "/**\n";
        $scss .= " * Panels Index\n";
        $scss .= " * Auto-generated file - imports all panel styles\n";
        $scss .= " */\n\n";

        foreach ($panelNames as $panelName) {
            $scss .= "@import 'panels/panel-{$panelName}';\n";
        }

        return $scss;
    }

    /**
     * Generate index file for components
     */
    public function generateComponentsIndex($componentNames)
    {
        $scss = "/**\n";
        $scss .= " * Components Index\n";
        $scss .= " * Auto-generated file - imports all component styles\n";
        $scss .= " */\n\n";

        $componentNames = array_unique($componentNames);
        sort($componentNames);

        foreach ($componentNames as $componentName) {
            $scss .= "@import 'components/{$componentName}';\n";
        }

        return $scss;
    }

    /**
     * Format field name for CSS class
     */
    private function formatFieldName($field)
    {
        return str_replace('_', '-', $field['name']);
    }

    /**
     * Check if field should be skipped
     */
    private function shouldSkipField($field)
    {
        $fieldType = $field['type'];
        $fieldName = $field['name'];

        if (in_array($fieldType, $this->config->getSkipFieldTypes())) {
            return true;
        }

        if (in_array($fieldName, $this->config->getSkipFieldNames())) {
            return true;
        }

        return false;
    }
}
