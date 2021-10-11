<?php

namespace mvr;

defined( 'ABSPATH' ) or exit;

class Shortcodes {
    function __construct() {

    }

    public function load_shortcodes() {
        $shortcodes = [

        ];

        foreach ( $shortcodes as $shortfunc ) {
            add_shortcode( $shortfunc, [$this, $shortfunc] );
        }
    }
}