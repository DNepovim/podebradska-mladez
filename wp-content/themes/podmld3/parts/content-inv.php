<div class="col-md-5 content-som content">
	<!--THUMBNAIL-->
	<?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail('thumbnail',array('class' => 'img-responzive image'));
	}
	?>

	<!--TITLE-->
	<span class="title">
		<?php the_title(); ?>
	</span>

	<!--CONTENT-->
	<div class="text">
		<?php echo the_content();?>
	</div>
	<div style="clear:both;"></div>
</div>