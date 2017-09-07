<?php



$args['events'] = get_posts([
	'post_type' => 'events',
	'numberposts' => -1,
	'order' => 'DESC',
	'meta_query' => [
		[
			'key'     => 'pm_end_date',
			'value'   => current_time( 'Y-m-d H:i' ),
			'compare' => '<',
		]
	]
]);

// latte file has same name as this file
view($args);
