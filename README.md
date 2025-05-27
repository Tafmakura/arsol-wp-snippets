# Arsol WP Snippets

## Overview
The **Arsol WP Snippets Packet System** is a cool WordPress plugin that transforms how you manage custom code snippets and enhancements. Unlike traditional snippet plugins that store code in your database, our system uses a file-based approach that's more secure, maintainable, and flexible.

### Why File-Based Snippets?
Traditional snippet plugins store your code in the WordPress database, which presents several challenges:
- Database bloat and potential performance issues
- Difficulty in version control and code review
- Limited ability to organize and structure code
- Risk of code loss during database operations
- Challenges in migrating between environments

Our file-based approach offers significant advantages:
- Better organization with separate files for different functionalities
- Easy version control and code review through Git
- Improved performance by reducing database queries
- Simple backup and migration process
- Better code organization and maintainability

### Plugin Distribution & Version Control
Our snippet packets are packaged as standard WordPress plugins, offering powerful distribution and version control capabilities:
- Distribute your snippet packets as standalone WordPress plugins
- Install and update through the WordPress plugin directory or your own repository
- Version control your snippets using Git or any other version control system
- Share your snippet packets with clients or the community
- Automatic updates through WordPress's built-in update system
- Maintain different versions of your snippets for different environments
- Track changes and roll back when needed
- Package related snippets together for easy distribution

### Code Portability & Reusability
One of the biggest advantages of our system is the ability to easily reuse code from various sources:
- Copy and paste snippets directly from ChatGPT, Stack Overflow, or other plugins
- No need to modify code to work with our system - just drop it in the appropriate folder
- Seamlessly integrate code from your favorite plugins
- Share snippets between projects with simple file copying
- Maintain your code library in your preferred IDE

### Theme Integration Made Simple
Don't want to create standalone snippet packets? No problem! Our system offers seamless theme integration:
- Create a `arsol-wp-snippets` folder in your theme
- Drop any PHP, CSS, or JS files into the appropriate  `/css`,`js` or `php` subfolders and you're good to go. Your snippets are automatically detected and available in the dashboard
- Enable/disable added snippets in your dashboard
- Perfect for theme-specific customizations
- No need to modify your theme's functions.php or erite custome code

### Beyond Theme's functions.php
While many developers use their theme's `functions.php` file for custom code, this approach has limitations:
- Single file becomes unwieldy as code grows
- Code is tied to the theme, making it difficult to switch themes
- No easy way to enable/disable specific functions
- Limited organization capabilities

The Arsol WP Snippets Packet System solves these problems by allowing you to:
- Create multiple function files organized by purpose
- Include CSS and JS files alongside your PHP code
- Enable/disable individual snippets through a user-friendly interface
- Maintain code independently of your theme
- Organize code into logical packets that can be nested and managed separately
- Write and edit code in your favorite IDE with full syntax highlighting and debugging support

### Advanced Features
- **Safe Mode**: Troubleshoot issues by temporarily disabling snippets
- **Nested Packets**: Create hierarchical organization of related snippets
- **Context-Aware Loading**: Load snippets only where needed (frontend, admin, or both)
- **Version Control Ready**: Perfect for Git-based workflows
- **Performance Optimized**: Load only what you need, when you need it
- **IDE Support**: Write and edit code in your preferred development environment
- **Theme Integration**: Use snippets directly within your theme structure or as standalone packets that operate like plugins
- **Code Portability**: Easily reuse code from any source

A WordPress plugin to add custom code snippets and enhancements with a safe mode feature for troubleshooting.

## Description

Arsol WP Snippets allows you to easily manage and load custom PHP, CSS, and JavaScript snippets in your WordPress site. The plugin includes a safe mode feature that helps you troubleshoot fatal errors by temporarily disabling snippet loading while maintaining your selections.

## Features

