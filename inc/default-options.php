<?php
/**
 * Widget options and default values.
 */

$days = array_keys( array_fill( 1, 30, '' ) );
$charts = [];

$x = esc_attr__( 'Total Visitors', 'dbdw' );
$y = esc_attr__( 'New Visitors', 'dbdw' );
$z = esc_attr__( 'Returning Visitors', 'dbdw' );

foreach ($days as $day) {
    $rand  = rand( 1, 10 ) / 10;
    $total_visits = 100 * $rand * $day;
    $new_visits = rand( 10, $total_visits );
    $ret_visits = $total_visits - $new_visits;
    array_push( $charts, array(
        'name' => "Day $day",
        $x => $total_visits,
        $y => $new_visits,
        $z => $ret_visits,
    ) );
}

return array(
    'widget_disabled' => 0,
    'timeframe' => '7-days',
    'charts' => $charts,
    'chart_lines' => array(
        array(
            'title' => $x,
            'stroke' => '#82ca9d',
        ),
        array(
            'title' => $y,
            'stroke' => '#8884d8',
        ),
        array(
            'title' => $z,
            'stroke' => '#ff7300',
        ),
    ),
);
