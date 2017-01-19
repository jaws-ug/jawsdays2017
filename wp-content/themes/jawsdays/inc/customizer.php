<?php
/**
 * JAWSDAYS Theme Customizer.
 *
 * @package JAWSDAYS
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function jawsdays_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	/**
	 * Theme options.
	 */
	$wp_customize->add_section( 'jawsdays_theme_options', array(
		'title'    => __( 'Theme Options', 'jawsdays' ),
		'priority'   => 160,
	) );

	// title
	$wp_customize->add_setting( 'footer_section_title', array(
		'default'           => sprintf(
				esc_html__( 'To participate in the JAWS DAYS %d', 'jawsdays' ),
				2017
			),
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'footer_section_title', array(
			'label'    => __( 'Footer Section Title', 'jawsdays' ),
			'section'  => 'jawsdays_theme_options',
			'settings' => 'footer_section_title',
			'type'     => 'text',
		)
	);
	$wp_customize->selective_refresh->add_partial( 'footer_section_title', array(
			'selector'            => '#jawsdays-contact-title',
		)
	);

	// page
	$wp_customize->add_setting( 'footer_section_link', array(
		'default'           => false,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'footer_section_link', array(
			'label'    => __( 'Button Link Page', 'jawsdays' ),
			'section'  => 'jawsdays_theme_options',
			'settings' => 'footer_section_link',
			'type'     => 'dropdown-pages',
		)
	);

	// button text
	$wp_customize->add_setting( 'footer_section_btn', array(
		'default'           => __( 'Tickets', 'jawsdays' ),
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'footer_section_btn', array(
			'label'    => __( 'Button Text', 'jawsdays' ),
			'section'  => 'jawsdays_theme_options',
			'settings' => 'footer_section_btn',
			'type'     => 'text',
		)
	);
	$wp_customize->selective_refresh->add_partial( 'footer_section_btn', array(
			'selector'            => '#jawsdays-contact-btn-text',
		)
	);

	// Other text
	$wp_customize->add_setting( 'footer_section_other', array(
		'default'           => '',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'footer_section_other', array(
			'label'    => __( 'Other Text', 'jawsdays' ),
			'section'  => 'jawsdays_theme_options',
			'settings' => 'footer_section_other',
			'type'     => 'textarea',
		)
	);
	$wp_customize->selective_refresh->add_partial( 'footer_section_other', array(
			'selector'            => '#jawsdays-contact-other-text',
		)
	);
}
add_action( 'customize_register', 'jawsdays_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function jawsdays_customize_preview_js() {
	wp_enqueue_script( 'jawsdays_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'jawsdays_customize_preview_js' );

