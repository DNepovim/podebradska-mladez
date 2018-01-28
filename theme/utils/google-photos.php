<?php
// Add '=w1800-h1200-no' for specific resolutions

function load_google_photos($post_id) {
	$url = meta($post_id, 'pm_google_photos_album');
	if (!empty($url)) {
		$content = file_get_contents($url);
		preg_match('/AF_initDataCallback(.*)function\(\){return ([^%]*)/', $content, $match);
		$next = substr($match[0], strpos($match[0], '{return ') + 8);
		$last = substr($next, 0, strpos($next, '}});'));
		$json = json_decode($last);
		$images = [];
		foreach ($json[1] as $item) {
			$images[] = $item[1][0];
		}

		if (metadata_exists('post', $post_id, 'pm_google_photos')) {
			return update_post_meta($post_id, 'pm_google_photos', $images);
		} else {
			return add_post_meta($post_id, 'pm_google_photos', $images);
		}
	} else {
		return delete_post_meta($post_id, 'pm_google_photos');
	}
}
