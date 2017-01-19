<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package JAWSDAYS
 */

?>

		<?php do_action( 'jawsdays_after_content' ); ?>
	</div><!-- #content -->

	<?php // Supporter slide ?>
	<?php supporter_slide(); ?>

	<?php if ( is_front_page() ) : ?>
		<?php if ( is_active_sidebar( 'footer-widgets-area' ) ) : ?>
		<section id="footer-widgets-area" class="footer-section footer-widgets-area">
			<?php dynamic_sidebar( 'footer-widgets-area' ); ?>
		</section><!-- #footer-widgets-area -->
		<?php endif; ?>
	<?php endif; ?>

	<?php
		$footer_section_link_page = get_theme_mod( 'footer_section_link' );
		if ( ! empty( $footer_section_link_page ) ) : 
	?>
	<section id="jawsdays-contact-box" class="footer-section jawsdays-contact-box"><div class="inner">
		<?php
			$footer_section_title = get_theme_mod( 'footer_section_title', sprintf(
				esc_html__( 'To participate in the JAWS DAYS %d', 'jawsdays' ),
				2017
			) );
		?>
		<p class="contact-text" id="jawsdays-contact-title"><?php echo esc_html( $footer_section_title ); ?></p>
		<?php
			$footer_section_btn_text  = get_theme_mod( 'footer_section_btn', __( 'Tickets', 'jawsdays' ) );
		?>
		<p class="contact-button"><a href="<?php echo get_permalink( $footer_section_link_page ); ?>" id="jawsdays-contact-btn-text"><?php echo esc_html( $footer_section_btn_text ); ?></a></p>
		<div class="contact-other-text" id="jawsdays-contact-other-text">
		<?php
			$footer_section_other = get_theme_mod( 'footer_section_other' );
			if ( ! empty( $footer_section_other ) ) {
				echo '<p>' . "\n";
				echo nl2br( $footer_section_other );
				echo '</p>' . "\n";
			}
		?>
		</div>
	</div></section>
	<?php endif; ?>

	<footer id="colophon" class="site-footer" role="contentinfo"><div class="inner">
		<?php do_action( 'jawsdays_before_footer' ); ?>
		<nav id="footer-navigation" class="footer-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'footer_menu' ) ); ?>
		</nav>
		<div class="site-info">
			<div class="social-button">
				<ul>
					<li><a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode( get_bloginfo( 'name' ) ); ?>&url=<?php echo rawurlencode( home_url( '/' ) ); ?>&hashtags=jawsdays, jawsug&via=jawsdays" target="_blank">
<i class="fa fa-twitter-square"></i></a></li>
					<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( home_url( '/' ) ); ?>" target="_blank"><i class="fa fa-facebook-official"></i></a></li>
				</ul>
			</div>
			<p class="copyright">Copyright &copy; AWS User Group Japan. All rights reserved.</p>
		</div><!-- .site-info -->
		<?php do_action( 'jawsdays_after_footer' ); ?>
	</div></footer><!-- #colophon -->
</div><!-- #page -->

<?php do_action( 'jawsdays_after_body' ); ?>

<?php wp_footer(); ?>

</body>
</html>
