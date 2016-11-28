<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS 2016
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<?php do_action( 'jawsdays_before_page_header' ); ?>
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'jawsdays' ); ?></h1>
		<?php do_action( 'jawsdays_after_page_header' ); ?>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php do_action( 'jawsdays_before_page_content' ); ?>
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'jawsdays' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'jawsdays' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'jawsdays' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
		<?php do_action( 'jawsdays_after_page_content' ); ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
