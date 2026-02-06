<?php
/**
 * Configuration Handler
 * 
 * Loads and validates configuration settings
 */

namespace ACFPanelGenerator;

class Config
{
    private $config;
    private $basePath;

    public function __construct($configPath, $basePath = null)
    {
        $this->basePath = $basePath ?: dirname($configPath);
        
        if (!file_exists($configPath)) {
            throw new \Exception("Configuration file not found: {$configPath}");
        }

        $this->config = require $configPath;
        $this->validate();
        $this->resolvePaths();
    }

    /**
     * Validate required configuration keys
     */
    private function validate()
    {
        $required = ['paths', 'component_fields'];
        
        foreach ($required as $key) {
            if (!isset($this->config[$key])) {
                throw new \Exception("Missing required config key: {$key}");
            }
        }

        $requiredPaths = ['acf_json', 'php_output', 'scss_panels', 'scss_components'];
        foreach ($requiredPaths as $pathKey) {
            if (!isset($this->config['paths'][$pathKey])) {
                throw new \Exception("Missing required path: {$pathKey}");
            }
        }
    }

    /**
     * Resolve relative paths to absolute paths
     */
    private function resolvePaths()
    {
        foreach ($this->config['paths'] as $key => $path) {
            // If path is not absolute, make it relative to base path
            if ($path[0] !== '/' && !preg_match('/^[A-Z]:/i', $path)) {
                $this->config['paths'][$key] = $this->basePath . '/' . $path;
            }
        }
    }

    /**
     * Get a configuration value
     */
    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Get all configuration
     */
    public function all()
    {
        return $this->config;
    }

    /**
     * Check if ACF JSON file exists
     */
    public function acfJsonExists()
    {
        return file_exists($this->get('paths.acf_json'));
    }

    /**
     * Get ACF JSON path
     */
    public function getAcfJsonPath()
    {
        return $this->get('paths.acf_json');
    }

    /**
     * Get component fields list
     */
    public function getComponentFields()
    {
        return $this->get('component_fields', []);
    }

    /**
     * Get skip field names
     */
    public function getSkipFieldNames()
    {
        return $this->get('skip_field_names', []);
    }

    /**
     * Get skip field types
     */
    public function getSkipFieldTypes()
    {
        return $this->get('skip_field_types', ['tab', 'accordion', 'message']);
    }

    /**
     * Get custom template for a field
     */
    public function getCustomTemplate($fieldName)
    {
        $templates = $this->get('custom_templates', []);
        return $templates[$fieldName] ?? null;
    }
}
