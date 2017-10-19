<?php
/* Template Name: Přihlašování */

if (isset($_GET['archiv'])) {
	$events = get_posts([
		'post_type'  => 'events',
		'numberposts'     => -1,
		'order' => 'DESC',
		'meta_query' => [
			[
				'key'     => 'pm_end_date',
				'value'   => current_time( 'Y-m-d H:i' ),
				'compare' => '<',
			]
		]
	]);
} else {
	$events = get_posts([
		'post_type'  => 'events',
		'offset'     => 1,
		'order' => 'DESC',
			'meta_query' => [
				[
							'key'     => 'pm_end_date',
							'value'   => current_time( 'Y-m-d H:i' ),
							'compare' => '>',
					]
			]
		]);
}

$args['events'] = $events;


view($args);
