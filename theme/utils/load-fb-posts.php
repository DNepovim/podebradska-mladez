<?php

function fptc_load() {
	$options = get_option('fb-settings');
	$fb = new Facebook\Facebook([
		'app_id'                => $options['pm_fb_app_id'],
		'app_secret'            => $options['pm_fb_app_secret'],
		'default_graph_version' => 'v2.8',
	]);
	try {
		$response = $fb->get('/' . $options['pm_fb_page_id'] . '/posts?locale=cs_CZ&fields=message,link,created_time,story,full_picture', $options['pm_fb_access_token']);
	} catch (Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch (Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	fptc_save($response->getDecodedBody()['data']);
	return 'success';
}

function fptc_save($obj) {
	foreach ($obj as $item) {
		$args = fptc_colect_data($item);
		if ($exist_post = fptc_exist_post($args['meta_input']['fptc_fb_id'])) {
			$args['ID'] = $exist_post;
			$id = wp_update_post($args);
			if (!empty($item['message'])) {
				delete_post_meta($id, 'fptc_hashtags');
				preg_match_all('/#([^\s]+)/', $item['message'], $hashtags);
				foreach ($hashtags[0] as $item) {
					add_post_meta($id, 'fptc_hashtag',$item);
				}
			}
			if (!empty($item['full_picture'])) {
				fptc_save_image($id, $item['full_picture']);
			}
		} else {
			$id = wp_insert_post($args);
			if (!empty($item['message'])) {
				delete_post_meta($id, 'fptc_hashtags');
				preg_match_all('/#([^\s]+)/', $item['message'], $hashtags);
				foreach ($hashtags[0] as $item) {
					add_post_meta($id, 'fptc_hashtag',$item);
				}
			}
			if (!empty($item['full_picture'])) {
				fptc_save_image($id, $item['full_picture']);
			}
		}
	}
	exit;
}

function fptc_exist_post($fb_id) {
	$args = [
		'post_type'  => 'fb',
		'meta_key'   => 'fptc_fb_id',
		'meta_value' => $fb_id
	];
	$posts = get_posts($args);
	try {
		if ($posts) {
			if (count($posts) > 1) {
				throw new Exception('There are more then one results');
			} else {
				return $posts[0]->ID;
			}
		} else {
			return false;
		};
	} catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

function fptc_colect_data($data) {
	if (empty($data['message'])) {
		$title = $data['story'];
	} else {
		$title = $data['message'];
	}
	$title = wp_trim_words($title, 7);
	$args = [
		'post_title'  => $title,
		'post_status' => 'publish',
		'post_type'   => 'fb',
		'post_date'   => $data['created_time'],
		'meta_input'  => [
			'fptc_fb_id'   => (string) $data['id'],
			'fptc_story'   => (string) $data['story'],
			'fptc_message' => (string) $data['message']
		]
	];
	return $args;
}

function fptc_save_image($post_id, $imageUrl) {

	global $post;

	$name = basename(parse_url($imageUrl)['path']);
	$upload = wp_upload_bits(wp_unique_filename(wp_upload_dir()['path'], $name), null, file_get_contents($imageUrl));

	if($upload['error']) {
		trigger_error('Upload failed: ' . $upload['error']);
	} else {
		$attach_id = wp_insert_attachment([
			'post_title' => $name,
			'post_content' => '',
			'post_status' => 'inherit',
			'post_mime_type' => 'image/jpg'
		], $upload['file'], $post_id);
	}

	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
	$res1        = wp_update_attachment_metadata($attach_id, $attach_data);
	$res2        = set_post_thumbnail($post_id, $attach_id);

}
