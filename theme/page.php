<?php

$post_type = meta(get_the_ID(), 'pm_page_posts');

$args['posts'] = get_posts([
	'post_type'  => $post_type,
	'numberposts' => -1,
	'order' => 'DESC'
]);

// latte file has same name as this file
view($args);
