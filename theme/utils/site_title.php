<?php
use Nette\Utils\Html;

function site_title() {
	$title = Html::el();

	$site_name = get_bloginfo('name') . ' ' . get_bloginfo('description');

	if (function_exists('is_tag') && is_tag()) {
		$title->setHtml('Tag Archive for "' . $tag . '" | ' . $site_name);
	} elseif (is_archive()) {
		$title->setHtml(wp_title('', FALSE) . ' Archive | ' . $site_name);
	} elseif (is_search()) {
		$title->setHtml('Search for "' . wp_specialchars($s) . '" | ' . $site_name);
	} elseif (!(is_404()) && (is_single()) || (is_page())) {
		$title->setHtml(wp_title('', FALSE) . ' | ' . $site_name);
	} elseif (is_404()) {
		$title->setHtml('Not Found | ' . $site_name);
	} else {
		$title->setHtml($site_name);
	}

	return $title;
}
