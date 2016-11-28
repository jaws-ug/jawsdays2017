<?php
/**
 * Template part for displaying single posts.
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

		<div class="entry-meta">
			<?php jawsdays_posted_on(); ?>
		</div><!-- .entry-meta -->
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
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jawsdays' ),
				'after'  => '</div>',
			) );
		?>
		<?php do_action( 'jawsdays_after_entry_content' ); ?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->

