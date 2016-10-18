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
add_image_size( 'invitation', 470 );


//POST EXCERPT MORE
function new_excerpt_more( $more ) {
    return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Pokračovat ve čtení...', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
