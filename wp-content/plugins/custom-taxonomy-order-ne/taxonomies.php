<?php


function custom_taxonomy_order() {

	// Set your custom capability through this filter.
	$custom_cap = apply_filters( 'customtaxorder_custom_cap', 'manage_categories' );

	if ( function_exists('current_user_can') && !current_user_can( $custom_cap ) ) {
		die(__( 'Cheatin&#8217; uh?', 'custom-taxonomy-order-ne' ));
	}

	if (isset($_POST['order-submit'])) {
		customtaxorder_update_taxonomies();
	}

	?>
	<div class='wrap customtaxorder'>
		<div id="icon-customtaxorder"></div>
		<h1><?php _e('Order Taxonomies', 'custom-taxonomy-order-ne'); ?></h1>

		<form name="custom-order-form" method="post" action="">
			<?php
			$args = array();
			$output = 'objects';
			$taxonomies = get_taxonomies( $args, $output );

			// Also make the link_category available if activated.
			$linkplugin = "link-manager/link-manager.php";
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active($linkplugin) ) {
				$args = array( 'name' => 'link_category' );
				$taxonomies2 = get_taxonomies( $args, $output );
				$taxonomies = array_merge($taxonomies, $taxonomies2);
			}

			if ( $taxonomies ) {

				$taxonomies_ordered = customtaxorder_sort_taxonomies( $taxonomies );
				?>

				<div id="poststuff" class="metabox-holder">
					<div class="widget order-widget">
						<h2 class="widget-top">
							<?php _e('Order Taxonomies', 'custom-taxonomy-order-ne'); ?> |
							<small><?php _e('Order the taxonomies by dragging and dropping them into the desired order.', 'custom-taxonomy-order-ne') ?></small>
						</h2>
						<div class="misc-pub-section">
							<ul id="custom-taxonomy-list">
								<?php
								foreach ( $taxonomies_ordered as $taxonomy ) { ?>
									<li id="<?php echo $taxonomy->name; ?>" class="lineitem"><?php echo $taxonomy->name; ?></li>
									<?php
								} ?>
							</ul>
						</div>
						<div class="misc-pub-section misc-pub-section-last">
							<div id="publishing-action">
								<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" id="custom-loading" style="display:none" alt="" />
								<input type="submit" name="order-submit" id="order-submit" class="button-primary" value="<?php _e('Update Order', 'custom-taxonomy-order-ne') ?>" />
							</div>
							<div class="clear"></div>
						</div>
						<input type="hidden" id="hidden-taxonomy-order" name="hidden-taxonomy-order" />
					</div>
				</div>

			<?php } else { ?>
				<p><?php _e('No taxonomies found', 'custom-taxonomy-order-ne'); ?></p>
			<?php }
			?>
		</form>
	</div>
	<?php
}


/*
 * Save order of the taxonomies in an option
 */
function customtaxorder_update_taxonomies() {
	if (isset($_POST['hidden-taxonomy-order']) && $_POST['hidden-taxonomy-order'] != "") {

		$new_order = $_POST['hidden-taxonomy-order'];
		$new_order = sanitize_text_field( $new_order );

		update_option('customtaxorder_taxonomies', $new_order);

		echo '<div id="message" class="updated fade notice is-dismissible"><p>'. __('Order updated successfully.', 'custom-taxonomy-order-ne').'</p></div>';
	} else {
		echo '<div id="message" class="error fade notice is-dismissible"><p>'. __('An error occured, order has not been saved.', 'custom-taxonomy-order-ne').'</p></div>';
	}
}


/*
 * Sort the taxonomies
 *
 * Parameter: $taxonomies, array with a list of taxonomy objects.
 *
 * Returns: array with list of taxonomies, ordered correctly.
 *
 * Since: 2.7.0
 *
 */
function customtaxorder_sort_taxonomies( $taxonomies = array() ) {
	$order = get_option( 'customtaxorder_taxonomies', '' );
	$order = explode( ",", $order );
	$taxonomies_ordered = array();

	// Main sorted taxonomies.
	if ( ! empty($order) && is_array($order) && ! empty($taxonomies) && is_array($taxonomies) ) {
		foreach ( $order as $tax ) {
			foreach ( $taxonomies as $tax_name => $tax_obj ) {
				if ( is_object( $tax_obj ) && $tax === $tax_name ) {
					$taxonomies_ordered[ $tax_name ] = $tax_obj;
					unset( $taxonomies[ $tax_name ] );
				}
			}
		}
	}

	// Unsorted taxonomies, the leftovers.
	foreach ( $taxonomies as $tax_name => $tax_obj ) {
		$taxonomies_ordered[ $tax_name ] = $tax_obj;
	}

	return $taxonomies_ordered;
}


/*
 * Same as customtaxorder_sort_taxonomies, but for WooCommerce.
 *
 * Parameter: $taxonomies, array with a list of taxonomy arrays.
 *
 * Returns: array with list of taxonomies, ordered correctly.
 *
 * Since: 2.7.0
 *
 */
function customtaxorder_sort_taxonomies_array( $taxonomies = array() ) {
	$order = get_option( 'customtaxorder_taxonomies', '' );
	$order = explode( ",", $order );
	$taxonomies_woo = array();

	// Main sorted taxonomies.
	if ( ! empty($order) && is_array($order) && ! empty($taxonomies) && is_array($taxonomies) ) {
		foreach ( $order as $tax ) {
			foreach ( $taxonomies as $key => $taxonomy ) {
	 			if ( is_array( $taxonomy ) && $tax === $taxonomy['name'] ) {
					$taxonomies_woo[ $taxonomy['name'] ] = $taxonomy;
					unset( $taxonomies[$taxonomy['name']] );
				}
			}
		}
	}

	// Unsorted taxonomies, the leftovers.
	foreach ( $taxonomies as $key => $taxonomy ) {
		$taxonomies_woo[ $key ] = $taxonomy;
	}

	return $taxonomies_woo;
}
add_filter( 'woocommerce_get_product_attributes', 'customtaxorder_sort_taxonomies_array' );
