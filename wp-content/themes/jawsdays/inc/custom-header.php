<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * @link http://codex.wordpress.org/Custom_Headers
 *
 * @package JAWSDAYS
 */

/**
 * Set up the WordPress core custom header feature.
 */
function jawsdays_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'jawsdays_custom_header_args', array(
		'default-image'      => get_parent_theme_file_uri( '/images/default-image.png' ),
		'width'              => 870,
		'height'             => 335,
        'flex-height'        => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/images/default-image.png',
			'thumbnail_url' => '%s/images/default-image.png',
			'description'   => __( 'Default Header Image', 'jawsdays' ),
		),
	) );

}
add_action( 'after_setup_theme', 'jawsdays_custom_header_setup' );
