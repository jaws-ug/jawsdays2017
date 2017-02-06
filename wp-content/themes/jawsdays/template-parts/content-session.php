<?php
/**
 * Template part for displaying supporter posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php do_action( 'jawsdays_before_entry_header' ); ?>
		<?php // カテゴリー
			$trackstext = '';
			$tracks = get_the_terms( $post->ID, 'session_track' );
			if ( $tracks && ! is_wp_error( $tracks ) ) {
				$tracks_array = array();
				foreach ( $tracks as $term ) {
				$tracks_array[] = esc_html( $term->name );
				}
				$trackstext = join( " / ", $tracks_array );
				$trackstext = ' [' . $trackstext . '] ';
			} 
		?>
		<?php the_title( '<h1 class="entry-title">' . $trackstext, '</h1>' ); ?>
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
					$venues_name_array = array();
					$venues_name = '';
					$venues_hash_array = array();
					$venues_hash = '';
					$text = get_the_title();
					$url  = get_the_permalink();
					foreach ( $venues as $term ) {
						$venues_name_array[] = esc_html( $term->name );
						$venues_hash_array[] = '<a href="https://twitter.com/intent/tweet?hashtags=jawsug,jawsdays,' . urlencode( $term->slug ) . '&amp;via=jawsdays&amp;text=' . urlencode( $text ) . '%20|%20JAWS%20DAYS%202017&amp;url=' . urlencode( $url ). '" target="_blank">' . esc_url( '#' . $term->slug ) . '</a>';
					}
					$venues_name = join( " / ", $venues_name_array );
					$venues_hash = join( " / ", $venues_hash_array );
					?>
			<span class="session-meta-parts"><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $venues_name; ?></span>
			<span class="session-meta-parts"><i class="fa fa-hashtag" aria-hidden="true"></i> <?php echo $venues_hash; ?></span>
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
		<?php
			if ( function_exists( 'sharing_display' ) ) {
				sharing_display( '', true );
			}
		?>
		<?php do_action( 'jawsdays_after_entry_header' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php do_action( 'jawsdays_before_entry_content' ); ?>

		<?php the_content(); ?>
		
		<?php
			// 登壇者
			if( have_rows( 'speaker' ) ):
				echo '<h2>' . __( 'Speakers', 'jawsdays' ) . '</h2>' . "\n";
				// loop through the rows of data
				while ( have_rows( 'speaker' ) ) : the_row();
				echo '<div class="speaker">';

					// Image
					$image = get_sub_field( 'image' );
					$size  = 'thumbnail';
					if( $image ) {
						echo '<div class="post-thumbnail"><span>';
						echo wp_get_attachment_image( $image, $size );
						echo '</span></div>' . "\n";
					}

					// Name
					echo '<p class="speaker-name">';
					the_sub_field( 'name' );
					echo '</p>' . "\n";
				?>
				<?php if ( get_sub_field( 'site_url' ) || get_sub_field( 'twitter' ) || get_sub_field( 'facebook' ) || get_sub_field( 'github' ) ) : ?>
				<div class="speaker-sns">
				<?php
					// Blog
					if ( get_sub_field( 'site_url' ) ) {
						echo '<a href="' . esc_url( get_sub_field( 'site_url' ) ) . '" target="_blank"><i class="fa fa-globe"></i></a>';
					}
					// Twitter
					if ( get_sub_field( 'twitter' ) ) {
						echo '<a href="' . esc_url( 'https://twitter.com/' . get_sub_field( 'twitter' ) ) . '" target="_blank"><i class="fa fa-twitter"></i></a>';
					}
					// Facebook
					if ( get_sub_field( 'facebook' ) ) {
						echo '<a href="' . esc_url( 'https://www.facebook.com/' . get_sub_field( 'facebook' ) ) . '" target="_blank"><i class="fa fa-facebook-official"></i></a>';
					}
					// GitHub
					if ( get_sub_field( 'github' ) ) {
						echo '<a href="' . 'https://github.com/' . get_sub_field( 'github' ) . '" target="_blank"><i class="fa fa-github"></i></a>';
					}
				?>
				</div>
				<?php endif; ?>
				<?php if ( get_sub_field( 'group' ) || get_field( 'profile' ) ) : ?>
				<div class="speaker-profile">
				<?php
					// 所属
					if ( get_sub_field( 'group' ) ) {
						echo '<p class="speaker-group">' . __( 'Affiliation:', 'jawsdays' );
						the_sub_field( 'group' );
						echo '</p>' . "\n";
					}
		
					// 自己紹介
					if ( get_sub_field( 'profile' ) ) {
						the_sub_field( 'profile' );
					}
				?>
				</div>
				<?php endif; ?>
				<?php
				echo '</div>' . "\n";

				endwhile;
			
			endif;
		?>

		<?php
			// 主な聴講者
			if ( get_field( 'target' ) ) {
				echo '<h2>' . __( 'Target', 'jawsdays' ) . '</h2>' . "\n";
				the_field( 'target' );
			}

			// スライド資料
			if ( get_field( 'slide_url' ) ) {
				echo '<h2>' . __( 'Materials', 'jawsdays' ) . '</h2>' . "\n";
				the_field( 'slide_url' );
			}

			// その他
			if ( get_field( 'other' ) ) {
				echo '<h2>' . __( 'Other', 'jawsdays' ) . '</h2>' . "\n";
				the_field( 'other' );
			}
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jawsdays' ),
				'after'  => '</div>',
			) );
		?>
		<?php do_action( 'jawsdays_after_entry_content' ); ?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->

