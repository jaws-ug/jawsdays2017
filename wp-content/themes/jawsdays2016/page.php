<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS 2016
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<?php do_action( 'jawsdays_before_primary' ); ?>
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<header class="page-header">
					<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					<?php if ( get_field( 'sub_title' ) ) : ?>
					<div class="taxonomy-description"><?php the_field( 'sub_title' ); ?></div>
					<?php endif; ?>
				</header><!-- .page-header -->

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
		<?php do_action( 'jawsdays_after_primary' ); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>
