<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<?php do_action( 'jawsdays_before_primary' ); ?>
		<main id="main" class="site-main" role="main">
			<div class="main-image">
				<?php the_custom_header_markup(); ?>
			</div>
		</main><!-- #main -->
		<?php do_action( 'jawsdays_after_primary' ); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>
