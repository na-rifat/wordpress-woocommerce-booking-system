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
        register_activation_hook( __FILE__, '\mvr\Install::create_guides' );
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
        // Install::create_guides();
        new Frontend();
        new Assets();

        if ( DOING_AJAX ) {
            new Ajax();
        }

        if ( is_admin() ) {
            new Admin();
        }

        new Bookable();


        // wp_insert_term( 'Guides', 'product_cat', array(
        //     'description' => 'Automatically created by DM reservation (Don\'t modify).', // optional
        //     'parent'      => 0,                                                          // optional
        //     'slug'        => 'guides',                                                   // optional
        // ) );

        // $args = array(
        //     'post_author'  => 0,
        //     'post_content' => '',
        //     'post_status'  => "publish", // (Draft | Pending | Publish)
        //     'post_title'   => 'Guides',
        //     'post_parent'  => '',
        //     'post_type'    => "product",
        // );

        // // Create a simple WooCommerce product
        // $post_id = wp_insert_post( $args );

        // // Setting the product type
        // wp_set_object_terms( $post_id, 'simple', 'product_type' );

        // // Setting the product price
        // update_post_meta( $post_id, '_price', 500 );
        // update_post_meta( $post_id, '_regular_price', 500 );

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