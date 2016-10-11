<!--HEADER-->
<?php get_header(); ?>
<!--MENU-->
<?php include 'parts/title-menu.php' ?>
<!--PAGE-->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="col-md-10 page single">
        <!--title-->
        <h1><?php	the_title(); ?></h1>
        <!--content-->
        <div class="text">
            <?php the_content(); ?>
        </div>
    </div>
<?php endwhile; else: ?>
    <div class="col-md-10 page single">
        <h1><?php _e('Error 404 - Not Found', 'theme') ?></h1>
        <div class="text">
            <p><?php _e("Stránka, kterou hledáte, zde není...", "theme") ?></p>
        </div>
    </div>
<?php endif; ?>

<!--FOOTER-->
<?php get_footer(); ?>