<?php
/**
 * Plugin Name: JAWSDAYS Setting
 * Plugin URI:  https://github.com/jaws-ug/jawsdays
 * Description: JAWSDAYS Settings.
 * Version:     2017
 * Author:      IGARASHI Kazue
 * Author URI:  http://gatespace.jp/
 * License:     GPLv2
 * Text Domain: jawsdays
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 IGARASHI Kazue ( http://gatespace.jp/ )
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



define( 'JAWSDAYS_URL',  plugins_url( '', __FILE__ ) );
define( 'JAWSDAYS_PATH', dirname( __FILE__ ) );

$jawsdays = new JAWSDAYS_Setting();
$jawsdays->register();

class JAWSDAYS_Setting {

private $version = '';
private $langs   = '';

function __construct() {
	$data = get_file_data(
		__FILE__,
		array( 'ver' => 'Version', 'langs' => 'Domain Path' )
	);
	$this->version = $data['ver'];
	$this->langs   = $data['langs'];
}

public function register() {
	add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
}

public function plugins_loaded() {
	load_plugin_textdomain(
		'jawsdays',
		false,
		dirname( plugin_basename( __FILE__ ) ) . $this->langs
	);

	// Register Custom Post Type
	add_action( 'init', array( $this, 'custom_post_type_session' ), 0 );
	add_action( 'init', array( $this, 'custom_post_type_supporter' ), 0 );
	// Term sort for Custom Post Type
	add_action( 'restrict_manage_posts', array( $this, 'jaws_restrict_manage_posts' ) );
	// Query pre_get_posts
	add_action( 'pre_get_posts', array( $this, 'jaws_modify_main_query' ) );
	// ACF
	add_filter( 'acf/settings/save_json', array( $this, 'jaws_acf_json_save_point' ) );
	add_filter( 'acf/settings/load_json', array( $this, 'jaws_acf_json_load_point' ) );
	add_action( 'admin_print_styles', array( $this, 'jaws_acf_css' ) );
	// Yast SEO override
	add_filter( 'wpseo_opengraph_image', array( $this, 'jaws_wpseo_opengraph_image' ) );
	add_filter( 'wpseo_twitter_image', array( $this, 'jaws_wpseo_opengraph_image' ) );
}

// Register Custom Post Type - Session
public function custom_post_type_session() {
	$tax_labels = array(
		'name'                       => _x( 'Tracks', 'Taxonomy General Name', 'jawsdays' ),
		'singular_name'              => _x( 'Track', 'Taxonomy Singular Name', 'jawsdays' ),
		'menu_name'                  => __( 'Track', 'jawsdays' ),
		'all_items'                  => __( 'All Tracks', 'jawsdays' ),
		'parent_item'                => __( 'Parent Track', 'jawsdays' ),
		'parent_item_colon'          => __( 'Parent Track:', 'jawsdays' ),
		'new_item_name'              => __( 'New Track Name', 'jawsdays' ),
		'add_new_item'               => __( 'Add New Track', 'jawsdays' ),
		'edit_item'                  => __( 'Edit Track', 'jawsdays' ),
		'update_item'                => __( 'Update Track', 'jawsdays' ),
		'view_item'                  => __( 'View Track', 'jawsdays' ),
		'separate_items_with_commas' => __( 'Separate tracks with commas', 'jawsdays' ),
		'add_or_remove_items'        => __( 'Add or remove tracks', 'jawsdays' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'jawsdays' ),
		'popular_items'              => __( 'Popular Tracks', 'jawsdays' ),
		'search_items'               => __( 'Search Tracks', 'jawsdays' ),
		'not_found'                  => __( 'Not Found', 'jawsdays' ),
		'no_terms'                   => __( 'No tracks', 'jawsdays' ),
		'items_list'                 => __( 'Tracks list', 'jawsdays' ),
		'items_list_navigation'      => __( 'Tracks list navigation', 'jawsdays' ),
	);
	$tax_args = array(
		'labels'                     => $tax_labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'meta_box_cb'                => false,
	);
	register_taxonomy( 'session_track', array( 'session' ), $tax_args );

	$tax_labels = array(
		'name'                       => _x( 'Venues', 'Taxonomy General Name', 'jawsdays' ),
		'singular_name'              => _x( 'Venue', 'Taxonomy Singular Name', 'jawsdays' ),
		'menu_name'                  => __( 'Venue', 'jawsdays' ),
		'all_items'                  => __( 'All Venues', 'jawsdays' ),
		'parent_item'                => __( 'Parent Venue', 'jawsdays' ),
		'parent_item_colon'          => __( 'Parent Venue:', 'jawsdays' ),
		'new_item_name'              => __( 'New Venue Name', 'jawsdays' ),
		'add_new_item'               => __( 'Add New Venue', 'jawsdays' ),
		'edit_item'                  => __( 'Edit Venue', 'jawsdays' ),
		'update_item'                => __( 'Update Venue', 'jawsdays' ),
		'view_item'                  => __( 'View Venue', 'jawsdays' ),
		'separate_items_with_commas' => __( 'Separate venues with commas', 'jawsdays' ),
		'add_or_remove_items'        => __( 'Add or remove venues', 'jawsdays' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'jawsdays' ),
		'popular_items'              => __( 'Popular Venues', 'jawsdays' ),
		'search_items'               => __( 'Search Venues', 'jawsdays' ),
		'not_found'                  => __( 'Not Found', 'jawsdays' ),
		'no_terms'                   => __( 'No venues', 'jawsdays' ),
		'items_list'                 => __( 'Venues list', 'jawsdays' ),
		'items_list_navigation'      => __( 'Venues list navigation', 'jawsdays' ),
	);
	$tax_args = array(
		'labels'                     => $tax_labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'meta_box_cb'                => false,
	);
	register_taxonomy( 'session_venue', array( 'session' ), $tax_args );

	$tax_labels = array(
		'name'                       => _x( 'Levels', 'Taxonomy General Name', 'jawsdays' ),
		'singular_name'              => _x( 'Level', 'Taxonomy Singular Name', 'jawsdays' ),
		'menu_name'                  => __( 'Level', 'jawsdays' ),
		'all_items'                  => __( 'All Levels', 'jawsdays' ),
		'parent_item'                => __( 'Parent Level', 'jawsdays' ),
		'parent_item_colon'          => __( 'Parent Level:', 'jawsdays' ),
		'new_item_name'              => __( 'New Level Name', 'jawsdays' ),
		'add_new_item'               => __( 'Add New Level', 'jawsdays' ),
		'edit_item'                  => __( 'Edit Level', 'jawsdays' ),
		'update_item'                => __( 'Update Level', 'jawsdays' ),
		'view_item'                  => __( 'View Level', 'jawsdays' ),
		'separate_items_with_commas' => __( 'Separate levels with commas', 'jawsdays' ),
		'add_or_remove_items'        => __( 'Add or remove levels', 'jawsdays' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'jawsdays' ),
		'popular_items'              => __( 'Popular Levels', 'jawsdays' ),
		'search_items'               => __( 'Search Levels', 'jawsdays' ),
		'not_found'                  => __( 'Not Found', 'jawsdays' ),
		'no_terms'                   => __( 'No levels', 'jawsdays' ),
		'items_list'                 => __( 'Levels list', 'jawsdays' ),
		'items_list_navigation'      => __( 'Levels list navigation', 'jawsdays' ),
	);
	$tax_args = array(
		'labels'                     => $tax_labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'meta_box_cb'                => false,
	);
	register_taxonomy( 'session_level', array( 'session' ), $tax_args );

	$labels = array(
		'name'                => _x( 'Sessions', 'Post Type General Name', 'jawsdays' ),
		'singular_name'       => _x( 'Session', 'Post Type Singular Name', 'jawsdays' ),
		'menu_name'           => _x( 'Sessions', 'Post Type Menu Name', 'jawsdays' ),
		'parent_item_colon'   => __( 'Parent Item:', 'jawsdays' ),
		'all_items'           => __( 'All Items', 'jawsdays' ),
		'view_item'           => __( 'View Item', 'jawsdays' ),
		'add_new_item'        => __( 'Add New Item', 'jawsdays' ),
		'add_new'             => __( 'Add New', 'jawsdays' ),
		'edit_item'           => __( 'Edit Item', 'jawsdays' ),
		'update_item'         => __( 'Update Item', 'jawsdays' ),
		'search_items'        => __( 'Search Item', 'jawsdays' ),
		'not_found'           => __( 'Not found', 'jawsdays' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jawsdays' ),
	);
	$args = array(
		'label'               => _x( 'Sessions', 'Post Type label', 'jawsdays' ),
		'description'         => _x( 'Session', 'Post Type description', 'jawsdays' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'page-attributes', 'revisions', 'publicize', 'wpcom-markdown' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-megaphone',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'rewrite'             => array( 'slug' => 'session', 'with_front' => false ),
		'sptp_permalink_structure' => 'session/%post_id%',
	);
	register_post_type( 'session', $args );
}

// Register Custom Post Type - Supporter
public function custom_post_type_supporter() {

	$tax_labels = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'jawsdays' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'jawsdays' ),
		'menu_name'                  => __( 'Type', 'jawsdays' ),
		'all_items'                  => __( 'All Types', 'jawsdays' ),
		'parent_item'                => __( 'Parent Type', 'jawsdays' ),
		'parent_item_colon'          => __( 'Parent Type:', 'jawsdays' ),
		'new_item_name'              => __( 'New Type Name', 'jawsdays' ),
		'add_new_item'               => __( 'Add New Type', 'jawsdays' ),
		'edit_item'                  => __( 'Edit Type', 'jawsdays' ),
		'update_item'                => __( 'Update Type', 'jawsdays' ),
		'view_item'                  => __( 'View Type', 'jawsdays' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'jawsdays' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'jawsdays' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'jawsdays' ),
		'popular_items'              => __( 'Popular Types', 'jawsdays' ),
		'search_items'               => __( 'Search Types', 'jawsdays' ),
		'not_found'                  => __( 'Not Found', 'jawsdays' ),
		'no_terms'                   => __( 'No types', 'jawsdays' ),
		'items_list'                 => __( 'Types list', 'jawsdays' ),
		'items_list_navigation'      => __( 'Types list navigation', 'jawsdays' ),
	);
	$tax_args = array(
		'labels'                     => $tax_labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'supporter_type', array( 'supporter' ), $tax_args );

	$labels = array(
		'name'                => _x( 'Supporters', 'Post Type General Name', 'jawsdays' ),
		'singular_name'       => _x( 'Supporter', 'Post Type Singular Name', 'jawsdays' ),
		'menu_name'           => _x( 'Supporters', 'Post Type Menu Name', 'jawsdays' ),
		'parent_item_colon'   => __( 'Parent Item:', 'jawsdays' ),
		'all_items'           => __( 'All Items', 'jawsdays' ),
		'view_item'           => __( 'View Item', 'jawsdays' ),
		'add_new_item'        => __( 'Add New Item', 'jawsdays' ),
		'add_new'             => __( 'Add New', 'jawsdays' ),
		'edit_item'           => __( 'Edit Item', 'jawsdays' ),
		'update_item'         => __( 'Update Item', 'jawsdays' ),
		'search_items'        => __( 'Search Item', 'jawsdays' ),
		'not_found'           => __( 'Not found', 'jawsdays' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jawsdays' ),
	);
	$args = array(
		'label'               => _x( 'Supporters', 'Post Type label', 'jawsdays' ),
		'description'         => _x( 'Supporter', 'Post Type description', 'jawsdays' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'publicize', 'wpcom-markdown' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-awards',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'rewrite'             => array( 'slug' => 'supporter', 'with_front' => false ),
		'sptp_permalink_structure' => 'supporter/%post_id%',
	);
	register_post_type( 'supporter', $args );
}

public function jaws_restrict_manage_posts() {
	global $post_type;

	if ( 'supporter' == $post_type ) {
		$queried_object = get_queried_object();
		$term_slug = ( ! empty( $queried_object->slug )) ? $queried_object->slug : '';
		wp_dropdown_categories( array(
			'show_option_all'    => __( 'All Types', 'jawsdays' ),
			'selected'           => $term_slug,
			'name'               => 'supporter_type',
			'taxonomy'           => 'supporter_type',
			'value_field'	     => 'slug',	
		));
	} elseif ( 'session' == $post_type ) {
		$queried_object = get_queried_object();
		$term_slug = ( ! empty( $queried_object->slug )) ? $queried_object->slug : '';
		wp_dropdown_categories( array(
			'show_option_all'    => __( 'All Tracks', 'jawsdays' ),
			'selected'           => $term_slug,
			'name'               => 'session_track',
			'taxonomy'           => 'session_track',
			'value_field'	     => 'slug',	
		));
	}
}

/**
 * Query pre_get_posts
 * http://notnil-creative.com/blog/archives/1996
 */
