<?php

namespace mvr;

class Bookable {

    /**
     * Build the instance
     */
    public function __construct() {
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_tab' ), 50 );
        add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_tab_content' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_meta_values' ) );
        add_action( 'woocommerce_cart_calculate_fees', [$this, 'add_user_discounts'] );
    }

    /**
     * Add Experience Product Tab.
     *
     *
     * @param  array   $tabs
     * @return mixed
     */
    public function add_product_tab( $tabs ) {

        $tabs['simple'] = array(
            'label'  => __( 'DM bookable', 'mvr' ),
            'target' => 'dm_bookable_product_options',
            'class'  => 'show_if_simple',
        );

        unset( $tabs['shipping'] );

        return $tabs;
    }

    /**
     * Add Content to Product Tab
     */
    public function add_product_tab_content() {
        global $product_object;
        include MVR_TEMPLATES_DIR . 'woocommerce/dm_settings.php';
    }

    public function save_meta_values( $post_id ) {
        $dm_available = esc_attr( $_POST['dm_available'] );
        $dm_capacity  = esc_attr( $_POST['dm_capacity'] );

        update_post_meta( $post_id, 'dm_available', $dm_available );
        update_post_meta( $post_id, 'dm_capacity', $dm_capacity );
    }

    public function add_user_discounts( $cart ) {

        if ( count( $cart->get_cart() ) < 3 ) {
            return;
        }

        $discount = $cart->get_subtotal() * 0.15;

        $cart->add_fee( 'Discount 15%', -$discount );
    }

}