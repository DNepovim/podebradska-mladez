<div class="content-som content">
	<!--THUMBNAIL-->
	<?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail('thumbnail',array('class' => 'img-responzive image'));
	}
	?>

	<!--TITLE-->
	<strong>
		<?php the_title(); ?>
	</strong>

	<!--CONTENT-->
		<?php echo the_content();?>
	<div style="clear:both;"></div>
</div>