<div class="col-md-2">
    <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
        <!--Title block-->
        <div class="title">
            <!--Title-->
            <h1><?php bloginfo( 'name' ); ?></h1>
            <!--Logo-->
            <img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/img/ryba.png">
            <!--Description-->
            <h2><?php bloginfo( 'description' ); ?></h2>
            <!--Menu-->
            <div class="menu">
                <?php if (has_nav_menu('title-menu')) {
                wp_nav_menu(array('theme_location' => 'title-menu'));} ?>
            </div>
        </div>
        <div class="links">
            <ul>
                E-mail:
                <li><a href="https://groups.google.com/forum/?hl=cs#!forum/info-podebradska-mladez" target="_blank">Informace o akcích</a></li>
                <li><a href="<?php echo home_url( '/' ); ?>?page_id=3378">Napište somu</a></li>
            </ul>
            <ul>
                Odkazy:
                <li><a href="http://mladez.evangnet.cz" target="_blank">Mládež ČCE</a></li>
                <li><a href="http://podebradsky-seniorat.evangnet.cz" target="_blank">Poděbradský seniorát</a></li>
                <li><a href="http://www.e-cirkev.cz" target="_blank">ČCE</a></li>
                <li><a href="https://www.evangnet.cz" target="_blank">Evangnet</a></li>
            </ul>
            <p><a href="http://www.dombl.cz" target="_blank">Dominik Bláha</a> | <a href="http://www.getbootstrap.cz" target="_blank">Bootstrap</a> | <a href="http://www.wordpress.org" target="_blank">Wordpress</a></p>
            <p><a href="http://podebradska-mladez.evangnet.cz/wp-login.php">Login</a> | <a href="http://podebradska-mladez.evangnet.cz/?page_id=1242">Secure</a></p>
        </div>
    </a>
</div>
