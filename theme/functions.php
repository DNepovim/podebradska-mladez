<?php

require __DIR__ . '/theme-init.php';

add_theme_support('menus');
register_nav_menu('main_menu', 'Hlavní menu');
register_nav_menu('links_menu', 'Odkazy');
register_nav_menu('footer_menu', 'Patička');

function get_menu_items($menuName) {
	if ($locations = get_nav_menu_locations()) {
		if (!empty($locations[$menuName])) {
			$menu = wp_get_nav_menu_object($locations[$menuName]);
			return wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
		} else {
			return false;
		}
	} else {
		return false;
	}
}

$View->main_menu = get_menu_items('main_menu');
$View->links_menu = get_menu_items('links_menu');
$View->footer_menu = get_menu_items('footer_menu');

$bgs = get_option('appearance-settings')['pm_bg_images'];
$View->site_background = $bgs[rand(0, count($bgs)-1)];
$View->gtm_code = $App->parameters['GTM'];

function get_daterange($event){
	$start = strtotime(meta($event->ID, 'pm_start_date'));
	$end = strtotime(meta($event->ID, 'pm_end_date'));
	if (date('dmy', $start) == date('dmy', $end)) {
			$date = date('j. n. Y', $start);
	} else {
		if(date('m', $start) == date('m', $end)){
			$date = date('j.', $start) . ' – ' . date('j. n. Y', $end);
		} else {
			$date = date('j. n.', $start) . ' – ' . date('j. n. Y', $end);
		}
	}

	return $date;
};

// Show date in admin posts list
add_filter('post_row_actions', 'pm_post_row_actions', 10, 2);
function pm_post_row_actions($actions, $post){
	echo get_daterange($post);
	return $actions;
}

// Sort events in admin by start date
function custom_post_order($query){
	if($query->get('post_type') === 'events' && $query->get('orderby') == ''){
		$query->set('order', 'ASC');
		$query->set('orderby', 'meta_value');
		$query->set('meta_key', 'pm_start_date');
	}
}
if(is_admin()){
		add_action('pre_get_posts', 'custom_post_order');
}

// Customize TinyMCE
function format_TinyMCE( $in ) {
		$in['block_formats'] = "Nadpis=h2; Podnadpis=h3; Odstavec=p";
		$in['toolbar1'] = 'formatselect,bold,underline,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker,wp_fullscreen,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help  ';
	$in['toolbar2'] = '';
	return $in;
}
add_filter( 'tiny_mce_before_init', 'format_TinyMCE' );

function get_event_fb_posts($postID){
	$hashtag = meta($postID, 'pm_hashtag');
	if (!empty($hashtag)) {
		$args = [
			'post_type'  => 'fb',
			'meta_key'   => 'fptc_hashtag',
			'meta_value' => $hashtag,
			'order'      => 'ASC'
		];
		$fb_posts = get_posts($args);
		return $fb_posts;
	} else {
		return false;
	}
}

function get_event_hashtags($postID) {
	$fb_posts = get_event_fb_posts($postID);
	if (!empty($fb_posts)) {
		$hashtags = [];
		foreach ($fb_posts as $post) {
			$potsHashtags = get_field($post->ID, 'fptc_hashtag', null, false);
			$hashtags = array_merge($hashtags, $potsHashtags);
		}
		return array_unique($hashtags);
	} else {
		return false;
	}
}

function process_registration_form($values, $postID = false) {
	if ($postID) {
		$full_name = wp_strip_all_tags($values['first_name']) . ' ' . wp_strip_all_tags($values['last_name']);
		$meta_input = [
			'event_id' => $postID,
			'participant_date' => current_time( 'Y-m-d H:i' ),
			'participant_type' => 'facebook',
			'participant_name' => $values['first_name'],
			'participant_surname' => $values['last_name'],
			'participant_chatfuel_user_id' => $values['chatfuel_user_id'],
			'participant_messenger_user_id' => $values['messenger_user_id'],
			'participant_profile_pic_url' => $values['profile_pic_url']
		];
	} else {
		$full_name = wp_strip_all_tags($values['firstname']) . ' ' . wp_strip_all_tags($values['surname']);
		$meta_input = [
			'event_id' => $values['postID'],
			'participant_date' => current_time( 'Y-m-d H:i' ),
			'participant_type' => 'web',
			'participant_name' => $values['firstname'],
			'participant_surname' => $values['surname'],
			'participant_mail' => $values['email']
		];
	}

	$meta_input['participant_json'] = json_encode($values);

	$post_data = [
		'post_title'  => $full_name,
		'post_type'   => 'participants',
		'post_status' => 'private',
		'meta_input'  => $meta_input
	];
	return wp_insert_post($post_data);
}

function getNextEvent() {
	$args   = [
		'post_type'  => 'events',
		'offset'     => 0,
		'order'      => 'ASC',
		'meta_query' => [
			[
				'key'     => 'pm_end_date',
				'value'   => date( 'Y-m-d h:m' ),
				'compare' => '>',
			]
		]
	];
	$events = get_posts( $args );

	return $events[0];
}

function isUserRegistered( $userID, $eventID ) {

	$args = [
		'post_type'   => 'participants',
		'post_status' => 'private',
		'meta_query'  => [
			[
				'key'   => 'participant_fb_id',
				'value' => $userID,
			],
			[
				'key'   => 'event_id',
				'value' => $eventID,
			]
		]
	];
	if ( ! empty( sizeof( get_posts( $args ) ) ) ) {
		return true;
	} else {
		return false;
	};
}

function getEventParticipants($eventID) {
	return get_posts([
		'post_type'  => 'participants',
		'post_status' => 'private',
		'numberposts'     => -1,
		'meta_key'   => 'event_id',
		'meta_value' => $eventID
	]);
}
