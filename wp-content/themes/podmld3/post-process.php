<?php
include '../../../wp-load.php';

if (isset( $_POST['submitted'])
     && isset($_POST['post_nonce_field'])
     && wp_verify_nonce($_POST['post_nonce_field'],'post_nonce')
) {

	if (trim($_POST['postName']) === '') {
		$err['postName'] = true;
		$hasError         = true;
	}

	if (trim( $_POST['postSurname']) === '') {
		$err['postSurname'] = true;
		$hasError           = true;
	}

	if (trim( $_POST['postMail']) === '') {
		$err['postMail'] = true;
		$hasError           = true;
	}

	if ($hasError) {
		$query = '?';
		foreach ($err as $key => $value) {
			$query .= $key . '=' . $value . '&';
		}
		$query .= 'status=error';
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
	add_post_meta($post_id,'participant_date',current_time( 'Y-m-d H:i' ));
	add_post_meta($post_id,'participant_type','web');
	add_post_meta($post_id,'participant_name',$_POST['postName']);
	add_post_meta($post_id,'participant_surname',$_POST['postSurname']);
	add_post_meta($post_id,'participant_mail',$_POST['postMail']);

	if ($post_id) {
		$query = '?status=success';
		wp_redirect(home_url($query));
	}
}
