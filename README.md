# Arsol WP Snippets

A WordPress plugin to add custom code snippets and enhancements with a safe mode feature for troubleshooting.

## Description

Arsol WP Snippets allows you to easily manage and load custom PHP, CSS, and JavaScript snippets in your WordPress site. The plugin includes a safe mode feature that helps you troubleshoot fatal errors by temporarily disabling snippet loading while maintaining your selections.

## Features

- Load custom PHP snippets
- Add custom CSS styles
- Include JavaScript files
- Safe mode for troubleshooting
- Easy-to-use admin interface
- Context-aware loading (admin/frontend)
- Position control for JavaScript files
- Loading order control for all file types
- Dependency management for CSS and JS files
- Flexible filter system for custom integration
- Conditional loading based on various criteria

## Safe Mode

The plugin includes a safe mode feature that can be enabled by adding the following constant to your wp-config.php file:

```php
define('ARSOL_WP_SNIPPETS_SAFE_MODE', true);
```

When safe mode is enabled:
- All selected snippets remain visible in the admin interface
- No snippets will be loaded (PHP, CSS, or JavaScript)
- A warning notice appears in the admin interface
- You can still manage which files are selected
- Perfect for troubleshooting fatal errors

To disable safe mode, set the constant to false:
```php
define('ARSOL_WP_SNIPPETS_SAFE_MODE', false);
```

## Installation

1. Upload the `arsol-wp-snippets` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Arsol WP Snippets' in the admin menu to start adding snippets

## Usage

### Adding PHP Snippets

1. Navigate to Arsol WP Snippets in the admin menu
2. Select the PHP snippets you want to include
3. Configure loading order (default: 10)
4. Save your changes

### Adding CSS Styles

1. Navigate to Arsol WP Snippets in the admin menu
2. Select the CSS files you want to include
3. Configure loading order (default: 10)
4. Add any required dependencies
5. Save your changes

### Adding JavaScript

1. Navigate to Arsol WP Snippets in the admin menu
2. Select the JavaScript files you want to include
3. Choose whether to load in header or footer
4. Configure loading order (default: 10)
5. Add any required dependencies
6. Save your changes

## Advanced Configuration

### Loading Order

The loading order determines when your snippets are loaded relative to other scripts and styles. Lower numbers load earlier, higher numbers load later.

```php
// Example of setting loading order in a filter
add_filter('arsol_wp_snippets_css_addon_files', function($addons) {
    $addons['my-custom-style'] = array(
        'name' => 'My Custom Style',
        'file' => 'path/to/style.css',
        'loading_order' => 5, // Loads earlier than default (10)
        'context' => 'frontend'
    );
    return $addons;
});
```

### Dependencies

For CSS and JavaScript files, you can specify dependencies that must be loaded before your snippet:

```php
// Example of adding dependencies
add_filter('arsol_wp_snippets_js_addon_files', function($addons) {
    $addons['my-custom-script'] = array(
        'name' => 'My Custom Script',
        'file' => 'path/to/script.js',
        'dependencies' => array('jquery', 'wp-api'),
        'loading_order' => 20,
        'position' => 'footer'
    );
    return $addons;
});
```

### Conditional Loading

The plugin supports conditional loading based on various criteria. Here are some examples:

```php
// Example of user role-based loading
add_filter('arsol_wp_snippets_js_addon_files', function($addons) {
    if (current_user_can('administrator')) {
        $addons['admin-only-script'] = array(
            'name' => 'Admin Only Script',
            'file' => 'path/to/admin-script.js',
            'loading_order' => 10
        );
    }
    return $addons;
});

// Example of mobile device detection
add_filter('arsol_wp_snippets_css_addon_files', function($addons) {
    if (wp_is_mobile()) {
        $addons['mobile-styles'] = array(
            'name' => 'Mobile Styles',
            'file' => 'path/to/mobile.css',
            'loading_order' => 5
        );
    }
    return $addons;
});

// Example of time-based loading
add_filter('arsol_wp_snippets_php_addon_files', function($addons) {
    $hour = (int) current_time('G');
    if ($hour >= 9 && $hour < 17) {
        $addons['business-hours'] = array(
            'name' => 'Business Hours Code',
            'file' => 'path/to/business-hours.php',
            'loading_order' => 10
        );
    }
    return $addons;
});

// Example of user login status
add_filter('arsol_wp_snippets_js_addon_files', function($addons) {
    if (is_user_logged_in()) {
        $addons['logged-in-script'] = array(
            'name' => 'Logged In User Script',
            'file' => 'path/to/logged-in.js',
            'loading_order' => 15
        );
    }
    return $addons;
});
```

### Filter Hooks

The plugin provides several filter hooks for custom integration:

