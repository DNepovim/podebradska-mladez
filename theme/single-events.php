<?php

if (meta(get_the_ID(), 'pm_start_date') < date('Y-m-j h:i')) {
	view([
		'hashtags' => get_event_hashtags(get_the_ID()),
		'fb_posts' => get_event_fb_posts(get_the_ID()),
		'photos' => meta(get_the_ID(), 'pm_google_photos')
	]);
} else {
	$nextEvent = get_post(get_the_ID());
	require_once 'front-page.php';
}
