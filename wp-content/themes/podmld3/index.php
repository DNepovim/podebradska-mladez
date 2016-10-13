<!DOCTYPE html><html <?php language_attributes(); ?>><head><meta charset="<?php echo bloginfo('charset'); ?>"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?php echo wp_title(" | ", true, "right").bloginfo('name'); ?></title><meta name="description" content="<?php echo bloginfo('description'); ?>"><meta name="author" content="Dominik Bláha / www.dombl.cz"><link rel="shortcut icon" href="<?php echo htmlspecialchars(get_template_directory_uri(), ENT_QUOTES, 'UTF-8'); ?>/favicon.ico" type="image/x-icon"><link rel="stylesheet " href="<?php echo htmlspecialchars(get_template_directory_uri(), ENT_QUOTES, 'UTF-8'); ?>/dist/styles.min.css" media="screen"><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400&subset=latin-ext" rel="stylesheet"><?php wp_head(); ?></head><body <?php body_class(); ?>><?php require_once 'analyticstracking.php'; ?><div class="container"><div class="col-md-2"><div class="navigation"><a href="<?php echo htmlspecialchars(home_url( '/' ), ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(esc_attr(get_bloginfo('name','display')), ENT_QUOTES, 'UTF-8'); ?>" rel="home" class="navigation__link"><h1 class="navigation__title"><?php echo htmlspecialchars(bloginfo( 'name' ), ENT_QUOTES, 'UTF-8'); ?></h1><img src="<?php echo htmlspecialchars(get_stylesheet_directory_uri(), ENT_QUOTES, 'UTF-8'); ?>/dist/images/ryba.png" class="navigation__img img-responsive"><h2 class="navigation__subtitle"><?php echo htmlspecialchars(bloginfo( 'description' ), ENT_QUOTES, 'UTF-8'); ?></h2><div class="navigation__menu"><?php
$args = array(
  'menu_class' => 'navigation__menu__list',
  'container' => 'nav',
  'container_class' => 'navigation__menu__container',
  'depth' => 1,
  'theme_location' => 'title-menu'
); ?><?php if (has_nav_menu('title-menu')) : ?><?php wp_nav_menu($args); ?><?php endif; ?></div></a></div><div class="navigation__links"><ul class="navigation__links__list">E-mail:<li class="navigation__links__item"><a href="https://groups.google.com/forum/?hl=cs#!forum/info-podebradska-mladez" target="_blank"><?php echo htmlspecialchars('Informace o akcích', ENT_QUOTES, 'UTF-8'); ?></a></li><li class="navigation__links__item"><a href="<?php echo htmlspecialchars(home_url( '/' ), ENT_QUOTES, 'UTF-8'); ?>?page_id=3378"><?php echo htmlspecialchars('Napište somu', ENT_QUOTES, 'UTF-8'); ?></a></li></ul><ul class="navigation__links__list">Odkazy:<li class="navigation__links__item"><a href="http://mladez.evangnet.cz" target="_blank"><?php echo htmlspecialchars('Mládež ČCE', ENT_QUOTES, 'UTF-8'); ?></a></li><li class="navigation__links__item"><a href="http://podebradsky-seniorat.evangnet.cz" target="_blank"><?php echo htmlspecialchars('Poděbradský seniorát', ENT_QUOTES, 'UTF-8'); ?></a></li><li class="navigation__links__item"><a href="http://www.e-cirkev.cz" target="_blank"><?php echo htmlspecialchars('ČCE', ENT_QUOTES, 'UTF-8'); ?></a></li><li class="navigation__links__item"><a href="https://www.evangnet.cz" target="_blank"><?php echo htmlspecialchars('Evangnet', ENT_QUOTES, 'UTF-8'); ?></a></li></ul><p class="navigation__link"><a href="http://podebradska-mladez.evangnet.cz/wp-login.php"><?php echo htmlspecialchars('Login', ENT_QUOTES, 'UTF-8'); ?></a><a href="http://podebradska-mladez.evangnet.cz/?page_id=1242"><?php echo htmlspecialchars('Secure', ENT_QUOTES, 'UTF-8'); ?></a></p><p class="navigation__link"><a href="http://www.dombl.cz" target="_blank"><?php echo htmlspecialchars('Dominik Bláha', ENT_QUOTES, 'UTF-8'); ?></a></p></div></div><?php if ( have_posts() ) : ?><?php
$args = array(
  'post_type'  => 'events',
  'offset'     => 0,
  'order' => 'ASC',
  'meta_query' => array(
      array(
          'key'     => 'pm_end_date',
          'value'   => date( 'Y-m-d h:m' ),
          'compare' => '>',
      )
  )
); ?><?php $events = get_posts($args); ?><?php $event = $events[0]; ?><div class="col-md-5 invitation"><div class="invitation__image"><a href="<?php echo $event->the_permalink ?>" class="invitation__link"><?php echo get_the_post_thumbnail($event, 'blog', array('class' => 'img-responsive')); ?></a></div><div class="invitation__text text"><?php echo htmlspecialchars($event -> post_content, ENT_QUOTES, 'UTF-8'); ?></div></div><div class="col-md-4"><div class="map"><?php $map_post_id = get_post_meta($event->ID,'pm_position',true); ?><?php echo get_post_meta($map_post_id, 'pm_position', true); ?></div></div><div class="col-md-5"></div><div class="col-md-5"><div class="calendar"><p class="calendar__popis"><?php echo htmlspecialchars('popisDalší akce:', ENT_QUOTES, 'UTF-8'); ?></p><table class="calendar__table"><tbody></tbody><?php foreach (array_slice($events, 1) as $item ): ?><?php
$start = strtotime(get_post_meta($item->ID, 'pm_start_date', true));
$end = strtotime(get_post_meta($item->ID, 'pm_end_date', true));
if(date('m', $start) == date('m', $end)){
  $date = date('j.', $start) . ' - ' . date('j. n. Y', $end);
} else {
  $date = date('j. n.', $start) . ' - ' . date('j. n. Y', $end);
}
?><tr><td class="calendar__name"><?php echo htmlspecialchars($item->post_title, ENT_QUOTES, 'UTF-8'); ?></td><td class="calendar__date"><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></td></tr><?php endforeach; ?></table></div></div><?php endif; ?><?php wp_reset_query(); ?></div></body>
</html>