<?php
include '../../../wp-load.php';

if (isset( $_POST['submitted'])
     && isset($_POST['post_nonce_field'])
     && wp_verify_nonce($_POST['post_nonce_field'],'post_nonce')
) {

	if (trim($_POST['postName']) === '') {
		$err['postTitle'] = true;
		$hasError         = true;
	}

	if (trim( $_POST['postName']) === '') {
		$err['postContent'] = true;
		$hasError           = true;
	}

	if ($hasError) {
		$query = '?';
		foreach ($err as $key => $value) {
			$query .= $key . '=' . $value . '&';
		}
		wp_redirect(home_url($query));
		exit;
	}

	$fullName = wp_strip_all_tags($_POST['postName']) . ' ' . wp_strip_all_tags($_POST['postSurname']);

	$post_information = array(
		'post_title'  => $fullName,
		'post_type'   => 'participants',
		'post_status' => 'private'
	);

	$post_id = wp_insert_post($post_information);

	add_post_meta($post_id,'event_id',$_POST['postID']);
	add_post_meta($post_id,'participant_name',$_POST['postName']);
	add_post_meta($post_id,'participant_surname',$_POST['postSurname']);

	if ($post_id) {
		wp_redirect(home_url());
	}
}
