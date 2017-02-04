<?php
// DEFINE CPT
require_once 'src/cpt/define.php';
require_once 'src/cpt/meta.php';

// MENU
add_action('init', 'register_my_menus');

function register_my_menus()
{
    register_nav_menus(
        array(
            'title-menu' => __('Title'),
            'secret-menu' => __('Secret'),
        )
    );
}

// POST THUMBNAILS
add_theme_support( 'post-thumbnails' );
add_image_size( 'som-face', 200 );
add_image_size( 'gallery-thumb', 300 );
add_image_size( 'fb-thumb', 500 );
add_image_size( 'invitation', 470 );


//POST EXCERPT MORE
function new_excerpt_more( $more ) {
    return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Pokračovat ve čtení...', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

function get_event_fb_posts($postID){

	$hashtag = get_post_meta($postID, 'pm_hashtag', true);

	if (!empty($hashtag)) {

		$args = array(
			'post_type'  => 'fb',
			'meta_key'   => 'fptc_hashtag',
			'meta_value' => $hashtag
		);

		$fbPosts = get_posts($args);

		return $fbPosts;

	} else {
		return false;
	}
}

function get_event_hashtags($postID) {

	$fbPosts = get_event_fb_posts($postID);

	if (!empty($fbPosts)) {

		$hashtags = array();

		foreach ($fbPosts as $post) {

			$potsHashtags = get_post_meta($post->ID, 'fptc_hashtag');

			$hashtags = array_merge($hashtags, $potsHashtags);
		}

		return array_unique($hashtags);

	} else {
		return false;
	}
}