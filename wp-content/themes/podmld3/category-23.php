<?php get_header();
include 'parts/title-menu.php'; ?>
		<div class="col-md-5">
			<?php if (have_posts()) : while(have_posts()) : $i++; if(($i % 2) == 0) : $wp_query->next_post(); else :    the_post();
				get_template_part( 'content', 'som' );
			endif; endwhile; else: ?>
				<div>Alternate content</div>
			<?php endif; ?>
		</div>

		<?php $i = 0; rewind_posts(); ?>

		<div class="col-md-5">
			<?php if (have_posts()) : while(have_posts()) : $i++; if(($i % 2) !== 0) : $wp_query->next_post(); else : the_post();
				get_template_part( 'content', 'som' );
			 endif; endwhile; else: ?>
				<div>Alternate content</div>
			<?php endif; ?>
		</div>
<?php get_footer(); ?>