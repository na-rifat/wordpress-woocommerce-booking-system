<?php
defined( 'ABSPATH' ) or die( 'You can\'t access to this file' );
/**
 * This files contains all important functions for mvr wp plugin
 */

/**
 * Return a css files url
 *
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_cssfile' ) ) {
    function mvr_cssfile( $filename, $deps = [] ) {
        return ['src' => MVR_CSS_URL . "/{$filename}.css", 'version' => mvr_cssversion( $filename ), 'deps' => $deps];
    }
}

/**
 * Return a js files url
 *
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_jsfile' ) ) {
    function mvr_jsfile( $filename, $deps = [] ) {
        return ['src' => MVR_JS_URL . "/{$filename}.js", 'version' => mvr_jsversion( $filename ), 'deps' => $deps];
    }
}

/**
 * Return a image files url
 *
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_imgfile' ) ) {
    function mvr_imgfile( $filename ) {
        return MVR_IMG_URL . "/$filename";
    }
}
/**
 * Return a image files url
 *
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_icofile' ) ) {
    function mvr_icofile( $filename ) {
        return MVR_IMG_URL . "/icons/$filename";
    }
}

/**
 * Get js files version based on date modified
 *
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_jsversion' ) ) {
    function mvr_jsversion( $filename ) {
        return filemtime( convert_path_slash( MVR_PATH . "/assets/js/{$filename}.js" ) );
    }
}
/**
 * Get css files version based on date modified
 *
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_cssversion' ) ) {
    function mvr_cssversion( $filename ) {
        return filemtime( convert_path_slash( MVR_PATH . "/assets/css/{$filename}.css" ) );
    }
}

/**
 * Replaces back slashes with slashes from a files path
 *
 * @param  [type] $path
 * @return void
 */
if ( ! function_exists( 'convert_path_slash' ) ) {
    function convert_path_slash( $path ) {
        return str_replace( "\\", "/", $path );
    }
}

/**
 * Pulls a template from views folder
 *
 * @param  [type] $dir
 * @param  [type] $filename
 * @return void
 */
if ( ! function_exists( 'mvr_template' ) ) {
    function mvr_template( $dir, $filename ) {
        ob_start();
        include convert_path_slash( "{$dir}/views/{$filename}.php" );
        return ob_get_clean();
    }
}

if ( ! function_exists( 'mvr_admin_template' ) ) {
    /**
     * Returns a template for admin panel
     *
     * @param  [type] $dir
     * @param  [type] $filename
     * @return void
     */
    function mvr_admin_template( $dir, $filename ) {
        ob_start();
        include convert_path_slash( "{$dir}/views/{$filename}.php" );
        echo ob_get_clean();
        return;
    }
}

/**
 * get's google recaptcha response
 *
 * @param  [type] $recaptcha
 * @return void
 */
if ( ! function_exists( 'reCaptcha' ) ) {
    function reCaptcha( $recaptcha ) {
        $secret = get_option( 'mvr_captcha_secret' ) ? get_option( 'mvr_captcha_secret' ) : '';
        $ip     = $_SERVER['REMOTE_ADDR'];

        $postvars = array(
            "secret"   => $secret,
            "response" => $recaptcha,
            "remoteip" => $ip,
        );
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch  = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postvars );
        $data = curl_exec( $ch );
        curl_close( $ch );

        return json_decode( $data, true );
    }
}

/**
 * Verifies if a function is okay or not
 *
 * @return void
 */
