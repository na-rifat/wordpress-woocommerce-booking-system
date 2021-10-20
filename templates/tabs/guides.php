<?php
    $product = wc_get_products(
        [
            'category' => 'guides',
        ]
    )[0];
    $dm_available = $product->get_meta( 'dm_available' );
    $dm_capacity  = $product->get_meta( 'dm_capacity' );
    $price        = $product->get_display_price();

    $dm_available = empty( $dm_available ) ? 0 : $dm_available;
    $dm_capacity  = empty( $dm_capacity ) ? 0 : $dm_capacity;
?>
<h4 class="text-left">Rental of guide service for trail or off- trail excursions .</h4>
<br><br>
<div class="rsvp text-left">
    <div class="mvr-info">Please make sure to correctly select the desired quantity for each of your chosen dates.</div>
    <div class="multiple-selector">
            <script>
            guideText = ``;
            guideText += ` <div role="group" class="input-group mt-1">
            <div class="input-group-prepend">
                <div class="input-group-text text-nowrap"><span class="badge badge-primary">{short_date}</span></div>
            </div><select name="input_person[]" type="number" placeholder="Enter the number of people"
                class="input_person enterNextTab form-control" data-refi="{refi}" data-date="{datei}">
                <option value="" disabled selected>No guide selected</option>
                <option value="0">None for this day</option>`;

            guideText += '<?php
                              for ( $i = 1; $i <= $dm_available; $i++ ) {
                                  printf(
                                      '<option value="%s">%s</option>',
                                      $i,
                                      sprintf( '%s guide%s - (%s to %s people) $%s',
                                          $i,
                                          $i > 1 ? 's' : '',
                                          $i * $dm_capacity - ( $dm_capacity - 1 ),
                                          $i * $dm_capacity,
                                          $price * $i
                                      )
                                  );
                          }
                          ?>';
            guideText += `</div></select>`
            </script>



    </div>
</div>
<br><br>