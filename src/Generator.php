<?php
/**
 * ACF Panel Generator
 * 
 * Main generator class that orchestrates the entire process
 */

namespace ACFPanelGenerator;

class Generator
{
    private $config;
    private $fieldProcessor;
    private $scssGenerator;
    private $results = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->fieldProcessor = new FieldProcessor($config);
        $this->scssGenerator = new ScssGenerator($config);
    }

    /**
     * Run the generation process
     */
    public function generate($overwrite = false)
    {
        $this->results = [
            'success' => false,
            'created' => 0,
            'skipped' => 0,
            'errors' => [],
            'messages' => [],
        ];

        try {
            // Load ACF JSON
            $acfData = $this->loadAcfJson();
            
            // Find flexible content field
            $flexibleField = $this->findFlexibleContentField($acfData);
            
            if (!$flexibleField) {
                throw new \Exception('No flexible content field found in ACF JSON');
            }

            // Delete existing files if overwrite mode
            if ($overwrite) {
                $this->deleteExistingFiles();
            }

            // Create directories
            $this->createDirectories();

            // Generate files
            $this->generatePanelFiles($flexibleField['layouts'], $overwrite);
            
            // Get component names from field processor
            $componentNames = $this->fieldProcessor->getComponentNames();
            
            // Generate component files
            $this->generateComponentFiles($componentNames, $overwrite);
            
            // Generate index files
            $this->generateIndexFiles($flexibleField['layouts'], $componentNames);

            $this->results['success'] = true;

        } catch (\Exception $e) {
            $this->results['errors'][] = $e->getMessage();
        }

        return $this->results;
    }

    /**
     * Load and parse ACF JSON file
     */
    private function loadAcfJson()
    {
        $path = $this->config->getAcfJsonPath();
        
        if (!file_exists($path)) {
            throw new \Exception("ACF JSON file not found: {$path}");
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!$data) {
            throw new \Exception("Could not parse ACF JSON file");
        }

        return $data;
    }

    /**
     * Find flexible content field in ACF data
     */
    private function findFlexibleContentField($acfData)
    {
        if (!isset($acfData['fields'])) {
            return null;
        }

        foreach ($acfData['fields'] as $field) {
            if ($field['type'] === 'flexible_content') {
                return $field;
            }
        }

        return null;
    }

    /**
     * Delete existing files in overwrite mode
     */
    private function deleteExistingFiles()
    {
        $this->results['messages'][] = '⚠️  OVERWRITE MODE: Deleting existing files...';

        // Delete PHP panel files
        $phpDir = $this->config->get('paths.php_output');
        if (is_dir($phpDir)) {
            $files = glob($phpDir . 'panel-*.php');
            foreach ($files as $file) {
                if (unlink($file)) {
                    $this->results['messages'][] = "🗑️  Deleted: {$file}";
                }
            }
        }

        // Delete SCSS panel files
        $scssDir = $this->config->get('paths.scss_panels');
        if (is_dir($scssDir)) {
            $files = glob($scssDir . '_panel-*.scss');
            foreach ($files as $file) {
                if (unlink($file)) {
                    $this->results['messages'][] = "🗑️  Deleted: {$file}";
                }
            }
        }

        // Delete SCSS component files
        $componentsDir = $this->config->get('paths.scss_components');
        if (is_dir($componentsDir)) {
            $files = glob($componentsDir . '_*.scss');
            foreach ($files as $file) {
                if (unlink($file)) {
                    $this->results['messages'][] = "🗑️  Deleted: {$file}";
                }
            }
        }
    }

    /**
     * Create output directories if they don't exist
     */
    private function createDirectories()
    {
        $dirs = [
            $this->config->get('paths.php_output'),
            $this->config->get('paths.scss_panels'),
            $this->config->get('paths.scss_components'),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0755, true)) {
                    $this->results['messages'][] = "📁 Created directory: {$dir}";
                }
            }
        }
    }

    /**
     * Generate panel PHP and SCSS files
     */
    private function generatePanelFiles($layouts, $overwrite)
    {
        $phpDir = $this->config->get('paths.php_output');
        $scssDir = $this->config->get('paths.scss_panels');

        foreach ($layouts as $layout) {
            $layoutName = str_replace('_', '-', $layout['name']);
            $phpFile = $phpDir . 'panel-' . $layoutName . '.php';
            $scssFile = $scssDir . '_panel-' . $layoutName . '.scss';

            // Generate PHP file
            if (!file_exists($phpFile) || $overwrite) {
                $phpContent = $this->generatePanelPhp($layout);
                file_put_contents($phpFile, $phpContent);
                $this->results['created']++;
                $this->results['messages'][] = "✓ Created: {$phpFile}";
            } else {
                $this->results['skipped']++;
                $this->results['messages'][] = "⊘ Skipped (exists): {$phpFile}";
            }

            // Generate SCSS file
            if (!file_exists($scssFile) || $overwrite) {
                $scssContent = $this->scssGenerator->generatePanelScss($layoutName, $layout);
                file_put_contents($scssFile, $scssContent);
                $this->results['created']++;
                $this->results['messages'][] = "✓ Created: {$scssFile}";
            } else {
                $this->results['skipped']++;
                $this->results['messages'][] = "⊘ Skipped (exists): {$scssFile}";
            }
        }
    }

    /**
     * Generate PHP content for a panel
     */
    private function generatePanelPhp($layout)
    {
        $layoutName = str_replace('_', '-', $layout['name']);
        $layoutLabel = $layout['label'];
        $wrapperClass = $this->config->get('html.wrapper_class', 'container');
        $panelPrefix = $this->config->get('html.panel_prefix', 'panel-');

        $php = "<section class=\"{$panelPrefix}{$layoutName}\">\n\n";
        $php .= "    <div class=\"{$wrapperClass}\">\n\n";

        foreach ($layout['sub_fields'] as $field) {
            $php .= $this->fieldProcessor->process($field, '        ');
        }

        $php .= "    </div>\n\n";
        $php .= "</section>";

        return $php;
    }

    /**
     * Generate component SCSS files
     */
    private function generateComponentFiles($componentNames, $overwrite)
    {
        $componentsDir = $this->config->get('paths.scss_components');
        $componentNames = array_unique($componentNames);

        foreach ($componentNames as $componentName) {
            $componentFile = $componentsDir . '_' . $componentName . '.scss';

            if (!file_exists($componentFile) || $overwrite) {
                $content = $this->scssGenerator->generateComponentScss($componentName);
                file_put_contents($componentFile, $content);
                $this->results['created']++;
                $this->results['messages'][] = "✓ Created: {$componentFile}";
            } else {
                $this->results['skipped']++;
                $this->results['messages'][] = "⊘ Skipped (exists): {$componentFile}";
            }
        }
    }

    /**
     * Generate index files
     */
    private function generateIndexFiles($layouts, $componentNames)
    {
        // Get panel names
        $panelNames = array_map(function($layout) {
            return str_replace('_', '-', $layout['name']);
        }, $layouts);

        // Generate panels index
        $panelsIndexPath = $this->config->get('paths.scss_panels_index');
        if ($panelsIndexPath) {
            $content = $this->scssGenerator->generatePanelsIndex($panelNames);
            file_put_contents($panelsIndexPath, $content);
            $this->results['messages'][] = "✓ Created/Updated: {$panelsIndexPath}";
        }

        // Generate components index
        $componentsIndexPath = $this->config->get('paths.scss_components_index');
        if ($componentsIndexPath) {
            $content = $this->scssGenerator->generateComponentsIndex($componentNames);
            file_put_contents($componentsIndexPath, $content);
            $this->results['messages'][] = "✓ Created/Updated: {$componentsIndexPath}";
        }
    }

    /**
     * Get generation results
     */
    public function getResults()
    {
        return $this->results;
    }
}
