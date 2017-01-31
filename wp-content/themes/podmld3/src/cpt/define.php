<?php

add_action('init', 'create_post_type');

function create_post_type()
{
	register_post_type('events',
		array(
			'labels' => array(
				'name' => __('Události'),
			),
			'rewrite' => array(
				'slug' => 'akce'
			),
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-exerpt-view',
			'supports' => array(
				'title',
				'author',
				'editor',
				'thumbnail',
			)
		)
	);

	register_post_type('maps',
		array(
			'labels' => array(
				'name' => __('Místa'),
			),
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-location',
			'supports' => array(
				'title',
			)
		)
	);

	register_post_type('participants',
		array(
			'labels' => array(
				'name' => __('Přihlašování'),
			),
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-groups',
			'supports' => array(
				'title',
				'custom-fields'
			)
		)
	);

	register_post_type('som',
		array(
			'labels' => array(
				'name' => __('SOM'),
			),
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-businessman',
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			)
		)
	);
}

