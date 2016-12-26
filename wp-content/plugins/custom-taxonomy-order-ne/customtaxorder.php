<?php
/*
Plugin Name: Custom Taxonomy Order NE
Plugin URI: http://products.zenoweb.nl/free-wordpress-plugins/custom-taxonomy-order-ne/
Description: Allows for the ordering of categories and custom taxonomy terms through a simple drag-and-drop interface.
Version: 2.8.2
Author: Marcel Pol
Author URI: http://zenoweb.nl/
License: GPLv2 or later
Text Domain: custom-taxonomy-order-ne
Domain Path: /lang/

/*
	Copyright 2011 - 2011  Drew Gourley
	Copyright 2013 - 2016  Marcel Pol   (email: marcel@timelord.nl)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


/* TODO:
 * - Add pagination, just like next_post_link().
 */


// Plugin Version
define('CUSTOMTAXORDER_VER', '2.8.2');


function customtaxorder_register_settings() {
	register_setting('customtaxorder_settings', 'customtaxorder_settings', 'customtaxorder_settings_validate');
}
add_action('admin_init', 'customtaxorder_register_settings');


function customtaxorder_update_settings() {
	$options = customtaxorder_get_settings();
	if ( isset($options['update']) ) {
		echo '<div class="updated fade notice is-dismissible" id="message"><p>' . __('Custom Taxonomy Order NE settings', 'custom-taxonomy-order-ne') . ' ' . $options['update'] . '</p></div>';
		unset($options['update']);
		update_option('customtaxorder_settings', $options);
	}
}


/*
 * $customtaxorder_settings is an array with key: $taxonomy->name and value: setting (0, 1, 2).
 */
function customtaxorder_get_settings() {
	$customtaxorder_defaults = array('category' => 0);

	$args = array( 'public' => true, '_builtin' => false );
	$output = 'objects';
	$taxonomies = get_taxonomies( $args, $output );
	foreach ( $taxonomies as $taxonomy ) {
		$customtaxorder_defaults[$taxonomy->name] = 0;
	}

	$customtaxorder_defaults = apply_filters( 'customtaxorder_defaults', $customtaxorder_defaults );
	$customtaxorder_settings = get_option( 'customtaxorder_settings' );
	$customtaxorder_settings = wp_parse_args( $customtaxorder_settings, $customtaxorder_defaults );

	return $customtaxorder_settings;
}


function customtaxorder_settings_validate($input) {
	$args = array( 'public' => true );
	$output = 'objects';
	$taxonomies = get_taxonomies( $args, $output );
	foreach ( $taxonomies as $taxonomy ) {
		if ( $input[$taxonomy->name] != 1 ) {
			if ( $input[$taxonomy->name] != 2 ) {
				$input[$taxonomy->name] = 0; //default
			}
		}
	}
	return $input;
}


function customtaxorder_menu() {
	$args = array( 'public' => true );
	$output = 'objects';
	$taxonomies = get_taxonomies($args, $output);

	// Also make the link_category available if activated.
	$linkplugin = "link-manager/link-manager.php";
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active($linkplugin) ) {
		$args = array( 'name' => 'link_category' );
		$taxonomies2 = get_taxonomies( $args, $output );
		$taxonomies = array_merge($taxonomies, $taxonomies2);
	}

	$taxonomies = customtaxorder_sort_taxonomies( $taxonomies );
	// Set your custom capability through this filter.
	$custom_cap = apply_filters( 'customtaxorder_custom_cap', 'manage_categories' );

	add_menu_page(__('Term Order', 'customtaxorder'), __('Term Order', 'custom-taxonomy-order-ne'), $custom_cap, 'customtaxorder', 'customtaxorder', 'dashicons-list-view', 122.35);
	add_submenu_page('customtaxorder', __('Order Taxonomies', 'custom-taxonomy-order-ne'), __('Order Taxonomies', 'custom-taxonomy-order-ne'), $custom_cap, 'customtaxorder-taxonomies', 'custom_taxonomy_order');

	foreach ($taxonomies as $taxonomy ) {
		// Set your finegrained capability for this taxonomy for this custom filter.
		$custom_cap_tax = apply_filters( 'customtaxorder_custom_cap_' . $taxonomy->name, $custom_cap );
		add_submenu_page('customtaxorder', __('Order ', 'custom-taxonomy-order-ne') . $taxonomy->label, __('Order ', 'custom-taxonomy-order-ne') . $taxonomy->label, $custom_cap_tax, 'customtaxorder-'.$taxonomy->name, 'customtaxorder');
	}
	add_submenu_page('customtaxorder', __('About', 'custom-taxonomy-order-ne'), __('About', 'custom-taxonomy-order-ne'), $custom_cap, 'customtaxorder-about', 'customtaxorder_about');
}
add_action('admin_menu', 'customtaxorder_menu');


