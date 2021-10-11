<div id='dm_bookable_product_options' class='panel woocommerce_options_panel'>
    <div class='options_group'>
        <?php

            woocommerce_wp_text_input(
                array(
                    'id'                => 'dm_available',
                    'label'             => __( 'Available quantity', 'mvr' ),
                    'value'             => $product_object->get_meta( 'dm_available', true ),
                    'type'              => 'number',
                    'default'           => '0',
                    'placeholder'       => '0',
                    'custom_attributes' => [
                        'min' => '0',
                        'max' => '100',
                    ],
                ) );
                
                woocommerce_wp_text_input(
                    array(
                        'id'                => 'dm_capacity',
                        'label'             => __( 'Person capacity per quantity', 'mvr' ),
                        'value'             => $product_object->get_meta( 'dm_capacity', true ),
                        'type'              => 'number',
                        'default'           => '0',
                        'placeholder'       => '0',
                        'custom_attributes' => [
                            'min' => '0',
                            'max' => '500',
                        ],
                    ) );
        ?>
    </div>
</div>