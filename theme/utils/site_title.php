<?php
use Nette\Utils\Html;

function site_title() {
	$title = Html::el();

	$site_name = get_bloginfo('name') . ' ' . get_bloginfo('description');

	if (is_front_page()) {
		$title->setHtml($site_name);
	} else {
		$title->setHtml(wp_title('', FALSE) . ' | ' . $site_name);
	}

	return $title;
}
