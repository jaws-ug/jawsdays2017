<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('supporter-archive'); ?>>

	<div class="post-thumbnail"><a href="<?php the_permalink(); ?>" rel="bookmark">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'archive-thumb' ); ?>
	<?php else : ?>
		<img src="<?php echo get_template_directory_uri(); ?>/images/no-image.png" alt="no-image" width="148" height="148" />
	<?php endif; ?>
	</a></div>

	<header class="entry-header">
		<?php do_action( 'jawsdays_before_entry_header' ); ?>
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php do_action( 'jawsdays_after_entry_header' ); ?>
	</header><!-- .entry-header -->


</article><!-- #post-## -->
