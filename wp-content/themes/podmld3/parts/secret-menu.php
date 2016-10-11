<div class="col-md-2">
	<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
		<!--Title block-->
		<div class="title">
			<div class="secret">
				<!--Title-->
				<h1><?php bloginfo( 'name' ); ?></h1>
				<!--Logo-->
				<img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/img/ryba.png">
				<!--Description-->
				<h2><?php bloginfo( 'description' ); ?></h2>
				<!--Menu-->
				<div class="menu">
					<?php if (has_nav_menu('secret-menu')) {
						wp_nav_menu(array('theme_location' => 'secret-menu'));} ?>
				</div>
			</div>
		</div>

	</a>
</div>