- Load custom PHP snippets
- Automatic or manual version control (cache busting)
- Add custom CSS styles
- Include JavaScript files
- Safe mode for troubleshooting
- Easy-to-use admin interface
- Context-aware loading (admin/frontend)
- Position control for JavaScript files (header/footer)
- Priority control for all file types
- Loading order control for all file types
- Dependency management
- Theme support (add files to theme or child theme for automatic detection)
- PHP based conditional loading based on various criteria

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

### Priority and Loading Order

The plugin provides two ways to control the execution order of your snippets:

1. **Priority**: Controls when your snippet's hook is executed relative to other WordPress hooks
   - PHP snippets hook into `init`
   - CSS files hook into `wp_enqueue_scripts` (frontend) or `admin_enqueue_scripts` (admin)
     - This is WordPress's standard way of enqueuing both styles and scripts
     - CSS files use `wp_enqueue_style()` within these hooks
     - JS files use `wp_enqueue_script()` within these hooks
   - Lower priority numbers execute earlier in the WordPress hook
   - Default priority is 10 (standard WordPress default)

2. **Loading Order**: Controls the order of snippets with the same priority
   - Only applies when multiple snippets have the same priority
   - Lower numbers load earlier within the same priority level
   - Default loading order is 10

```php
// Example of setting priority and loading order in a filter
add_filter('arsol_wp_snippets_php_addon_files', function($addons) {
    $addons['my-custom-snippet'] = array(
        'name' => 'My Custom Snippet',
        'file' => 'path/to/snippet.php',
        'priority' => 20,    // Will run at priority 20 of the init hook
        'loading_order' => 5 // Will load before other snippets with priority 20
    );
    return $addons;
});
```

#### How Priority Works
- Lower priority numbers execute earlier in the WordPress hook
- Default priority is 10 (standard WordPress default)
- Files with the same priority are sorted by loading order
- Files with the same priority and loading order maintain their original order

#### WordPress Hook Integration
The plugin integrates with WordPress hooks in the following way:

1. **PHP Snippets**:
   - Hook: `init`
   - Priority: Set by the snippet's priority value
   - Context: Global (runs in both admin and frontend)

2. **CSS Files**:
   - Frontend Hook: `wp_enqueue_scripts`
     - Uses `wp_enqueue_style()` to register and enqueue styles
     - This is WordPress's standard way of adding styles
   - Admin Hook: `admin_enqueue_scripts`
     - Uses `wp_enqueue_style()` to register and enqueue styles
   - Priority: Set by the file's priority value
   - Context: Frontend or Admin (based on context setting)

3. **JavaScript Files**:
   - Frontend Hook: `wp_enqueue_scripts`
     - Uses `wp_enqueue_script()` to register and enqueue scripts
     - This is WordPress's standard way of adding scripts
   - Admin Hook: `admin_enqueue_scripts`
     - Uses `wp_enqueue_script()` to register and enqueue scripts
   - Priority: Set by the file's priority value
   - Context: Frontend or Admin (based on context setting)
   - Position: Header or Footer (based on position setting)

Example of hook timing:
```php
// This PHP snippet will run at priority 5 of the init hook
add_filter('arsol_wp_snippets_php_addon_files', function($addons) {
    $addons['early-init'] = array(
        'file' => 'path/to/early.php',
        'priority' => 5  // Runs early in init
    );
    return $addons;
});

// This CSS file will load at priority 20 of wp_enqueue_scripts
add_filter('arsol_wp_snippets_css_addon_files', function($addons) {
    $addons['late-style'] = array(
        'file' => 'path/to/style.css',
        'priority' => 20,  // Loads late in wp_enqueue_scripts
        'context' => 'frontend'
    );
    return $addons;
});
```

Note: While there are separate functions for enqueuing styles (`wp_enqueue_style()`) and scripts (`wp_enqueue_script()`), WordPress uses the same hooks (`wp_enqueue_scripts` and `admin_enqueue_scripts`) for both. This is by design and follows WordPress best practices for asset management.

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

For third-party files that are modified through the plugin's filter system, versioning is optional. Note that versioning only applies to CSS and JavaScript files - PHP files are always loaded fresh as they are executed server-side.

