<?php

function draw_calendar_controls( $controls, $month, $year ) {
    if ( get_locale() == 'en-ca' || get_locale() == 'en' ) {
        $months = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
    } else {
        $months = array( 'Jan.', 'Fév.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.' );
    }

    if ( $controls == 'ajax_month' ) {
        $prev_month_link = '<a href="#" data-month="' . ( $month != 1 ? str_pad( $month - 1, 2, 0, STR_PAD_LEFT ) : 12 ) . '" data-year="' . ( $month != 1 ? $year : $year - 1 ) . '" class="control calendar-prev-month btn-prev"><i class="fa fa-chevron-left"></i></a>';
        $next_month_link = '<a href="#" data-month="' . ( $month != 12 ? str_pad( $month + 1, 2, 0, STR_PAD_LEFT ) : '01' ) . '" data-year="' . ( $month != 12 ? $year : $year + 1 ) . '" class="control calendar-next-month btn-next"><i class="fa fa-chevron-right"></i></a>';

        $reset_link = '<a title="Revenir au mois en cours..." href="#" data-month="' . date( 'm' ) . '" data-year="' . date( 'Y' ) . '" class="ml-3 calendar-reset"><i class="fa fa-history"></i></a>';
    } else if ( $controls == 'month' ) {
        $prev_month_link = '<a data-month=" ' . ( $month != 1 ? str_pad( $month - 1, 2, 0, STR_PAD_LEFT ) : 12 ) . ' " data-year="' . ( $month != 1 ? $year : $year - 1 ) . '" href="?m=' . ( $month != 1 ? str_pad( $month - 1, 2, 0, STR_PAD_LEFT ) : 12 ) . '&y=' . ( $month != 1 ? $year : $year - 1 ) . '" class="control btn-prev"><i class="fa fa-chevron-left"></i></a>';
        $next_month_link = '<a data-month="' . ( $month != 12 ? str_pad( $month + 1, 2, 0, STR_PAD_LEFT ) : 1 ) . '" data-year="' . ( $month != 12 ? $year : $year + 1 ) . '" href="?m=' . ( $month != 12 ? str_pad( $month + 1, 2, 0, STR_PAD_LEFT ) : 1 ) . '&y=' . ( $month != 12 ? $year : $year + 1 ) . '" class="control btn-next"><i class="fa fa-chevron-right"></i></a>';
        $reset_link      = '<a title="Mois en cours..." href="?m=' . date( 'm' ) . '&y=' . date( 'Y' ) . '" class="ml-3"><i class="fa fa-history"></i></a>';
    }

    $html = '';
    $html .= '<tr class="controls">';
    $html .= '<td class="text-center">' . $prev_month_link . '</td>';
    $html .= '<td colspan="5" class="text-center control-date text-white m-0 p-0"><h3 class="p-0 m-0">' . $months[$month - 1] . ' ' . $year . ( $month == date( 'm' ) && $year == date( 'Y' ) ? '' : $reset_link ) . '</h3></td>';
    $html .= '<td class="text-center">' . $next_month_link . '</td>';
    $html .= '</tr>';
    return $html;
}

