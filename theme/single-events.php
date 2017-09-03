<?php

$args = [
	'hashtags' => get_event_hashtags(get_the_ID()),
	'fb_posts' => get_event_fb_posts(get_the_ID()),
	'photos' => meta(get_the_ID(), 'pm_google_photos')
];

// latte file has same name as this file
view($args);
