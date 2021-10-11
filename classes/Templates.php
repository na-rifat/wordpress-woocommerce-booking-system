<?php

namespace mvr;

defined( 'ABSPATH' ) or exit;

class Templates {
    function __construct() {

    }

    public static function get( $template_name ) {
        ob_start();
        include MVR_TEMPLATES_DIR . $template_name . '.php';
        return ob_get_clean();
    }

    public static function print( $template_name ) {
        echo self::get( $template_name );
    }
}