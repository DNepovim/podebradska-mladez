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
	$start = strtotime(get_post_meta($event->ID, 'pm_start_date', true));
	$end = strtotime(get_post_meta($event->ID, 'pm_end_date', true));
	if (date('dmy', $start) == date('dmy', $end)) {
			$date = date('j. n. Y', $start);
	} else {
		if(date('m', $start) == date('m', $end)){
			$date = date('j.', $start) . '–' . date('j. n. Y', $end);
		} else {
			$date = date('j. n.', $start) . '–' . date('j. n. Y', $end);
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
