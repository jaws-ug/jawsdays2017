<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package JAWSDAYS
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
				$terms = get_terms( 'session_track', array(
					'orderby' => 'term_order',
					'order'   => 'ASC',
					'hide_empty' => false,
				) );
				if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {

						echo '<section class="session_type">' . "\n";
						echo '<h2 class="section-title">' . esc_html( $term->name ) . '</h2>' . "\n";
						echo '<div class="section-description">' . term_description( $term->term_id, 'session_track' ) . '</div>' . "\n";

						$args = array(
							'posts_per_page' => -1,
							'post_type'      => 'session',
							'orderby'        => 'menu_order date',
							'order'          => 'ASC',
							'tax_query' => array(
								array(
									'taxonomy' => 'session_track',
									'terms'    => $term->term_id,
								),
							),
							
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							echo '<div class="section-posts">';
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								get_template_part( 'template-parts/content', 'archive-session' );
							}
							echo '</div>' . "\n";
						} else {
							echo '<p class="no-session">Comming soon...</p>' . "\n";;
						}
						wp_reset_postdata();
						echo '</section>' . "\n";
					}
				}
			?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
