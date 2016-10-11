<div class="col-md-5 invitation">
    <div class="image">
        <?php if (have_posts()) : ?>
            <?php query_posts('post_type=events'); ?>
            <?php the_post() ?>
            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('blog',array('class'=>'img-responsive')); ?></a>
    </div>
    <div class="text">
            <?php the_content() ?>
        <?php endif; ?>
	    <?php wp_reset_query(); ?>
    </div>
</div>