function customtaxorder_css() {
	if ( isset($_GET['page']) ) {
		$pos_page = $_GET['page'];
		$pos_args = 'customtaxorder';
		$pos = strpos($pos_page,$pos_args);
		if ( $pos === false ) {} else {
			wp_enqueue_style('customtaxorder', plugins_url( 'css/customtaxorder.css', __FILE__), false, CUSTOMTAXORDER_VER, 'screen' );
		}
	}
}
add_action('admin_print_styles', 'customtaxorder_css');


function customtaxorder_js_libs() {
	if ( isset($_GET['page']) ) {
		$pos_page = $_GET['page'];
		$pos_args = 'customtaxorder';
		$pos = strpos($pos_page,$pos_args);
		if ( $pos === false ) {} else {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'customtaxorder', plugins_url( '/js/script.js', __FILE__ ), 'jquery-ui-sortable', CUSTOMTAXORDER_VER, true );
		}
	}
}
add_action('admin_print_scripts', 'customtaxorder_js_libs');


/*
 * customtax_cmp
 * Sorting of an array with objects, ordered by term_order
 * Sorting the query with get_terms() doesn't allow sorting with term_order
 */
function customtax_cmp( $a, $b ) {
	if ( (float) $a->term_order == (float) $b->term_order ) {
		return 0;
	} else if ( (float) $a->term_order < (float) $b->term_order ) {
		return -1;
	} else {
		return 1;
	}
}


/*
 * customtaxorder_update_order
 * Function to update the database with the submitted order
 */
function customtaxorder_update_order() {
	if (isset($_POST['hidden-custom-order']) && $_POST['hidden-custom-order'] != "") {
		global $wpdb;
		$parent_ID_order = 0;
		if ( isset($_POST['hidden-parent-id-order']) && $_POST['hidden-parent-id-order'] > 0 ) {
			$parent_ID_order = (int) $_POST['hidden-parent-id-order'] + 1;
		}
		$new_order = $_POST['hidden-custom-order'];
		$IDs = explode(",", $new_order);
		$ids = Array();
		$result = count($IDs);
		for($i = 0; $i < $result; $i++) {
			$id = (int) str_replace("id_", "", $IDs[$i]);
			$j = $i + $parent_ID_order;
			$wpdb->query( $wpdb->prepare(
				"
					UPDATE $wpdb->terms SET term_order = '%d' WHERE term_id ='%d'
				",
				$j,
				$id
			) );
			$wpdb->query( $wpdb->prepare(
				"
					UPDATE $wpdb->term_relationships SET term_order = '%d' WHERE term_taxonomy_id ='%d'
				",
				$j,
				$id
			) );
			$ids[] = $id;
		}
		echo '<div id="message" class="updated fade notice is-dismissible"><p>'. __('Order updated successfully.', 'custom-taxonomy-order-ne').'</p></div>';
		do_action('customtaxorder_update_order', $ids);
	} else {
		echo '<div id="message" class="error fade notice is-dismissible"><p>'. __('An error occured, order has not been saved.', 'custom-taxonomy-order-ne').'</p></div>';
	}
}


/*
 * Flush object cache when order is changed in taxonomy ordering plugin.
 *
 * Since 2.7.8
 *
 */
function customtaxorder_flush_cache() {
	wp_cache_flush();
}
add_action( 'customtaxorder_update_order', 'customtaxorder_flush_cache' );


/*
 * customtaxorder_sub_query
 * Function to give an option for the list of sub-taxonomies
 */
function customtaxorder_sub_query( $terms, $tax ) {
	$options = '';
	foreach ( $terms as $term ) :
		$subterms = get_term_children( $term->term_id, $tax );
		if ( $subterms ) {
			$options .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
		}
	endforeach;
	return $options;
}


/*
 * customtaxorder_apply_order_filter
 * Function to sort the standard WordPress Queries.
 *
 * @return string t.orderby
 *
 */