public function jaws_modify_main_query( $query ) {

	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( array( 'supporter', 'session' ) ) ) {
		$query->set( 'posts_per_archive_page', -1 );
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'ASC' );
		return;
	}

}

// ACF
public function jaws_acf_json_save_point( $path ) {

	// update path
	$path = JAWSDAYS_PATH . '/acf-json';

	// return
	return $path;

}
public function jaws_acf_json_load_point( $paths ) {

	// remove original path (optional)
	unset($paths[0]);

	// append path
	$paths[] = JAWSDAYS_PATH . '/acf-json';

	// return
	return $paths;

}
public function jaws_acf_css() {
?>
<style>
	.acf-taxonomy-field ul.acf-radio-list li,
	.acf-taxonomy-field ul.acf-checkbox-list li {
		display: inline-block;
		margin-right: 1em;
	}
</style>
<?php
}

// Yoast SEO
public function jaws_wpseo_opengraph_image( $image ) {
	if ( is_singular( 'session' ) || is_post_type_archive( 'session' ) ) {
		$image = JAWSDAYS_URL . '/images/ogp-session-image.png';
	} elseif ( is_post_type_archive( 'supporter' ) ) {
		$image = JAWSDAYS_URL . '/images/ogp-supporter-image.png';
	}
	return $image;
}

} // end class JAWSDAYS__Setting

// EOF
