<?php
/**
 * ACF Panel Generator - Web Interface
 */

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'ACFPanelGenerator\\';
    $base_dir = __DIR__ . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

use ACFPanelGenerator\Config;
use ACFPanelGenerator\Generator;

// Check if config exists
$configPath = __DIR__ . '/config.php';
$configExists = file_exists($configPath);

// Initialize
$config = null;
$error = null;
$results = null;

if ($configExists) {
    try {
        $config = new Config($configPath, __DIR__);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $config) {
    $overwrite = isset($_POST['overwrite']) && $_POST['overwrite'] === '1';
    
    try {
        $generator = new Generator($config);
        $results = $generator->generate($overwrite);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACF Panel Generator</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🎨 ACF Panel Generator</h1>
            <p class="subtitle">Generate panel PHP and SCSS files from ACF flexible content</p>
        </header>

        <?php if (!$configExists): ?>
            <!-- No config file -->
            <div class="alert alert-warning">
                <h3>⚠️ Configuration Required</h3>
                <p>Please create a <code>config.php</code> file by copying <code>config.sample.php</code></p>
                <pre>cp config.sample.php config.php</pre>
                <p>Then edit <code>config.php</code> with your project settings.</p>
            </div>

        <?php elseif ($error): ?>
            <!-- Configuration error -->
            <div class="alert alert-error">
                <h3>❌ Configuration Error</h3>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>

        <?php else: ?>
            <!-- Main interface -->
            
            <?php if ($results): ?>
                <!-- Results -->
                <div class="results <?php echo $results['success'] ? 'results-success' : 'results-error'; ?>">
                    <?php if ($results['success']): ?>
                        <h3>✓ Generation Complete!</h3>
                        <div class="stats">
                            <span class="stat stat-success">Created: <?php echo $results['created']; ?></span>
                            <span class="stat stat-warning">Skipped: <?php echo $results['skipped']; ?></span>
                        </div>
                    <?php else: ?>
                        <h3>❌ Generation Failed</h3>
                    <?php endif; ?>

                    <?php if (!empty($results['errors'])): ?>
                        <div class="messages">
                            <?php foreach ($results['errors'] as $message): ?>
                                <div class="message message-error"><?php echo htmlspecialchars($message); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($results['messages'])): ?>
                        <details class="log">
                            <summary>View detailed log (<?php echo count($results['messages']); ?> items)</summary>
                            <div class="log-content">
                                <?php foreach ($results['messages'] as $message): ?>
                                    <div class="log-item"><?php echo htmlspecialchars($message); ?></div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php endif; ?>

                    <div class="actions">
                        <a href="?" class="button">← Back</a>
                    </div>
                </div>

            <?php else: ?>
                <!-- Configuration info -->
                <div class="card">
                    <h3>📋 Current Configuration</h3>
                    
                    <div class="config-info">
                        <div class="config-item">
                            <strong>ACF JSON File:</strong>
                            <code><?php echo htmlspecialchars($config->get('paths.acf_json')); ?></code>
                            <?php if ($config->acfJsonExists()): ?>
                                <span class="badge badge-success">✓ Found</span>
                            <?php else: ?>
                                <span class="badge badge-error">✗ Not Found</span>
                            <?php endif; ?>
                        </div>

                        <div class="config-item">
                            <strong>PHP Output:</strong>
                            <code><?php echo htmlspecialchars($config->get('paths.php_output')); ?></code>
                        </div>

                        <div class="config-item">
                            <strong>SCSS Panels:</strong>
                            <code><?php echo htmlspecialchars($config->get('paths.scss_panels')); ?></code>
                        </div>

                        <div class="config-item">
                            <strong>SCSS Components:</strong>
                            <code><?php echo htmlspecialchars($config->get('paths.scss_components')); ?></code>
                        </div>

                        <div class="config-item">
                            <strong>Component Fields:</strong>
                            <div class="badge-list">
                                <?php foreach ($config->getComponentFields() as $field): ?>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($field); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card card-actions">
                    <h3>🚀 Generate Files</h3>
                    
                    <form method="POST" id="generateForm">
                        <input type="hidden" name="action" value="generate">
                        
                        <div class="button-group">
                            <button type="submit" name="overwrite" value="0" class="button button-primary">
                                Generate New Files Only
                            </button>
                            
                            <button type="submit" name="overwrite" value="1" class="button button-danger" 
                                    onclick="return confirm('This will DELETE all existing panel and component files and regenerate them. Are you sure?')">
                                🗑️ Delete & Regenerate All
                            </button>
                        </div>

                        <p class="help-text">
                            <strong>Generate New Files Only:</strong> Creates only missing files, preserves existing ones.<br>
                            <strong>Delete & Regenerate All:</strong> Removes all panels and components, then creates fresh files.
                        </p>
                    </form>
                </div>

                <!-- Documentation -->
                <div class="card card-docs">
                    <h3>📖 How It Works</h3>
                    <ol>
                        <li>Reads your ACF JSON file for flexible content layouts</li>
                        <li>Generates a PHP panel file for each layout</li>
                        <li>Creates matching SCSS files with nested structure</li>
                        <li>Generates component SCSS files for fields in your component list</li>
                        <li>Creates index files: <code>_panels.scss</code> and <code>_components.scss</code></li>
                    </ol>

                    <h4>After Generation:</h4>
                    <p>Import the SCSS index files in your main stylesheet:</p>
                    <pre>@import 'components'; // Base component styles
@import 'panels';     // Panel-specific overrides</pre>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <footer class="footer">
            <p>ACF Panel Generator v1.0.0</p>
        </footer>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
