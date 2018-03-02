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

$args['nextEvent'] = !empty($nextEvent) ? $nextEvent : $events[0];

$args['futureEvents'] = array_slice($events, 1);

$args['map'] = $args['nextEvent'] ? get_post_meta(meta($args['nextEvent']->ID,'pm_position'), 'pm_position', true) : false;

if (!has_post_thumbnail($args['nextEvent']->ID)) {
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
