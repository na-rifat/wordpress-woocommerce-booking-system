<?php
    $month = isset( $_POST['month'] ) ? $_POST['month'] : date( 'm' );
    $year  = isset( $_POST['year'] ) ? $_POST['year'] : date( 'Y' );
?>

<div class="rsv-section rsv-section-step-2">
    <div class="rsv-row">
        <h4><i class="fas fa-mouse-pointer"></i>Selection of booking dates</h4>
    </div>
    <div class="rsv-row calendar-holder">
        <?php echo draw_calendar( $month, $year, ['controls' => 'month'] ) ?>
    </div>
    <div class="rsv-row">
    <div class="rsv-form-button invisible rsv-config-dates"><i class="fa fa-cog mr-2"></i>Configure my chosen dates</div>
        <div class="rsv-reset-button invisible"><i class="fa fa-refresh mr-2"></i>Restart</div>
    </div>
</div>