if ( ! function_exists( 'verify_mvr_captcha' ) ) {
    function verify_mvr_captcha() {
        $recaptcha = $_POST['g-recaptcha-response'];
        $res       = reCaptcha( $recaptcha );
        if ( ! $res['success'] ) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists( 'mvr_ajax' ) ) {
    /**
     * Registers an ajax hook
     *
     * @param  [type] $action
     * @param  array  $func
     * @return void
     */
    function mvr_ajax( $action, $func = [] ) {
        add_action( "wp_ajax_$action", $func );
        add_action( "wp_ajax_nopriv_$action", $func );
    }
}

if ( ! function_exists( 'mvr_var' ) ) {
    /**
     * Returns formatted variable
     *
     * @param  [type]                        $var
     * @return void|string|int|array|mixed
     */
    function mvr_var( $var ) {
        return isset( $_POST[$var] ) && ! empty( $_POST[$var] ) ? $_POST[$var] : '';
    }

    if ( ! function_exists( 'mvr_get_option' ) ) {
        function mvr_get_option( $key ) {
            return stripslashes( get_option( $key ) );
        }
    }
}

if ( ! function_exists( 'array2options' ) ) {
    function array2options( $array ) {
        $result = '';
        foreach ( $array as $item ) {
            $caption = ucwords( $item );
            $result .= "<option value='{$item}'>{$caption}</option";
        }
        return $result;
    }
}

if ( ! function_exists( 'std2array' ) ) {
    function std2array( $std ) {
        return json_decode( json_encode( $std ), true );
    }
}

if ( ! function_exists( 'mvr_compare_table_rows' ) ) {
    function mvr_compare_table_rows( $rows ) {
        $result   = '';
        $packages = [
            'package_free',
            'package_basic',
            'package_facilitator',
            'package_creator',
            'package_enterprise',
        ];

        foreach ( $rows as $key => $row ) {
            if ( strpos( $key, 'package_' ) === FALSE ) {
                continue;
            }

            $result .= sprintf( '<td>%s</td>', $row == 'enabled' ? '<i class="fas fa-check-square"></i>' : '<span class="ico-na">n/a</span>' );
        }

        return $result;
    }
}

if ( ! function_exists( 'mvr_y2embed' ) ) {
    function mvr_y2embed( $string ) {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://www.youtube.com/embed/$2",
            $string
        );

    }
}

// Post views management
function mvr_get_post_view() {
    $count = get_post_meta( get_the_ID(), 'post_views_count', true );
    return "$count views";
}

function mvr_set_post_view() {
    $key     = 'post_views_count';
    $post_id = get_the_ID();
    $count   = (int) get_post_meta( $post_id, $key, true );
    $count++;
    update_post_meta( $post_id, $key, $count );
}

function mvr_posts_column_views( $columns ) {
    $columns['post_views'] = 'Views';
    return $columns;
}

function mvr_posts_custom_column_views( $column ) {
    if ( $column === 'post_views' ) {
        echo mvr_get_post_view();
    }
}

add_filter( 'manage_posts_columns', 'mvr_posts_column_views' );
add_action( 'manage_posts_custom_column', 'mvr_posts_custom_column_views' );

if ( ! function_exists( 'mvr_color_value' ) ) {
    /**
     * Hex encoded color value
     *
     * @param  string   $value
     * @return string
     */
    function mvr_color_value( $value ) {
        if ( strpos( $value, 'value' ) !== FALSE ) {
            $value = str_replace( ')', '', str_replace( '(', '', str_replace( 'value', '', $value ) ) );
            $value = explode( ', ', $value );
            $hex   = sprintf( "#%02x%02x%02x", $value[0], $value[1], $value[2] );

            return $hex;
        } else {
            $hex = $value;
            if ( strlen( $hex ) == 4 ) {
                $hex = '#' . str_replace( '#', '', $hex ) . str_replace( '#', '', $hex );
            }

            return $hex;
        }
    }
}

if ( ! function_exists( 'mvr_brand_logo' ) ) {
    /**
     * Get a image file url
     *
     * @return string
     */
    function mvr_brand_logo() {
        echo mvr_imgfile( 'logo.png' );
    }
}

if ( ! function_exists( 'mvr_annual_price' ) ) {
    function mvr_annual_price( $monthly_price ) {
        return $monthly_price * 12;
    }
}

if ( ! function_exists( 'mvr_monthly_price' ) ) {
    function mvr_monthly_price( $annual_price ) {
        return $annual_price / 12;
    }
}

if ( ! function_exists( 'mvr_cesium_icon' ) ) {
    function mvr_cesium_icon( $width = 50, $height = 50 ) {
        return sprintf(
            '<img src="%s" alt="Cesium icon" style="width: %spx; height: %spx;" />',
            mvr_imgfile( 'cesium_icon.png' ),
            $width,
            $height
        );
    }
}

