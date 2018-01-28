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
	if (get_post_type($post) == 'events') {
		echo get_daterange($post);
	}
	return $actions;
}

// Sort events in admin by start date
function custom_post_order($query){
	if($query->get('post_type') === 'events' && $query->get('orderby') == ''){
		$query->set('order', 'DESC');
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
	$prefix = 'participant_';
	if ($postID) {
		$full_name = wp_strip_all_tags($values['first_name']) . ' ' . wp_strip_all_tags($values['last_name']);
		$meta_input = [
			'event_id' => $postID,
			$prefix . 'date' => current_time( 'Y-m-d H:i' ),
			$prefix . 'type' => 'facebook',
			$prefix . 'name' => $values['first_name'],
			$prefix . 'surname' => $values['last_name'],
			$prefix . 'chatfuel_user_id' => $values['chatfuel_user_id'],
			$prefix . 'messenger_user_id' => $values['messenger_user_id'],
			$prefix . 'profile_pic_url' => $values['profile_pic_url']
		];
	} else {
		$full_name = wp_strip_all_tags($values['firstname']) . ' ' . wp_strip_all_tags($values['surname']);
		$meta_input = [
			'event_id' => $values['postID'],
			$prefix . 'date' => current_time( 'Y-m-d H:i' ),
			$prefix . 'type' => 'web',
			$prefix . 'name' => $values['firstname'],
			$prefix . 'surname' => $values['surname'],
			$prefix . 'mail' => $values['email'],
			$prefix . 'additional' => $values['additional']
		];
	}

	$meta_input[$prefix . 'json'] = json_encode($values);

	$meta_input[$prefix . 'som'] = get_posts_by_title($full_name, 'som')[0];

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

function get_posts_by_title($post_title, $post_type) {
	global $wpdb;

	$search_query = 'SELECT ID FROM wp_posts WHERE post_type = "' . $post_type . '" AND post_title = "' . $post_title . '"';
	$results = $wpdb->get_results($search_query, ARRAY_N);

	foreach($results as $key => $array){
			$quote_ids[] = $array[0];
	}

	return $quote_ids;
}

show_admin_bar(false);

add_action('pre_render_view', 'create_admin_menu');

function create_admin_menu() {
	global $View;

	$adminNavigation = [];
	if (get_page_template_slug() == 'template-registerlist.php') {
		$adminNavigation = [
			[
				'title' => 'web',
				'icon' => 'home',
				'link' => get_home_url()
			],
		];
		if (isset($_GET['archive'])) {
			$adminNavigation[] = [
				'title' => 'aktuální',
				'icon' => 'users',
				'link' => get_permalink()
			];
		} else {
			$adminNavigation[] = [
				'title' => 'archiv',
				'icon' => 'th-list',
				'link' => get_permalink() . '?archive'
			];
		}
	} else {
		$adminNavigation[] = [
			'title' => 'přihlašování',
			'icon' => 'users',
			'link' => '/tajne'
		];
	}

	$adminNavigation[] = [
			'title' => 'administrace',
			'icon' => 'wordpress',
			'link' => get_admin_url()
	];

	$adminNavigation[] = [
			'title' => 'odhlásit',
			'icon' => 'sign-out',
			'link' => wp_logout_url(get_home_url())
	];

	$View->admin_navigation = $adminNavigation;
}

function admin_default_page() {
  return '/prihlasovani';
}
add_filter('login_redirect', 'admin_default_page');
