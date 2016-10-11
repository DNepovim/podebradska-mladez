<?php get_header();
include 'parts/title-menu.php'; ?>
<div class="invitation">
	<div class="col-md-5">
		<?php if (have_posts()) : while(have_posts()) : $i++; if(($i % 2) == 0) : $wp_query->next_post(); else : the_post(); ?>
				<a href="<?php the_permalink() ?>"><?php the_post_thumbnail('blog',array('class'=>'img-responsive')); ?></a>
		<?php endif; endwhile; else: ?>
			<div>Alternate content</div>
		<?php endif; ?>
	</div>

	<?php $i = 0; rewind_posts(); ?>

	<div class="col-md-5">
		<?php if (have_posts()) : while(have_posts()) : $i++; if(($i % 2) !== 0) : $wp_query->next_post(); else : the_post(); ?>
		<a href="<?php the_permalink() ?>"><?php the_post_thumbnail('blog',array('class'=>'img-responsive')); ?></a>
		<?php endif; endwhile; else: ?>
			<div>Alternate content</div>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>