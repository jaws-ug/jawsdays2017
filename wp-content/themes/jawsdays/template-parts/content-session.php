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
	<div class="post-thumbnail"><span>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'archive-thumb' ); ?>
	<?php else : ?>
		<img src="<?php echo get_template_directory_uri(); ?>/images/no-image.png" alt="no-image" width="148" height="148" />
	<?php endif; ?>
	</span></div>

	<header class="entry-header">
		<?php do_action( 'jawsdays_before_entry_header' ); ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php
			// トラック
			if ( get_field( 'track' ) ) {
				echo '<h2>' . __( 'Track:', 'jawsdays' );
				the_field( 'track' );
				echo '</h2>' . "\n";
			}
		?>
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
