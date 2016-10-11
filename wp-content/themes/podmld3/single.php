<!--HEADER-->
<?php get_header(); ?>
<!--MENU-->
<?php include 'parts/title-menu.php'; ?>
<!--POST-->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="single">
        <!--title-->
        <h1><?php the_title(); ?></h1>
        <!--content-->
        <div class="col-md-5 text">
            <?php the_content(__('Read more...', 'theme')); ?>
        </div>
        <div class="col-md-5 image">
            <?php the_post_thumbnail('blog',array('class'=>'img-responsive')); ?>
        </div>
    </div>
    <?php endwhile; else: ?>
    <div>
        <h1>
            <?php _e('Error 404 - Not Found', 'theme') ?>
        </h1>
        <div class="text">
            <p><?php _e("Stráka, kterou hledáte zde není...", "theme") ?></p>
    <?php endif; ?>
<!--FOOTER-->
<?php get_footer(); ?>