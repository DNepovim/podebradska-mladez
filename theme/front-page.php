<?php

if(isset($_GET['styleguide'])) {
	view('styleguide');
	exit;
}


$args = array(
	'post_type'  => 'events',
	'numberposts'     => -1,
	'order' => 'ASC',
	'meta_query' => array(
		array(
				'key'     => 'pm_end_date',
				'value'   => current_time( 'Y-m-d H:i' ),
				'compare' => '>',
		)
	)
);

$events = get_posts($args);

$netxtEvent = $events ? $events[0] : false;

$futureEvents = array_slice($events, 1);

$map = $nextEvent ? get_post_meta(meta($nextEvent->ID,'pm_position'), 'pm_position', true) : false;

$registerTo = $nextEvent && meta($nextEvent->ID, 'pm_register_to') >= current_time('Y-m-d') ? meta($nextEvent->ID, 'pm_register_to') : false;

// latte file has same name as this file
view(['nextEvent' => $nextEvent, 'map' => $map, 'futureEvents' => $futureEvents, 'registerTo' => $registerTo ]);