```php
// PHP Snippets
add_filter('arsol_wp_snippets_php_addon_files', 'your_callback_function');

// CSS Files
add_filter('arsol_wp_snippets_css_addon_files', 'your_callback_function');

// JavaScript Files
add_filter('arsol_wp_snippets_js_addon_files', 'your_callback_function');

// Action when a PHP snippet is loaded
add_action('arsol_wp_snippets_loaded_php_addon', 'your_callback_function', 10, 2);

// Action when a CSS file is loaded
add_action('arsol_wp_snippets_loaded_css_addon', 'your_callback_function', 10, 2);

// Action when a JS file is loaded
add_action('arsol_wp_snippets_loaded_js_addon', 'your_callback_function', 10, 2);
```

### Asset Versioning

The plugin's own assets (CSS and JavaScript files) are versioned using the plugin version number. For third-party files that are modified through the plugin's filter system, versioning is optional. Note that versioning only applies to CSS and JavaScript files - PHP files are always loaded fresh as they are executed server-side.

When no version is specified for a CSS or JavaScript file, the plugin will automatically use the file's last modification time (`filemtime()`) as the version number. This means:
- Files will be cached until they are modified
- When a file is modified, the timestamp changes, forcing a cache refresh
- No manual version management is needed for files that should update on modification

```php
// Example of a CSS file that will be cached (with version)
add_filter('arsol_wp_snippets_css_addon_files', function($addons) {
    $addons['my-cached-style'] = array(
        'name' => 'My Cached Style',
        'file' => 'path/to/style.css',
        'version' => '1.0.0', // This file will be cached with this version
        'context' => 'frontend'
    );
    return $addons;
});

// Example of a CSS file that will update when modified
add_filter('arsol_wp_snippets_css_addon_files', function($addons) {
    $addons['my-fresh-style'] = array(
        'name' => 'My Fresh Style',
        'file' => 'path/to/style.css',
        'context' => 'frontend'
        // No version specified, will use filemtime() as version
    );
    return $addons;
});

// Example of a PHP file (versioning not applicable)
add_filter('arsol_wp_snippets_php_addon_files', function($addons) {
    $addons['my-php-snippet'] = array(
        'name' => 'My PHP Snippet',
        'file' => 'path/to/snippet.php',
        'context' => 'frontend'
        // Version parameter is ignored for PHP files
    );
    return $addons;
});
```

Note: The plugin's own assets (located in the `assets` directory) will always use the plugin's version number for caching. This behavior cannot be modified.

## Troubleshooting

If you encounter any issues:

1. Enable safe mode by adding `define('ARSOL_WP_SNIPPETS_SAFE_MODE', true);` to wp-config.php
2. Visit the Arsol WP Snippets admin page to see which files are selected
3. Uncheck problematic files
4. Disable safe mode by setting the constant to false
5. Save your changes

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher

## Changelog

### Version 0.0.14
- Improved asset versioning system
  - Removed automatic versioning of assets
  - Added opt-in versioning for individual files
  - Files can now specify their own version number
  - Default behavior is to not cache files
  - Better handling of third-party file modifications
- Fixed CSS and JS file loading issues
  - Corrected option structure handling for enabled files
  - Removed duplicate loading methods to prevent conflicts
  - Added comprehensive debug logging for troubleshooting
- Improved file loading reliability
  - Added proper file existence checks
  - Enhanced context-aware loading (frontend/admin)
  - Better handling of dependencies and loading order
- Added missing file flag functionality
  - Files that don't exist are now properly skipped
  - Added error logging for missing files
  - Improved error handling for invalid file paths
- Enhanced safe mode functionality
  - Added debug logging for safe mode status
  - Improved safe mode checks across all file types
  - Better handling of safe mode transitions
- Added duplicate file detection
  - Prevents loading the same file multiple times
  - Shows admin notice for duplicate files
  - Logs duplicate file attempts
  - Works across all file types (PHP, CSS, JS)
- Fixed CSS and JS file loading issues
  - Corrected option structure handling for enabled files
  - Removed duplicate loading methods to prevent conflicts
  - Added comprehensive debug logging for troubleshooting
- Improved file loading reliability
  - Added proper file existence checks
  - Enhanced context-aware loading (frontend/admin)
  - Better handling of dependencies and loading order
- Added missing file flag functionality
  - Files that don't exist are now properly skipped
  - Added error logging for missing files
  - Improved error handling for invalid file paths
- Enhanced safe mode functionality
  - Added debug logging for safe mode status
  - Improved safe mode checks across all file types
  - Better handling of safe mode transitions


### Version 0.0.13
- Updated plugin header with GitHub repository links
- Added welcome message to admin settings page
- Made plugin URL clickable in WordPress plugin listing
- Added links to GitHub repository and packet template
- Improved documentation with better examples

### Version 0.0.12
- Added loading order control for all file types
- Added dependency management for CSS and JS files
- Enhanced filter system for better integration
- Improved admin interface with loading order display
- Added timing categories (Early, Default, Late, Very Late)

### Version 0.0.11
- Added safe mode feature
- Improved file loading logic
- Added admin notifications
- Enhanced error handling

### Version 0.0.1 - 0.0.10
- Initial plugin development
- Basic snippet management functionality
- Admin interface implementation
- File loading system setup


## License

This plugin is licensed under the GPL v2 or later.

## Author

Taf Makura