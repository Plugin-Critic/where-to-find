<?php

namespace Where_To_Find;

if (!defined('ABSPATH')) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

class Plugin {
    /** @var array $cache Cached results of method calls */
    protected static array $cache = [];

    /**
     * @return string find absolute path to the plugin file based on directory name
     */
    protected static function find_plugin_file() {
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $slug = basename( __DIR__ );

        $plugins = get_plugins();

        foreach ( $plugins as $name => $data ) {
            if ( stripos( $name, $slug ) === 0 ) {
                return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $name;
            }
        }

        trigger_error( 'Unable to find plugin in registered plugins', E_USER_WARNING );

        if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . $slug . '.php' ) ) {
            return __DIR__ . DIRECTORY_SEPARATOR . $slug . '.php';
        }

        trigger_error( 'Unable to determine plugin name', E_USER_ERROR );
    }

    /**
     * Get the plugin file path, or set it on the first time this method is requested
     * @param string $file Absolute path to the plugin file
     * @return mixed|string
     */
    public static function plugin_file( $file = null ) {
        return static::$cache[ __METHOD__ ] ??= ( $file ?? static::find_plugin_file() );
    }

    /**
     * The relative path to the plugin file from WP_PLUGIN_DIR
     * @return string
     */
    public static function plugin_basename(): string
    {
        return static::$cache[ __METHOD__ ] ??= plugin_basename( static::plugin_file() );
    }

    /**
     * The plugin slug, usually the plugin directory name
     * @return string
     */
    public static function get_slug(): string
    {
        return static::$cache[ __METHOD__ ] ??= explode( DIRECTORY_SEPARATOR, static::plugin_basename(), 2 )[0];
    }

    /**
     * Get the filesystem directory path (with trailing slash) for the plugin.
     * @return string the filesystem path of the directory that contains the plugin.
     */
    static function plugin_dir_path() {
        return static::$cache[ __METHOD__ ] ??= plugin_dir_path( static::plugin_file() );
    }

    /**
     * Get the URL directory path (with trailing slash) for the plugin.
     * @return string the URL path of the directory that contains the plugin.
     */
    static function plugin_dir_url() {
        return static::$cache[ __METHOD__ ] ??= plugin_dir_url( static::plugin_file() );
    }

    /**
     * Plugin data retrieved from get_plugin_data()
     * @return array
     */
    public static function get_plugin_data(): array
    {
        if ( isset( static::$cache[ __METHOD__ ] ) ) {
            return static::$cache[ __METHOD__ ];
        }

        include_once ABSPATH . '/wp-admin/includes/plugin.php';
        return static::$cache[ __METHOD__ ] = get_plugin_data( static::plugin_file() );
    }

    /**
     * The plugin version number
     * @return string|null
     */
    public static function get_version(): string|null
    {
        return static::$cache[ __METHOD__ ] ??= static::get_plugin_data()['Version'] ?? null;
    }

    /**
     * Get config data for the plugin
     * @return array
     */
    public static function get_config(): array
    {
        return static::$cache[ __METHOD__ ] ??= include __DIR__ . DIRECTORY_SEPARATOR . '.config.php';
    }
}
