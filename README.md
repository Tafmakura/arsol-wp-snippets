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
3. Save your changes

### Adding CSS Styles

1. Navigate to Arsol WP Snippets in the admin menu
2. Select the CSS files you want to include
3. Save your changes

### Adding JavaScript

1. Navigate to Arsol WP Snippets in the admin menu
2. Select the JavaScript files you want to include
3. Choose whether to load in header or footer
4. Save your changes

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

### 1.0.9
- Added safe mode feature
- Improved file loading logic
- Added admin notifications
- Enhanced error handling

## License

This plugin is licensed under the GPL v2 or later.

## Author

Taf Makura