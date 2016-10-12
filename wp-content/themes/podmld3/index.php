<?php get_header(); ?>

<?php include "parts/title-menu.php"; ?>

<?php if ( have_posts() ) : ?>
	<?php
	$args = array(
		'post_type'  => 'events',
		'offset' => 1,
		'meta_query' => array(
			array(
				'key'     => 'pm_end_date',
				'value'   => date( 'Y-m-d h:m' ),
				'compare' => '>',
			)
		)
	); ?>
	<?php $event = get_posts($args)[0]; ?>

	<div class="col-md-5 invitation">
		<div class="image">
			<a href="<?php $event->the_permalink ?>"><?php echo get_the_post_thumbnail($event, 'blog', array( 'class' => 'img-responsive' ) ); ?></a>
		</div>
		<div class="text">
			<?php echo $event->post_content ?>
		</div>
	</div>
	<div class="col-md-4">
		<div class="map">
			<?php $map_post_id = get_post_meta( $event->ID, 'pm_position', true ); ?>
			<?php echo get_post_meta( $map_post_id, 'pm_position', true ) ?>
		</div>
	</div>
	<div class="col-md-2">
		<?php echo do_shortcode( '[pt_view id="ea205c204c"]' ); ?>
	</div>
	<div class="col-md-3">
		<div class="calendar">
			<p class="popis">Další akce:</p>
			<?php get_posts() ?>
			<table>
				<tbody>
				<tr>
					<td class="name">Podzimní SDM</td>
					<td class="date">11. - 13. 11.</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

<?php endif; ?>
<?php wp_reset_query(); ?>


<?php get_footer(); ?>