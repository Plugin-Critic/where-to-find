<?php
/*
Plugin Name: Where To Find
Plugin URI: https://github.com/Plugin-Critic/where-to-find
Description: Where to Find what you are looking for throughout
Author: Steve Putala
Version: 1.0.0
Author URI: https://steveputala.com
Update URI: https://github.com/Plugin-Critic/where-to-find
*/

namespace Where_To_Find;

if (!defined('ABSPATH')) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

// Set up autoloader first
spl_autoload_register( __NAMESPACE__ . '\\autoload' );
function autoload( $class_name ) {
    if ( stripos( $class_name, __NAMESPACE__ . '\\' ) !== 0 ) {
        return;
    }

    $file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . str_replace( [ '\\', '_' ], [ DIRECTORY_SEPARATOR, '-' ], strtolower( $class_name ) ) . '.php';

    if ( file_exists( $file ) ) {
        require_once( $file );
    }
}

add_filter( 'update_plugins_github.com', [ __NAMESPACE__ . '\\update', 'check_for_updates' ], null, 4 );