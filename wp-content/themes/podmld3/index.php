<?php get_header(); ?>

<?php include "parts/title-menu.php"; ?>

<?php if ( have_posts() ) : ?>
	<?php
	$args = array(
		'post_type'  => 'events',
		'offset'     => 0,
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'pm_end_date',
				'value'   => date( 'Y-m-d h:m' ),
				'compare' => '>',
			)
		)
	); ?>
	<?php $events = get_posts( $args ); ?>
	<?php $event = $events[0]; ?>

	<div class="col-md-5 invitation">
		<div class="invitation__image">
			<a class="invitation__link" href="<?php $event->the_permalink ?>"><?php echo get_the_post_thumbnail( $event, 'blog', array( 'class' => 'img-responsive' ) ); ?></a>
		</div>
		<div class="invitation__text text">
			<?php echo $event->post_content ?>
		</div>
	</div>
	<div class="col-md-4">
		<div class="map">
			<?php $map_post_id = get_post_meta( $event->ID, 'pm_position', true ); ?>
			<?php echo get_post_meta( $map_post_id, 'pm_position', true ) ?>
		</div>
	</div>
	<div class="col-md-5">
		<?php echo do_shortcode( '[pt_view id="ea205c204c"]' ); ?>
	</div>
	<div class="col-md-5">
		<div class="calendar">
			<p class="calendar__popis popis">Další akce:</p>
			<table class="calendar__table">
				<tbody>
				<?php foreach ( array_slice($events, 1) as $item ): ?>
					<?php
					$start = strtotime(get_post_meta($item->ID, 'pm_start_date', true));
					$end = strtotime(get_post_meta($item->ID, 'pm_end_date', true));
					if(date('m', $start) == date('m', $end)){
						$date = date('j.', $start) . ' - ' . date('j. n. Y', $end);
					} else {
						$date = date('j. n.', $start) . ' - ' . date('j. n. Y', $end);
					}
					?>
					<tr>
						<td class="calendar__name"><?php echo $item->post_title; ?></td>
						<td class="calendar__date"><?php echo $date; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

<?php endif; ?>
<?php wp_reset_query(); ?>


<?php get_footer(); ?>