function customtaxorder_apply_order_filter( $orderby, $args ) {
	$options = customtaxorder_get_settings();

	$taxonomy = 'category';
	if ( isset( $args['taxonomy'] ) ) {
		if ( is_string( $args['taxonomy'] ) && ! empty( $args['taxonomy'] ) ) {
			$taxonomy = $args['taxonomy'];
		} else if ( is_array( $args['taxonomy'] ) && ! empty( $args['taxonomy'] ) ) {
			// Bug: if $args[$taxonomy] is an array with tax->names it will return the orderby for the first tax.
			$taxonomy = array_shift( $args['taxonomy'] );
		}
	}

	if ( ! isset( $options[$taxonomy] ) ) {
		$options[$taxonomy] = 0; // Default if it was not set in options yet.
	}

	if ( $args['orderby'] == 'term_order' ) {
		return 't.term_order';
	} elseif ( $args['orderby'] == 'name' ) {
		return 't.name';
	} elseif ( $options[$taxonomy] == 1 && !isset($_GET['orderby']) ) {
		return 't.term_order';
	} elseif ( $options[$taxonomy] == 2 && !isset($_GET['orderby']) ) {
		return 't.name';
	} else {
		return $orderby;
	}
}
add_filter('get_terms_orderby', 'customtaxorder_apply_order_filter', 10, 2);


/*
 * customtaxorder_wp_get_object_terms_order_filter
 * wp_get_object_terms is used to sort in wp_get_object_terms and wp_get_post_terms functions.
 * get_terms is used in wp_list_categories and get_terms functions.
 * get_the_terms is used in the the_tags function.
 * tag_cloud_sort is used in the wp_tag_cloud and wp_generate_tag_cloud functions (but then the get_terms filter here does nothing).
 * Default sorting is by name (according to the codex).
 *
 */
function customtaxorder_wp_get_object_terms_order_filter( $terms ) {
	$options = customtaxorder_get_settings();

	if ( empty($terms) || ! is_array($terms) ) {
		return $terms; // only work with an array of terms
	}
	foreach ($terms as $term) {
		if ( is_object($term) && isset( $term->taxonomy ) ) {
			$taxonomy = $term->taxonomy;
		} else {
			return $terms; // not an array with objects
		}
		break; // just the first one :)
	}

	if ( !isset ( $options[$taxonomy] ) ) {
		$options[$taxonomy] = 0; // default if not set in options yet
	}
	if ( $options[$taxonomy] == 1 && !isset($_GET['orderby']) ) {

		// no filtering so the test in wp_generate_tag_cloud() works out right for us
		// filtering will happen in the tag_cloud_sort filter sometime later
		// post_tag = default tags
		// product_tag = woocommerce product tags
		if (current_filter() == 'get_terms'  && !is_admin() ) {
			$customtaxorder_exclude_taxonomies = array('post_tag', 'product_tag');
			if ( in_array($taxonomy, apply_filters( 'customtaxorder_exclude_taxonomies', $customtaxorder_exclude_taxonomies )) ) {
				return $terms;
			}
		}

		// Sort children after the ancestor, by using a float with "ancestor.child".
		foreach ($terms as $term) {
			if ( ! $term->parent == 0 ) {
				$parents = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
				if ( is_array($parents) && !empty($parents) ) {
					$ancestor_ID = array_pop( $parents );
					$ancestor_term = get_term($ancestor_ID);
					if ( is_object($ancestor_term) && isset($ancestor_term->term_order) ) {
						$float = floatval($term->term_order) + 10000;
						$term->term_order = floatval( strval($ancestor_term->term_order) . '.' . strval($float) );
					}
				}
			}
		}

		usort($terms, 'customtax_cmp');
		return $terms;
	}
	return $terms;
}
add_filter( 'wp_get_object_terms', 'customtaxorder_wp_get_object_terms_order_filter', 10, 3 );
add_filter( 'get_terms', 'customtaxorder_wp_get_object_terms_order_filter', 10, 3 );
add_filter( 'get_the_terms', 'customtaxorder_wp_get_object_terms_order_filter', 10, 3 );
add_filter( 'tag_cloud_sort', 'customtaxorder_wp_get_object_terms_order_filter', 10, 3 );


/*
 * Support Advanced Custom Fields with its Taxonomy Fields.
 */
function customtaxorder_wp_get_object_terms_order_filter_acf( $terms ) {

	if ( empty($terms) || ! is_array($terms) ) {
		return $terms; // only work with an array of terms
	}
	foreach ($terms as $term) {
		if ( ! is_object($term) || ! is_a($term, 'WP_Term') ) {
			return $terms; // not an array with terms
		}
	}

	$terms = customtaxorder_wp_get_object_terms_order_filter( $terms );

	return $terms;
}
add_filter('acf/format_value_for_api', 'customtaxorder_wp_get_object_terms_order_filter_acf', 99 );


