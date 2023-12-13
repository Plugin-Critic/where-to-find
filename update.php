<?php

namespace Where_To_Find;

if (!defined('ABSPATH')) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

class Update {

    public static function get_remote_data() {
        // @TODO: get remote info

    }

    public static function get_plugin_update_data( $plugin_data ) {

        $remote_data = static::get_remote_data();

        // https://developer.wordpress.org/reference/hooks/update_plugins_hostname/#parameters
        return [
            'id' => $plugin_data['PluginURI'] ?? null,
            'slug' => Plugin::get_slug(),
            'version' => $remote_data,
            'url' => $remote_data,
            'package' => $remote_data,  // url for zip file
            'tested' => $remote_data,
            'requires_php' => $remote_data,
        ];
    }

    public static function check_for_updates( $update = null, $plugin_data = null, $plugin_file = null, $locales = null ) {
        if ( $plugin_file !== Plugin::plugin_basename() ) {
            return $update;
        }

        $plugin = static::get_plugin_update_data( $plugin_data );

        return;
    }
}