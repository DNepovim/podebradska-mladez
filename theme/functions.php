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
