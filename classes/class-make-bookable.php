<?php

class DM_bookable_product extends WC_Product_Simple {
    // /**
    //  * Build the instance
    //  */
    // public function __construct() {
    //     // ...
    //     add_filter( 'product_type_selector', array( $this, 'add_type' ) );
    // }

    /**
     * Return the product type
     * @return string
     */
    public function get_type() {
        return 'dm_bookable';
    }

    /**
     * Returns the product's active price.
     *
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string price
     */
    public function get_price( $context = 'view' ) {

        if ( current_user_can( 'manage_options' ) ) {
            $price = $this->get_meta( '_member_price', true );
            if ( is_numeric( $price ) ) {
                return $price;
            }

        }
        return $this->get_prop( 'price', $context );
    }


}