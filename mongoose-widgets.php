<?php
/**
 * Plugin Name: Mongoose Widgets
 * Description: A library of custom Elementor widgets that can be individually enabled/disabled from an admin dashboard.
 * Version: 1.0.0
 * Author: Mongoose
 * Text Domain: mongoose-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'MONGOOSE_WIDGETS_VERSION', '1.0.0' );
define( 'MONGOOSE_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'MONGOOSE_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

require_once MONGOOSE_WIDGETS_PATH . 'vendor/autoload.php';
require_once MONGOOSE_WIDGETS_PATH . 'includes/class-mongoose-widgets-registry.php';
require_once MONGOOSE_WIDGETS_PATH . 'includes/class-mongoose-widgets.php';

// GitHub-based plugin updates.
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$mongoose_widgets_updater = PucFactory::buildUpdateChecker(
    'https://github.com/tyler-abovedesign/Mongoose-Widgets/',
    __FILE__,
    'mongoose-widgets'
);
$mongoose_widgets_updater->setBranch( 'main' );

Mongoose_Widgets::instance();
