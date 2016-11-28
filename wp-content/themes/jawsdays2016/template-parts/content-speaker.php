<?php
/**
 * Template part for displaying supporter posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS 2016
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

		<?php if ( get_field( 'twitter' ) || get_field( 'facebook' ) || get_field( 'github' ) ) : ?>
		<div class="entry-sns">
		<?php
			// Twitter
			if ( get_field( 'twitter' ) ) {
				echo '<a href="' . esc_url( 'https://twitter.com/' . get_field( 'twitter' ) ) . '" target="_blank"><i class="fa fa-twitter"></i></a>';
			}
			// Facebook
			if ( get_field( 'facebook' ) ) {
				echo '<a href="' . esc_url( 'https://www.facebook.com/' . get_field( 'facebook' ) ) . '" target="_blank"><i class="fa fa-facebook-official"></i></a>';
			}
			// GitHub
			if ( get_field( 'github' ) ) {
				echo '<a href="' . 'https://github.com/' . get_field( 'github' ) . '" target="_blank"><i class="fa fa-github"></i></a>';
			}
		?>
		</div>
		<?php endif; ?>

		<?php if ( get_field( 'group' ) || get_field( 'profile' ) ) : ?>
		<div class="entry-profile">
		<?php
			// 所属
			if ( get_field( 'group' ) ) {
				echo '<p>' . __( 'Affiliation:', 'jawsdays' );
				the_field( 'group' );
				echo '</p>' . "\n";
			}

			// 自己紹介
			if ( get_field( 'profile' ) ) {
				the_field( 'profile' );
			}
		?>
		</div>
		<?php endif; ?>

		<?php
			if ( function_exists( 'sharing_display' ) ) {
				sharing_display( '', true );
			}
		?>
		<?php do_action( 'jawsdays_after_entry_header' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php do_action( 'jawsdays_before_entry_content' ); ?>

		<?php
			// セッションタイトル
			if ( get_field( 'session_title' ) ) {
				echo '<h1>';
				the_field( 'session_title' );
				echo '</h1>' . "\n";
			}

			// トラック
			if ( get_field( 'track' ) ) {
				echo '<h2>' . __( 'Track:', 'jawsdays' );
				the_field( 'track' );
				echo '</h2>' . "\n";
			}
		?>
		<?php the_content(); ?>
		
		<?php
			// 主な聴講者
			if ( get_field( 'target' ) ) {
				echo '<h3>' . __( 'Target', 'jawsdays' ) . '</h3>' . "\n";
				the_field( 'target' );
			}

			// スライド資料
			if ( get_field( 'slide_url' ) ) {
				echo '<h3>' . __( 'Materials', 'jawsdays' ) . '</h3>' . "\n";
				the_field( 'slide_url' );
			}

			// その他
			if ( get_field( 'other' ) ) {
				echo '<h3>' . __( 'Other', 'jawsdays' ) . '</h3>' . "\n";
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

