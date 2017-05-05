<?php


add_filter( 'rwmb_meta_boxes', 'your_prefix_meta_boxes' );
function your_prefix_meta_boxes( $meta_boxes ) {

	$prefix = 'pm_';

	$meta_boxes[] = array(
		'title'      => __( 'Data', 'textdomain' ),
		'post_types' => 'events',
		'fields'     => array(
			array(
				'id'   => $prefix . 'start_date',
				'name' => __( 'Začátek' ),
				'type' => 'datetime'
			),
			array(
				'id'   => $prefix . 'end_date',
				'name' => __( 'Konec' ),
				'type' => 'datetime'
			),
			array(
				'id'        => $prefix . 'position',
				'name'      => __( 'Mapa', 'textdomain' ),
				'type'      => 'post',
				'post_type' => 'maps'
			),
		),
	);

	$meta_boxes[] = array(
		'title'      => __( 'Přihlašování', 'textdomain' ),
		'post_types' => 'events',
		'fields'     => array(
			array(
				'id'        => $prefix . 'register_to',
				'name'      => __( 'Uzávěrka', 'textdomain' ),
				'type'      => 'date'
			)
		),
	);

	$meta_boxes[] = array(
		'title'      => __( 'Kronika', 'textdomain' ),
		'post_types' => 'events',
		'fields'     => array(
			array(
				'id'        => $prefix . 'hashtag',
				'name'      => __( 'Facebook hashtag', 'textdomain' ),
				'type'      => 'text'
			),
			array(
				'id'        => $prefix . 'article',
				'name'      => __( 'Zápis', 'textdomain' ),
				'type'      => 'wysiwyg'
			),
			array(
				'id'        => $prefix . 'images',
				'name'      => __( 'Fotky', 'textdomain' ),
				'type'      => 'image_upload'
			),
		),
	);

	$meta_boxes[] = array(
		'title'      => __( 'Mapa', 'textdomain' ),
		'post_types' => 'maps',
		'fields'     => array(
			array(
				'id'   => $prefix . 'position',
				'name' => __( 'Mapa', 'textdomain' ),
				'type' => 'textarea'
			),
		),
	);

	$meta_boxes[] = array(
		'title'      => __( 'Pozice', 'textdomain' ),
		'context' => 'side',
		'post_types' => 'som',
		'fields'     => array(
			array(
				'id'   => $prefix . 'position',
				'type' => 'radio',
				'inline' => false,
				'options' => array(
					'chairman' => 'předseda',
					'vice-chairman' => 'místopředseda',
					'member' => 'člen',
					'ex-member' => 'bývalý člen'
					)
			)
		),
	);

	return $meta_boxes;
}
