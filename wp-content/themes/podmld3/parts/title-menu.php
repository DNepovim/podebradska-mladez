<div class="col-md-2">
        <!--Title block-->
    <div class="navigation">
        <a class="navigation__link" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            <!--Title-->
            <h1 class="navigation__title"><?php bloginfo( 'name' ); ?></h1>
            <!--Logo-->
            <img class="navigation__img img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/ryba.png">
            <!--Description-->
            <h2 class="navigation__subtitle"><?php bloginfo( 'description' ); ?></h2>
        </a>
		<!--Menu-->
		<div class="navigation__menu">
			<?php
			$args = array(
				'menu_class' => 'navigation__menu__list',
				'container' => 'nav',
				'container_class' => 'navigation__menu__container',
				'depth' => 1,
				'theme_location' => 'title-menu'
			); ?>
			<?php if (has_nav_menu('title-menu')) {
			wp_nav_menu($args);} ?>
		</div>
	</div>
	<div class="navigation__links">
		<ul class="navigation__links__list">
			E-mail:
			<li class="navigation__links__item"><a href="https://groups.google.com/forum/?hl=cs#!forum/info-podebradska-mladez" target="_blank">Informace o akcích</a></li>
			<li class="navigation__links__item"><a href="<?php echo home_url( '/' ); ?>?page_id=3378">Napište somu</a></li>
		</ul>
		<ul class="navigation__links__list">
			Odkazy:
			<li class="navigation__links__item"><a href="http://mladez.evangnet.cz" target="_blank">Mládež ČCE</a></li>
			<li class="navigation__links__item"><a href="http://podebradsky-seniorat.evangnet.cz" target="_blank">Poděbradský seniorát</a></li>
			<li class="navigation__links__item"><a href="http://www.e-cirkev.cz" target="_blank">ČCE</a></li>
			<li class="navigation__links__item"><a href="https://www.evangnet.cz" target="_blank">Evangnet</a></li>
		</ul>
		<p class="navigation__link"><a href="http://podebradska-mladez.evangnet.cz/wp-login.php">Login</a> | <a href="http://podebradska-mladez.evangnet.cz/?page_id=1242">Secure</a></p>
		<p class="navigation__link" ><a href="http://www.dombl.cz" target="_blank">Dominik Bláha</a></p>
	</div>
</div>