if ( ! function_exists( 'mvr_osm_icon' ) ) {
    function mvr_osm_icon( $width = 50, $height = 50 ) {
        return sprintf(
            '<img src="%s" alt="OSM icon" style="width: %spx; height: %spx;" />',
            mvr_imgfile( 'osm_icon.png' ),
            $width,
            $height
        );
    }
}

if ( ! function_exists( 'mvr_session' ) ) {
    function mvr_session() {
        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }
    }
}

if ( ! function_exists( 'mvr_unique_username' ) ) {
    function mvr_unique_username( $username ) {

        $username = explode( '@', $username )[0];

        $username = sanitize_user( $username );

        static $i;
        if ( null === $i ) {
            $i = 1;
        } else {
            $i++;
        }
        if ( ! username_exists( $username ) ) {
            return $username;
        }
        $new_username = sprintf( '%s-%s', $username, $i );
        if ( ! username_exists( $new_username ) ) {
            return $new_username;
        } else {
            return call_user_func( __FUNCTION__, $username );
        }
    }
}

if ( ! function_exists( 'mvr_ajax_check' ) ) {
    function mvr_ajax_check() {
        $action = mvr_var( 'action' );
        $nonce  = mvr_var( 'nonce' );

        if ( ! wp_verify_nonce( $nonce, $action ) ) {
            wp_send_json_error(
                [
                    'msg' => 'Invalid token!',
                ]
            );exit;
        }
    }
}

if ( ! function_exists( 'get_mvr_products' ) ) {
    function get_mvr_products( $category ) {
        $result = [];
        $args   = array(
            'post_type'      => 'product',
            'posts_per_page' => 20,
            'product_cat'    => $category,
        );

        $result = new WP_Query( $args );

        wp_reset_query();

        return $result;
    }
}

// if ( ! function_exists( 'print_mvr_products' ) ) {
//     function print_mvr_products( $category ) {
//         $products = get_mvr_products( $category );

//         while ( $products->have_posts() ) {
//             $products->the_post();
//             global $product;

//             // echo '<br /><a href="' . get_permalink() . '">' . woocommerce_get_product_thumbnail() . ' ' . get_the_title() . '</a>';
//         }
//     }
// }

if ( ! function_exists( 'print_mvr_products' ) ) {
    function print_mvr_products( $category ) {
        $products = wc_get_products(
            [
                'category' => [$category],
            ]
        );

        echo '<div class="mvr-product-list" >';

        foreach ( $products as $product ) {
            printf( '<div class="mvr-product" data-id="%s">
                <div class="mvr-product-image">%s</div>
                <div>
                    <div class="mvr-product-title">%s</div>
                    <div class="mvr-product-price">%s %s</div>
                    <div class="mvr-product-quantity">%s</div>
                </div>
            </div>',
                $product->id,
                $product->get_image(),
                $product->get_name(),
                get_woocommerce_currency_symbol(),
                $product->get_display_price(),
                mvr_prod_q( $category, $product->get_meta( 'dm_available' ), $product->get_meta( 'dm_capacity' ) )
            );
        }
        echo '</div>';
    }
}

if ( ! function_exists( 'mvr_prod_q' ) ) {
    function mvr_prod_q( $category, $quantity = 0, $capacity = 0 ) {
        switch ( $category ) {
            case 'hebergement':
                $select = '<select name="select_quantity" class="custom-select">
                <option value="null" disabled="disabled" selected>Desired quantity: </option>
                <option value="0" >None for this day</option>
                %s
                </select>';
                $options = '';

                for ( $i = 1; $i <= $quantity; $i++ ) {
                    $options .= sprintf( '<option value="%d">%d - (Up to %d people)</option>', $i, $i, $i * $capacity );
                }

                $select = sprintf( $select, $options );

                return $select;

            case 'vehicules':
            case 'equipements':
                $select = '<select name="select_quantity" class="custom-select">
                <option value="null" disabled="disabled" selected>Desired quantity: </option>
                <option value="0" >None for this day</option>
                %s
                </select>';
                $options = '';

                for ( $i = 1; $i <= $quantity; $i++ ) {
                    $options .= sprintf( '<option value="%d">%d</option>', $i, $i );
                }

                $select = sprintf( $select, $options );

                return $select;
                break;
            default:return '';
                break;
        }

    }
}