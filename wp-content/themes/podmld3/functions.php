<?php
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
add_image_size( 'pozv', 470 );


//POST EXCERPT MORE
function new_excerpt_more( $more ) {
    return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Pokračovat ve čtení...', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

//SIDEBAR
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => 'Main Sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
?>