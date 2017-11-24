<?php

if(isset($_GET['styleguide'])) {
	view('styleguide');
	exit;
}

$events = get_posts([
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
]);

$args['nextEvent'] = $events ? $events[0] : false;

$args['futureEvents'] = array_slice($events, 1);

$args['map'] = $nextEvent ? get_post_meta(meta($nextEvent->ID,'pm_position'), 'pm_position', true) : false;

if ($registerTo = meta($nextEvent->ID, 'pm_register_to')) {
	if ($registerTo >= current_time('Y-m-d')) {
		$registerTo = -1;
	}
} else {
	$registerTo = -1;
}

$args['registerTo'] = $registerTo;

if (!has_post_thumbnail($nextEvent->ID)) {
	$args['lastEvent'] = get_posts([
		'post_type'  => 'events',
		'numberposts'     => 1,
		'order' => 'DESC',
		'orderby' => 'meta_value',
		'meta_key' => 'pm_start_date',
		'meta_query' => [
			[
				'key'     => 'pm_end_date',
				'value'   => current_time( 'Y-m-d H:i' ),
				'compare' => '<',
			]
		]
	])[0];

	$args['lastEventPhotos'] = meta($args['lastEvent']->ID, 'pm_google_photos');
}


view($args);
