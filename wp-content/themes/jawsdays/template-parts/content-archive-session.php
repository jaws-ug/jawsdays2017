<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'session-archive' ); ?>>

	<header class="entry-header">
		<?php do_action( 'jawsdays_before_entry_header' ); ?>
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="session-meta">
			<?php // 難易度
				$levels = get_the_terms( $post->ID, 'session_level' );
				if ( $levels && ! is_wp_error( $levels ) ) : 
					$levels_array = array();
					foreach ( $levels as $term ) {
					$levels_array[] = esc_html( $term->name );
					}
					$levelstext = join( " / ", $levels_array );
					?>
			<span class="session-meta-parts"><i class="fa fa-star" aria-hidden="true"></i> <?php echo $levelstext; ?></span>
			<?php endif; ?>
			<?php // 会場
				$venues = get_the_terms( $post->ID, 'session_venue' );
				if ( $venues && ! is_wp_error( $venues ) ) : 
					$venues_array = array();
					foreach ( $venues as $term ) {
						$venues_array[] = esc_html( $term->name );
					}
						$venuestext = join( " / ", $venues_array );
					?>
			<span class="session-meta-parts"><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $venuestext; ?></span>
			<?php endif; ?>

			<?php // 時間
				$start_time = get_field( 'start_time' );
				$end_time   = get_field( 'end_time' );
				if ( $start_time || $end_time) {
					echo '<span class="session-meta-parts"><i class="fa fa-clock-o" aria-hidden="true"></i> ';
				}
				if ( $start_time ) {
					the_field( 'start_time' );
					echo '〜';
				}
				if ( $end_time ) {
					the_field( 'end_time' );
				}
				if ( $start_time || $end_time) {
					echo '</span>';
				}
			?>
		</div>
		<?php do_action( 'jawsdays_after_entry_header' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<p class="entry-more"><a href="<?php the_permalink(); ?>"><?php _e( 'Read more &raquo;', 'jawsdays' ) ?></a></p>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
