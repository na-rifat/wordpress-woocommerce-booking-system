<?php

namespace mvr;

class Install {
    public static function create_guides() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $term_id = wp_insert_term( 'Guides', 'product_cat', array(
            'description' => 'Automatically created by DM reservation (Don\'t modify).', // optional
            'parent'      => 0,                                                          // optional
            'slug'        => 'guides',                                                   // optional
        ) )['term_id'];

        $args = array(
            'post_author'  => 0,
            'post_content' => '',
            'post_status'  => "publish", // (Draft | Pending | Publish)
            'post_title'   => 'Guides',
            'post_parent'  => '',
            'post_type'    => "product",
        );

        // Create a simple WooCommerce product
        $post_id = wp_insert_post( $args );

        // Setting the product type
        wp_set_object_terms( $post_id, 'simple', 'product_type' );
        wp_set_object_terms( $post_id, $term_id, 'product_cat' );

        // Setting the product price
        update_post_meta( $post_id, '_price', 500 );
        update_post_meta( $post_id, '_regular_price', 500 );
    }
}