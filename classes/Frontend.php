<?php

namespace mvr;

// defined( 'ABSPATH' ) or exit;

class Frontend {
    function __construct() {
        add_action( 'wp_head', [$this, 'reservation_button'] );
    }

    public function reservation_button() {
        printf( '<div class="reservation-button"><i class="fas fa-calendar-week"></i>RESERVATION</div>' );
    }
}