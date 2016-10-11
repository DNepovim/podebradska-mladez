<!--HEADER-->
<?php get_header(); ?>
<!--MENU-->
<?php include 'parts/secret-menu.php' ?>
<!--PAGE-->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="col-md-7 page single">
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

<!--DRIVE-->
<div class="col-md-3 drive">
	<a class="vtajne" href="https://drive.google.com/folderview?id=0B8vvlzr1LcJZS2FneVhzZzZrZDQ&usp=sharing">
<h1>Google Drive</h1></a>
    <iframe src="https://drive.google.com/embeddedfolderview?id=0B8vvlzr1LcJZS2FneVhzZzZrZDQ#list" height="600" frameborder="0"  scrolling=no></iframe></div>

<!--FOOTER-->
<?php get_footer(); ?>
