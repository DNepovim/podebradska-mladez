<?php

require __DIR__ . '/theme-init.php';

add_theme_support('menus');
register_nav_menu('main_menu', 'Hlavní menu');
register_nav_menu('contacts_menu', 'Kontakty');
register_nav_menu('links_menu', 'Odkazy');

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
$View->contacts_menu = get_menu_items('contacts_menu');
$View->links_menu = get_menu_items('links_menu');

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
		$query->set('order', 'desc');
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
			'meta_value' => $hashtag
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