When no version is specified for a CSS or JavaScript file, the plugin will automatically use the file's last modification time (`filemtime()`) as the version number. This means:
- Files will be cached until they are modified
- When a file is modified, the timestamp changes, forcing a cache refresh
- No manual version management is needed for files that should update on modification

This versioning system applies to both plugin and theme assets. Theme assets are automatically detected and versioned using their file modification time.

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

### Theme Support

The plugin includes built-in support for theme assets. To use this feature:

1. Create an `arsol-wp-snippets` directory in your theme
2. Add subdirectories for different file types:
   - `css/` for CSS files
   - `js/` for JavaScript files
   - `php/` for PHP snippets

Example theme structure:
```
your-theme/
├── arsol-wp-snippets/
│   ├── css/
│   │   ├── custom-styles.css
│   │   └── mobile-styles.css
│   ├── js/
│   │   ├── custom-scripts.js
│   │   └── mobile-scripts.js
│   └── php/
│       ├── custom-functions.php
│       └── theme-hooks.php
```

Theme assets are automatically:
- Detected and registered
- Versioned using file modification time
- Loaded in the correct context (frontend/admin)
- Positioned correctly (header/footer for JS)
- Available in the plugin's admin interface

The plugin checks both child theme and parent theme directories, with child theme files taking precedence. All theme assets use the same versioning system as plugin assets:
- Files without an explicit version use their modification time
- Files with an explicit version use that version
- PHP files are always loaded fresh

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher


## License

This plugin is licensed under the GPL v2 or later.


## Creating Custom Snippet Packets

You can create your own snippet packets by downloading and using this template repository: https://github.com/Tafmakura/arsol-wps-packet-example

## Development and Debugging

### Debug Mode
For development and troubleshooting:
1. Enable WordPress debug mode in wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```
2. Check the debug.log file for detailed error messages and file paths
3. Enable safe mode
4. Visit the Arsol WP Snippets admin page to see which files are selected
5. Uncheck problematic files
6. Disable safe mode by setting the constant to false
7. Save your changes


### File Placement
- Place your snippets in the appropriate directories before registering them
- Use absolute paths for PHP files
- Use URL paths for CSS and JavaScript files
- Ensure all dependencies are available before loading

### Common Issues
1. **File Not Found**:
   - Check file paths in your filter functions
   - Verify file permissions
   - Ensure files are in the correct directories

2. **Loading Order Issues**:
   - Adjust loading_order values
   - Check dependencies are properly declared
   - Verify context settings

3. **Version Conflicts**:
   - Clear browser cache
   - Check version numbers in filter functions
   - Verify file modification times

## Best Practices

### File Organization
- Keep related snippets together
- Use descriptive file names
- Follow WordPress coding standards
- Document your code

### Performance
- Use appropriate loading contexts
- Set proper loading orders
- Declare dependencies correctly
- Version files appropriately

### Security
- Validate all inputs
- Sanitize outputs
- Use WordPress security functions
- Follow WordPress coding standards

### Maintenance
- Keep snippets up to date
- Remove unused code
- Document changes
- Test thoroughly


## Changelog

### 0.0.14
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
- Added priority control for all file types (PHP, CSS, JS)
  - Improved sorting mechanism to handle priority and loading order
  - Added debug logging for file registration and sorting
  - Fixed issue with file loading order in admin context
  - Enhanced documentation for priority and loading order features

### 0.0.13
- Updated plugin header with GitHub repository links
- Added welcome message to admin settings page
- Made plugin URL clickable in WordPress plugin listing
- Added links to GitHub repository and packet template
- Improved documentation with better examples

### 0.0.12
- Added loading order control for all file types
- Added dependency management for CSS and JS files
- Enhanced filter system for better integration
- Improved admin interface with loading order display
- Added timing categories (Early, Default, Late, Very Late)

### 0.0.11
- Added safe mode feature
- Improved file loading logic
- Added admin notifications
- Enhanced error handling

### 0.0.1 - 0.0.10
- Initial plugin development
- Basic snippet management functionality
- Admin interface implementation
- File loading system setup

