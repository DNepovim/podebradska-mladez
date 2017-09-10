<?php
$args['nextEvent'] = get_posts([
	'post_type'  => 'events',
	'numberposts'     => -1,
	'order' => 'ASC',
	'meta_query' => [
		[
			'key'     => 'pm_end_date',
			'value'   => current_time( 'Y-m-d H:i' ),
			'compare' => '>'
		]
	]
])[0];

// latte file has same name as this file
view($args);
