<?php
/* Template Name: Archiv */

$args['events'] = get_posts([
	'post_type' => 'events',
	'numberposts' => -1,
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
]);

view($args);
