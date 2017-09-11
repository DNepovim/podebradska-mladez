<?php
/* Template Name: Přihlašování */

$args_events = [
	'post_type'  => 'events',
	'offset'     => 0,
	'order' => 'ASC',
		'meta_query' => [
			[
						'key'     => 'pm_end_date',
						'value'   => current_time( 'Y-m-d H:i' ),
						'compare' => '>',
				]
		]
	];

if ($events = get_posts($args_events)) {
	$args['participants'] = get_posts([
		'post_type'  => 'participants',
		'post_status' => 'private',
		'numberposts'     => -1,
		'meta_key'   => 'event_id',
		'meta_value' => $events[0]->ID
	]);
} else {
	$args['participants'] = false;
}
$args['nextEvent'] = $events[0];

view($args);
