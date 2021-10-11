<?php

namespace mvr;

defined( 'ABSPATH' ) or exit;

class Admin {
    function __construct() {
        add_action( 'admin_menu', [$this, 'menu'] );
    }

    public function menu() {
        add_menu_page( 'DM reservation', 'DM reservation', 'manage_options', 'dm-reservation', [$this, 'home_page'], 'dashicons-media-document', 3 );
    }

    public function home_page() {
        var_dump( draw_calendar( 12, 2021, ['controls' => 'ajax_month'] ) );
    }
}