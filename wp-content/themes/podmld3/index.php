<?php get_header(); ?>

<?php include "parts/title-menu.php"; ?>

<?php if ( have_posts() ) : ?>
	<?php
	$args = array(
		'post_type'  => 'events',
		'post_count' => 1,
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'pm_end_date',
				'value'   => date( 'Y-m-d h:m' ),
				'compare' => '>',
			)
		)
	); ?>
	<?php query_posts( $args ); ?>
	<?php the_post(); ?>

	<div class="col-md-5 invitation">
		<div class="image">
			<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'blog', array( 'class' => 'img-responsive' ) ); ?></a>
		</div>
		<div class="text">
			<?php the_content() ?>
		</div>
	</div>
	<div class="col-md-4">
		<div class="map">
			<?php $map_post_id = get_post_meta( get_the_ID(), 'pm_position', true ); ?>
			<?php echo get_post_meta( $map_post_id, 'pm_position', true ) ?>
		</div>
	</div>
	<div class="col-md-2">
		<?php echo do_shortcode( '[pt_view id="ea205c204c"]' ); ?>
	</div>
	<div class="col-md-3">
		<div class="calendar">
			<p class="popis">Další akce:</p>
			<?php echo do_shortcode( '[pt_view id="d6f2fd52d0"]' ); ?>
		</div>
	</div>

<?php endif; ?>
<?php wp_reset_query(); ?>


<?php get_footer(); ?>