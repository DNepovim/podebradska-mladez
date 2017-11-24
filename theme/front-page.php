<?php

if(isset($_GET['styleguide'])) {
	view('styleguide');
	exit;
}


$args = [
	'post_type'  => 'events',
	'numberposts'     => -1,
	'order' => 'ASC',
	'orderby' => 'meta_value',
	'meta_key' => 'pm_start_date',
	'meta_query' => [
		[
			'key'     => 'pm_end_date',
			'value'   => current_time( 'Y-m-d H:i' ),
			'compare' => '>',
		]
	]
];

$events = get_posts($args);

$nextEvent = $events ? $events[0] : false;

$futureEvents = array_slice($events, 1);

$map = $nextEvent ? get_post_meta(meta($nextEvent->ID,'pm_position'), 'pm_position', true) : false;

if ($registerTo = meta($nextEvent->ID, 'pm_register_to')) {
	if ($registerTo >= current_time('Y-m-d')) {
		$registerTo = -1;
	}
} else {
	$registerTo = -1;
}

// latte file has same name as this file
view(['nextEvent' => $nextEvent, 'map' => $map, 'futureEvents' => $futureEvents, 'registerTo' => $registerTo ]);