/*
 * customtaxorder_order_categories
 * Filter to sort the categories according to term_order
 *
 */
function customtaxorder_order_categories( $categories ) {
	$options = customtaxorder_get_settings();
	if ( !isset ( $options['category'] ) ) {
		$options['category'] = 0; // default if not set in options yet
	}
	if ( $options['category'] == 1 && !isset($_GET['orderby']) ) {
		usort($categories, 'customtax_cmp');
		return $categories;
	}
	return $categories;
}
add_filter('get_the_categories', 'customtaxorder_order_categories', 10, 3);


/*
 * About page with text.
 */
function customtaxorder_about() {
	?>
	<div class='wrap'>
		<h1><?php _e('About Custom Taxonomy Order NE', 'custom-taxonomy-order-ne'); ?></h1>
		<div id="poststuff" class="metabox-holder">
			<div class="widget">
				<h2 class="widget-top"><?php _e('About this plugin.', 'custom-taxonomy-order-ne'); ?></h2>
				<p><?php _e('This plugin is being maintained by Marcel Pol from', 'custom-taxonomy-order-ne'); ?>
					<a href="http://zenoweb.nl" target="_blank" title="ZenoWeb">ZenoWeb</a>.
				</p>

				<h2 class="widget-top"><?php _e('Review this plugin.', 'custom-taxonomy-order-ne'); ?></h2>
				<p><?php _e('If this plugin has any value to you, then please leave a review at', 'custom-taxonomy-order-ne'); ?>
					<a href="https://wordpress.org/support/view/plugin-reviews/custom-taxonomy-order-ne?rate=5#postform" target="_blank" title="<?php esc_attr_e('The plugin page at wordpress.org.', 'custom-taxonomy-order-ne'); ?>">
						<?php _e('the plugin page at wordpress.org', 'custom-taxonomy-order-ne'); ?></a>.
				</p>

				<h2 class="widget-top"><?php _e('Donate to the maintainer.', 'custom-taxonomy-order-ne'); ?></h2>
				<p><?php _e('If you want to donate to the maintainer of the plugin, you can donate through PayPal.', 'custom-taxonomy-order-ne'); ?></p>
				<p><?php _e('Donate through', 'custom-taxonomy-order-ne'); ?> <a href="https://www.paypal.com" target="_blank" title="<?php esc_attr_e('Donate to the maintainer.', 'custom-taxonomy-order-ne'); ?>"><?php _e('PayPal', 'custom-taxonomy-order-ne'); ?></a>
					<?php _e('to', 'custom-taxonomy-order-ne'); ?> marcel@timelord.nl.
				</p>
			</div>
		</div>
	</div>
	<?php
}


/*
 * customtaxorder_links
 * Add Settings link to the main plugin page
 *
 */
function customtaxorder_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/customtaxorder.php' ) ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=customtaxorder' ) . '">' . __( 'Settings', 'custom-taxonomy-order-ne' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'customtaxorder_links', 10, 2 );


/*
 * Load language files.
 */
function customtaxorder_load_lang() {
	load_plugin_textdomain('custom-taxonomy-order-ne', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
}
add_action('plugins_loaded', 'customtaxorder_load_lang');


/*
 * customtaxorder_activate
 * Function called at activation time.
 */
function _customtaxorder_activate() {
	global $wpdb;
	$init_query = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'term_order'");
	if ($init_query == 0) { $wpdb->query("ALTER TABLE $wpdb->terms ADD term_order INT( 4 ) NULL DEFAULT '0'"); }
}


function customtaxorder_activate($networkwide) {
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blogids as $blog_id) {
			switch_to_blog($blog_id);
			_customtaxorder_activate();
			restore_current_blog();
		}
	} else {
		_customtaxorder_activate();
	}
}
register_activation_hook( __FILE__, 'customtaxorder_activate' );


function customtaxorder_activate_new_site($blog_id) {
	switch_to_blog($blog_id);
	_customtaxorder_activate();
	restore_current_blog();
}
add_action( 'wpmu_new_blog', 'customtaxorder_activate_new_site' );


// Include Settingspage
include('page-customtaxorder.php');
// Include functions for sorting taxonomies
include('taxonomies.php');
