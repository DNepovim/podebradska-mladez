<?php

add_action('after_setup_theme', 'add_image_sizes');
function add_image_sizes() {
	add_image_size('invitation', 500);
	add_image_size('wrapper', 300);
}
