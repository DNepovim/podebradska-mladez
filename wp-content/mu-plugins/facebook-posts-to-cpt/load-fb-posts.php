<?php
function fptc_load() {

	$options = get_option( 'fptc_option' );

	$fb = new Facebook\Facebook( [
		'app_id'                => $options['app_id'],
		'app_secret'            => $options['app_secret'],
		'default_graph_version' => 'v2.8',
	] );

	try {
		$response = $fb->get( '/' . $options['page_id'] . '/posts?locale=cs_CZ&fields=message,link,created_time,story,full_picture', $options['access_token'] );
	} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	fptc_save( $response->getDecodedBody()['data'] );
}


function fptc_save( $obj ) {

	foreach ( $obj as $item ) {
		$args = fptc_colect_data( $item );

		if ( $exist_post = fptc_exist_post( $args['meta_input']['fptc_fb_id'] ) ) {
			$args['ID'] = $exist_post;
			$id = wp_update_post( $args );

			if (!empty($item['message'])) {

				delete_post_meta($id, 'fptc_hashtags');

				preg_match_all('/#([^\s]+)/', $item['message'], $hashtags);
				foreach ($hashtags[0] as $item) {
					add_post_meta($id, 'fptc_hashtags',$item);
				}
			}

			if (!empty($item['full_picture'])) {
				fptc_save_image($id, $item['full_picture']);
			}
		} else {
			$id = wp_insert_post( $args );

			if (!empty($item['message'])) {

				delete_post_meta($id, 'fptc_hashtags');

				preg_match_all('/#([^\s]+)/', $item['message'], $hashtags);
				foreach ($hashtags[0] as $item) {
					add_post_meta($id, 'fptc_hashtags',$item);
				}
			}

			if (!empty($item['full_picture'])) {
				fptc_save_image($id, $item['full_picture']);
			}
		}
	}

	wp_redirect( '/wp-admin/edit.php?post_type=fb' );
	exit;
}

function fptc_exist_post( $fb_id ) {

	$args = array(
		'post_type'  => 'fb',
		'meta_key'   => 'fptc_fb_id',
		'meta_value' => $fb_id
	);

	$posts = get_posts( $args );

	try {
		if ( $posts ) {
			if ( count( $posts ) > 1 ) {
				throw new Exception( 'There are more then one results' );
			} else {
				return $posts[0]->ID;
			}
		} else {
			return false;
		};
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

function fptc_colect_data( $data ) {
	if ( empty( $data['message'] ) ) {
		$title = $data['story'];
	} else {
		$title = $data['message'];
	}
	$title = wp_trim_words( $title, 7 );

	$args = array(
		'post_title'  => $title,
		'post_status' => 'publish',
		'post_type'   => 'fb',
		'post_date'   => $data['created_time'],
		'meta_input'  => array(
			'fptc_fb_id'   => (string) $data['id'],
			'fptc_story'   => (string) $data['story'],
			'fptc_message' => (string) $data['message']
		)
	);

	return $args;
}

function fptc_save_image($postID, $imageUrl) {

	$uploaddir = wp_upload_dir();
	$filename = $postID . '-thumbnail.jpg';

	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
		$file = $uploaddir['path'] . '/' . $filename;
	} else {
		$file = $uploaddir['basedir'] . '/' . $filename;
	}

	file_put_contents($file, file_get_contents($imageUrl));

	$wp_filetype = wp_check_filetype( $filename, null );

	$attachment  = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);

	$attach_id   = wp_insert_attachment( $attachment, $file, $postID );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	$res1        = wp_update_attachment_metadata( $attach_id, $attach_data );
	$res2        = set_post_thumbnail( $postID, $attach_id );
}
