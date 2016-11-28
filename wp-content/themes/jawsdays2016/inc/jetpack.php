<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.me/
 *
 * @package JAWSDAYS 2016
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.me/support/infinite-scroll/
 * See: https://jetpack.me/support/responsive-videos/
 */
function jawsdays_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'jawsdays_infinite_scroll_render',
		'footer'    => 'page',
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );
} // end function jawsdays_jetpack_setup
add_action( 'after_setup_theme', 'jawsdays_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function jawsdays_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
		    get_template_part( 'template-parts/content', 'search' );
		else :
		    get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}
} // end function jawsdays_infinite_scroll_render

/**
 * Moving Sharing Icons.
 *
 * @link http://jetpack.me/2013/06/10/moving-sharing-icons/
 */
function jptweak_remove_share() {
	remove_filter( 'the_content', 'sharing_display',19 );
	remove_filter( 'the_excerpt', 'sharing_display',19 );
	if ( class_exists( 'Jetpack_Likes' ) ) {
		remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	}
}
 
add_action( 'loop_start', 'jptweak_remove_share' );

/*
 * パブリサイズ共有でサフィックス追加
 * 参考 http://blog.tenshinbo.net/2013/12/12/p-57/
 * original
 * $this->default_prefix = Publicize_Util::build_sprintf( array(
 * 	apply_filters( 'wpas_default_prefix', $this->default_prefix ),
 * 	'url',
 * ) );
 */

add_filter( 'wpas_default_suffix', 'jawsdays_wpas_default_suffix' );
function jawsdays_wpas_default_suffix( $suffix ) {
	$suffix = $suffix . " #jawsdays #jawsug";
	return $suffix;
}

add_action( 'publicize_save_meta', 'jawsdays_publicize_save_meta', 10, 4);
function jawsdays_publicize_save_meta( $submit_post, $post_id, $service_name, $connection ) {
	$suffix = " #jawsdays #jawsug";
	$title  = get_the_title( $post_id );
	$link   = wp_get_shortlink( $post_id );
	$publicize_custom_message = get_post_meta( $post_id, '_wpas_mess', true );
	if ( empty( $publicize_custom_message ) ) {
		$publicize_custom_message = sprintf(
			"%s %s %s",
			$title,
			$link,
			$suffix
		);
	} else {
		if ( strpos( $publicize_custom_message, $title ) === false ) {
			$publicize_custom_message = $publicize_custom_message . $title;
		}
		if ( strpos( $publicize_custom_message, $link ) === false ) {
			$publicize_custom_message = $publicize_custom_message . $link;
		}
		if ( strpos( $publicize_custom_message, $suffix ) === false ) {
			$publicize_custom_message = $publicize_custom_message . $suffix;
		}
	}
	update_post_meta($post_id, '_wpas_mess', $publicize_custom_message);
}