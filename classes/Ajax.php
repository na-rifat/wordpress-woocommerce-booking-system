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
            'get_ajax_calendar',
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
        $actions  = [];

        foreach ( $requests as $action ) {
            $actions[$action] = wp_create_nonce( $action );
            // wp_localize_script( 'mvr-universal-script', 'mvr_nonce', [$action => wp_create_nonce( $action )] );
        }
        wp_localize_script( 'mvr-universal-script', 'mvr_nonce', $actions );
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
        mvr_ajax_check();

        $data  = mvr_var( 'data' );
        $items = [];

        global $woocommerce;
        $woocommerce->cart->empty_cart();
        $guide_id = wc_get_products(
            [
                'category' => 'guides',
            ]
        )[0]->id;

        $service_count = 0;
        $discounted    = false;
        foreach ( $data as $single_day ) {
            $items[$single_day['date']] = [
                'person_count'  => $single_day['person'],
                'guide_count'   => $single_day['guide'],
                'accommodation' => $single_day['accommodation'],
                'vehicles'      => $single_day['vehicles'],
                'equipements'   => $single_day['equipements'],
            ];

            // WC()->cart->empty_cart();

            foreach ( $items[$single_day['date']]['accommodation'] as $accommodation ) {
                WC()->cart->add_to_cart( $accommodation['id'], $accommodation['quantity'] );
            }

            foreach ( $items[$single_day['date']]['vehicles'] as $vehicle ) {
                WC()->cart->add_to_cart( $vehicle['id'], $vehicle['quantity'] );
            }

            foreach ( $items[$single_day['date']]['equpements'] as $equipement ) {
                WC()->cart->add_to_cart( $vehicle['id'], $equipement['quantity'] );
            }

            WC()->cart->add_to_cart( $guide_id, $single_day['guide'] );

            $service_count++;
        }

        // if ( $service_count >= 3 && ! $discounted ) {
        //     $discount = WC()->cart->get_subtotal() * 0.15;

        //     WC()->cart->add_fee( 'Discount 15%', -$discount );
        //     $discounted = true;
        // }
        wp_send_json_success(
            [
                // 'invoice' => Templates::get( 'woocommerce/checkout' ),
                'invoice' => count( WC()->cart->get_cart() ) == 0 ? 'Your cart is empty' : do_shortcode( '[woocommerce_checkout]' ),
                // 'invoice' => $items,
            ]
        );exit;

    }

    function get_ajax_calendar() {
        mvr_ajax_check();

        wp_send_json_success(
            [
                'calendar' => draw_calendar( $_POST['month'], $_POST['year'], ['controls' => $_POST['controls']] ),
            ]
        );
    }
}