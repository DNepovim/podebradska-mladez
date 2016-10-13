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
				'id'   => $prefix . 'register_to',
				'name' => __( 'Přihlašujte se do', 'textdomain' ),
				'type' => 'date'
			),
			array(
				'id'   => $prefix . 'register_fields',
				'name' => __( 'Další pole', 'textdomain' ),
				'type' => 'text',
				'clone' => true
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

	return $meta_boxes;
}
