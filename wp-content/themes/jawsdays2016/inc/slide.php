<?php
/**
 * Supporter slide.
 *
 * @package JAWSDAYS 2016
 */

if ( ! function_exists( 'supporter_slide' ) ) :
function supporter_slide() {
	$args = array(
		'post_type'      => 'supporter',
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'orderby'        => 'date',
	);
	$the_query = new WP_Query( $args );
	?>
	<?php if ( $the_query->have_posts() ) : ?>
	<section id="jawsdays-supporter" class="footer-section jawsdays-supporter">
		<ul class="bxslider jawsdays-supporter-slider">
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<li><a href="<?php the_field( '_supporter_url' ); ?>" target="_blank">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'slide-thumb' ); ?>
				<?php else : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/no-image.png" alt="no-image" width="148" height="148" />
				<?php endif; ?>
			</a></li>
		<?php endwhile; ?>
		</ul>
	</section>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
<?php
}
endif;
