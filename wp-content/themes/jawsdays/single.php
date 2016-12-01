<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package JAWSDAYS
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<?php do_action( 'jawsdays_before_primary' ); ?>
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php
				if ( is_singular( array( 'session', 'supporter' ) ) ) :
					$post_type = get_query_var( 'post_type' );
					if ( is_array( $post_type ) )
						$post_type = reset( $post_type );
					$post_type_obj = get_post_type_object( $post_type );
					$post_type_name = $post_type_obj->labels->name;

			?>
				<header class="page-header">
					<h1 class="page-title"><?php echo esc_html( $post_type_name ); ?></h1>
				</header><!-- .page-header -->
			<?php endif; ?>
			<?php
				if ( is_singular( 'supporter' ) ) {
					get_template_part( 'template-parts/content', 'supporter' );
				} elseif ( is_singular( 'session' ) ) {
					get_template_part( 'template-parts/content', 'session' );
				} else {
					get_template_part( 'template-parts/content', 'single' );
				}
			?>

			<?php the_post_navigation( array( 'prev_text' => '<span class="meta-nav">&larr;</span>&nbsp;%title', 'next_text' => '%title&nbsp;<span class="meta-nav">&rarr;</span>' ) ); ?>

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