function draw_calendar( $month, $year, $params = [], $user_id = null ) {
    // return 'a';
    if ( $year < date( 'Y' ) || $year > ( date( 'Y' ) + 1 ) ) {
        $year = date( 'Y' );
    }

    if ( $month < 1 || $month > 12 ) {
        $month = date( 'm' );
    }

    // DEBUG
    #echo "<br>1=".mktime(0,0,0,$month,1,$year);
    #echo "<br>2=".mktime(0,0,0,date('m'),1,date('Y'));

    if ( mktime( 0, 0, 0, $month, 1, $year ) < mktime( 0, 0, 0, date( 'm' ), 1, date( 'Y' ) ) ) {
        $month = date( 'm' );
        $year  = date( 'Y' );
    }

    /* table headings */
    if ( get_locale() == 'en-ca' || get_locale() == 'en' ) {
        $headings = array( 'Sun.', 'Mon.', 'Tue.', 'Wed.', 'Thu.', 'Fri.', 'Sat.' );
    } else {
        $headings = array( 'Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.' );
    }

    $calendar = '';

    #$devclass='d-none';

    $devclass = 'd-none';
    if ( $user = get_userdata( get_current_user_id() ) ) {
        if ( $user->id == 1 ) // Superadmin
        {
            $devclass = '';
        }
    }

    /*$calendar.= '
    <div id="calendar-tooltip" style="display: none;" class="'.$devclass.'">
    <table class="table table-borderless table-sm">
    <thead class="thead-light">
    <tr>
    <th colspan="2">
    Disponibilités
    </th>
    </tr>
    </thead>
    <tbody>
    <tr><td class="debug">Hors-piste :</td><td id="vehiclecount" class="text-success font-weight-bolder">-</td></tr>
    <tr><td class="debug">Sentier :</td><td id="vehiclesentiercount" class="text-success font-weight-bolder">-</td></tr>
    <tr><td class="debug">Guides :</td><td id="guidecount" class="text-success font-weight-bolder">-</td></tr>
    <tr><td class="debug">Chalets :</td><td id="chaletcount" class="text-success font-weight-bolder">-</td></tr>
    <tr><td class="debug">Lofts :</td><td id="loftcount" class="text-success font-weight-bolder">-</td></tr>
    <tr class=" d-none"><td class="debug text-secondary">Personnes :</td><td id="personcount" class=" text-secondary">-</td></tr>
    </tbody>

    </table>
    </div>';
     */

    /* draw table */
    $calendar .= '<table id="calendar" cellpadding="0" cellspacing="0">';
    $calendar .= '<thead>';

    $controls = 'month';
    if ( isset( $params['controls'] ) ) {
        $controls = $params['controls'];
    }

    $controls_html = draw_calendar_controls( $controls, $month, $year );
    $calendar .= $controls_html;

    $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode( '</td><td class="calendar-day-head">', $headings ) . '</td></tr></thead>';

    /* days and weeks vars now ... */
    $running_day       = date( 'w', mktime( 0, 0, 0, $month, 1, $year ) );
    $days_in_month     = date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
    $days_in_this_week = 1;
    $day_counter       = 0;
    $dates_array       = array();

    /* row for week one */
    $calendar .= '<tbody><tr class="calendar-row">';

    /* print "blank" days until the first of the current week */
    for ( $x = 0; $x < $running_day; $x++ ):
        $calendar .= '<td class="calendar-day-np"> </td>';
        $days_in_this_week++;
    endfor;

                         // GET DB DATA FOR THE MONTH
                         // $eventsOfMonth = \App\Reservation\Reservation::getMonthReservationsArrayByDay( $month, $year );
    $eventsOfMonth = []; //\App\Reservation\Reservation::getMonthReservationsArrayByDay( $month, $year );

    /* keep going with days.... */
    for ( $list_day = 1; $list_day <= $days_in_month; $list_day++ ) {
        // Disable mouse if day is past
        $past_day    = false;
        $current_day = false;
        if ( mktime( 0, 0, 0, $month, $list_day, $year ) < mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) ) ) {
            $past_day = true;
        } else if ( mktime( 0, 0, 0, $month, $list_day, $year ) == mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) ) ) {
            $past_day    = false;
            $current_day = true;
        }

        $calendar .= '<td onclick="select_day_options(this)" class="noselect calendar-day' . ( $past_day ? ' pastday disabled' : '' ) . '' . ( $current_day ? ' today' : '' ) . '" data-ref="' . str_pad( $list_day, 2, 0, STR_PAD_LEFT ) . '/' . $month . '/' . $year . '"';

        $event_day = $year . '-' . $month . '-' . str_pad( $list_day, 2, '0', STR_PAD_LEFT );

        if ( $past_day === false ) {
            // Add TD attributes if needed
            if ( isset( $eventsOfMonth[$event_day] ) ) {
                foreach ( $eventsOfMonth[$event_day] as $type => $count ) {
                    $calendar .= ' data-available-' . $type . '="' . $count . '"';
                }
            } /*
        DEBUG
        else
        {
        $calendar.= ' data-available-toto="'.$event_day.'"';
        }*/
        }

        $calendar .= '>';

        // DEBUG for developer
        /*
        if( $user_id == 2 && $past_day === false )
        {
        if(isset($eventsOfMonth[$event_day]))
        {
        foreach($eventsOfMonth[$event_day] as $type => $count)
        {
        $calendar.= '<div class="debug">'.ucfirst($type).' : '.$count.'</div>';
        }
        }
        }
         */

        /* add in the day number */
        $calendar .= '<div class="day-number">' . $list_day . '</div>';

        $calendar .= '</td>';
        if ( $running_day == 6 ):
            $calendar .= '</tr>';
            if (  ( $day_counter + 1 ) != $days_in_month ):
                $calendar .= '<tr class="calendar-row">';
            endif;
            $running_day       = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++;
        $running_day++;
        $day_counter++;
    }

    /* finish the rest of the days in the week */
    if ( $days_in_this_week < 8 ):
        for ( $x = 1; $x <= ( 8 - $days_in_this_week ); $x++ ):
            $calendar .= '<td class="calendar-day-np"> </td>';
        endfor;
    endif;

    /* final row */
    $calendar .= '</tr></tbody>';

    /*
    $calendar.= '
    <tfoot>
    <tr>
    <td colspan="7">

    </td>
    </tr>
    </tfoot>';
     */
    /* end the table */
    $calendar .= '</table>';

    $calendar .= '<div class="loader" style="display: none;"><p>Chargement...</p><script>navigateCalendar();</script></div>';

    return $calendar;
}
