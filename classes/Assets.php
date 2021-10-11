<?php

namespace mvr;

defined( 'ABSPATH' ) or exit;

use mvr\Helper;

class Assets {
    function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'load'] );
        add_action( 'admin_enqueue_scripts', [$this, 'load'] );
    }

    public static function get_localizations() {
        return [
            'universal' => [
                'site_url'  => site_url(),
                'admin_url' => admin_url(),
                'ajax_url'  => admin_url( 'admin-ajax.php' ),
            ],
        ];
    }

    public static function get_script_deps() {
        return [
            'universal' => ['jquery'],
        ];
    }

    public static function get_style_deps() {
        return [

        ];
    }

    public static function load() {
        $scripts       = Helper::get_file_name_list( MVR_JS_DIR );
        $styles        = Helper::get_file_name_list( MVR_CSS_DIR );
        $localizations = self::get_localizations();

        $script_deps = self::get_script_deps();
        $style_deps  = self::get_style_deps();

        // Scripts
        foreach ( $scripts as $script ) {
            wp_enqueue_script( "mvr-$script-script", MVR_JS_URL . "$script.js", $script_deps[$script], filemtime( MVR_JS_DIR . "$script.js" ) );
        }

        // Styles
        foreach ( $styles as $style ) {
            wp_enqueue_style( "mvr-$style-style", MVR_CSS_URL . "$style.css", $style_deps[$style], filemtime( MVR_CSS_DIR . "$style.css" ) );
        }

        // Localization
        foreach ( $localizations as $handle => $vars ) {
            wp_localize_script( "mvr-$handle-script", 'mvr', $vars );
        }

    }
}