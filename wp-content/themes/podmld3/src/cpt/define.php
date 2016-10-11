<?php

add_action('init', 'create_post_type');

function create_post_type()
{
	register_post_type('events',
		array(
			'labels' => array(
				'name' => __('Události'),
			),
			'public' => true,
			'has_archive' => true,
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
				'name' => __('Mapy'),
			),
			'public' => true,
			'has_archive' => true,
			'supports' => array(
				'title',
			)
		)
	);
}

