<?php

namespace mvr;

defined( 'ABSPATH' ) or exit;

use mvr\Templates;

class Ajax {
    function __construct() {
        $this->load_requests();
        add_action( 'wp_enqueue_scripts', [$this, 'init_nonces'] );
    }

    public static function get_requests() {
        return [
            'get_reservation_form',
            'handle_data_collection',
        ];
    }

    public function load_requests() {
        $requests = self::get_requests();

        foreach ( $requests as $request ) {
            mvr_ajax( $request, [$this, $request] );
        }
    }

    public function init_nonces() {
        $requests = self::get_requests();

        foreach ( $requests as $action ) {
            wp_localize_script( 'mvr-universal-script', 'mvr_nonce', [$action => wp_create_nonce( $action )] );
        }
    }

    function get_reservation_form() {
        mvr_ajax_check();

        wp_send_json_success(
            [
                'form' => Templates::get( 'reservation-form' ),
            ]
        );exit;
    }

    public function handle_data_collection() {
        
    }
}