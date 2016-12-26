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
				$terms = get_terms( 'supporter_type', array( 'orderby' => 'term_order', 'order' => 'ASC' ) );
				if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$args = array(
							'posts_per_page' => -1,
							'post_type'      => 'supporter',
							'orderby'        => 'date',
							'order'          => 'ASC',
							'tax_query' => array(
								array(
									'taxonomy' => 'supporter_type',
									'terms'    => $term->term_id,
								),
							),
							
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							echo '<section class="supporter_type">' . "\n";
							echo '<h2 class="section-title">' . esc_html( $term->name ) . '</h2>' . "\n";
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								get_template_part( 'template-parts/content', 'archive-supporter' );
							}
							echo '</section>' . "\n";
						}
						wp_reset_postdata();
					}
				}
			?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
