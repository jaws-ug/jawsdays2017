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

		<?php
			// 会場：時間
			$track      = get_field( 'track' );
			$start_time = get_field( 'start_time' );
			$end_time   = get_field( 'end_time' );
			if ( $track || $start_time || $end_time ) :
		?>
		<div class="session-meta"><?php
			// 会場
				$venues = get_the_terms( $post->ID, 'session_venue' );
				if ( $venues && ! is_wp_error( $venues ) ) : 
					$venues_array = array();
					foreach ( $venues as $term ) {
					$venues_array[] = esc_html( $term->name );
					}
					$venuestext = join( " / ", $venues_array );
					?>
			<span class="session-meta-parts"><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $venuestext; ?></span>
			<?php
				endif;

			// 時間
			if ( $start_time || $end_time) {
				echo '<i class="fa fa-clock-o" aria-hidden="true"></i> ';
			}
			if ( $start_time ) {
				the_field( 'start_time' );
				echo '〜';
			}
			if ( $end_time ) {
				the_field( 'end_time' );
			}
		?></div>
		<?php endif; // 会場：時間 ?>
		<?php // カテゴリー・難易度 ?>
		<div class="session-meta">
			<?php // カテゴリー
				$tracks = get_the_terms( $post->ID, 'session_track' );
				if ( $tracks && ! is_wp_error( $tracks ) ) : 
					$tracks_array = array();
					foreach ( $tracks as $term ) {
					$tracks_array[] = esc_html( $term->name );
					}
					$trackstext = join( " / ", $tracks_array );
					?>
			<span class="session-meta-parts"><i class="fa fa-file" aria-hidden="true"></i> <?php echo $trackstext; ?></span>
			<?php endif; ?>

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
		</div>
		<?php do_action( 'jawsdays_after_entry_header' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<p class="entry-more"><a href="<?php the_permalink(); ?>"><?php _e( 'Read more &raquo;', 'jawsdays' ) ?></a></p>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
