<?php
/**
 * Plugin Name:       DMV reservation system
 * Plugin URI:        https://rafalotech.com/wp/plugins/rmv
 * Description:       Wordpress woocommerce integrated reservation management plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rafalo tech
 * Author URI:        https://rafalotech.com
 * License:           GPL v2 or later
 * Text Domain:       mvr
 */

namespace mvr;

defined( 'ABSPATH' ) or exit;

include 'vendor/autoload.php';

class mvr {
    function __construct() {
        self::init_constants();
        add_action( 'plugins_loaded', [$this, 'init_classes'] );
        add_action( 'wp_head', [$this, 'p_test'] );
    }

    public static function init_constants() {
        define( 'MVR_PATH', __DIR__ );
        define( 'MVR_URL', plugins_url( '', __FILE__ ) );
        define( 'MVR_ASSETS_PATH', __DIR__ . '/assets' );
        define( 'MVR_ASSETS_URL', plugins_url( 'assets/', __FILE__ ) );
        define( 'MVR_IMG_PATH', __DIR__ . '/assets/img' );
        define( 'MVR_IMG_URL', plugins_url( 'assets/img/', __FILE__ ) );
        define( 'MVR_CSS_DIR', __DIR__ . '/assets/css' );
        define( 'MVR_CSS_URL', plugins_url( 'assets/css/', __FILE__ ) );
        define( 'MVR_JS_DIR', __DIR__ . '/assets/js' );
        define( 'MVR_JS_URL', plugins_url( 'assets/js/', __FILE__ ) );
        define( 'MVR_TEMPLATES_DIR', __DIR__ . '/templates/' );
        define( 'MVR', __DIR__ . 'mvr' );
    }

    public function init_classes() {
        new Frontend();
        new Assets();

        if ( DOING_AJAX ) {
            new Ajax();
        }

        if ( is_admin() ) {
            new Admin();
        }

        new Bookable();

    }

    public static function init_self() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }
    }

    public function p_test() {
        // var_dump( wc_get_products(
        //     [
        //         'category' => ['hebergement'],
        //     ]
        // )[0]->get_name() );exit;
    }
}

mvr::init_self();