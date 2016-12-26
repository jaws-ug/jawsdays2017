<?php
/*
 * Admin Settingspage for Custom Taxonomy Order NE
 */


function customtaxorder() {
	global $sitepress;

	customtaxorder_update_settings();
	$options = customtaxorder_get_settings();
	$settings = ''; // The input and text for the taxonomy that's shown
	$parent_ID = 0;

	// Get list of taxonomies
	$args = array( 'public' => true );
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

	if ( !empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$com_page = 'customtaxorder-'.$taxonomy->name;
			if ( !isset($options[$taxonomy->name]) ) {
				$options[$taxonomy->name] = 0; // default if not set in options yet
			}
			if ( $_GET['page'] == $com_page ) {

				// Set your custom capability through this filter.
				$custom_cap = apply_filters( 'customtaxorder_custom_cap', 'manage_categories' );

				// Set your finegrained capability for this taxonomy for this custom filter.
				$custom_cap_tax = apply_filters( 'customtaxorder_custom_cap_' . $taxonomy->name, $custom_cap );

				if ( function_exists('current_user_can') && !current_user_can( $custom_cap_tax ) ) {
					die(__( 'Cheatin&#8217; uh?', 'custom-taxonomy-order-ne' ));
				}
			}
		}
	}

	// Remove filter for WPML
	remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
	remove_filter( 'get_terms', array( $sitepress, 'get_terms_filter' ) );
	?>
	<div class='wrap customtaxorder'>
		<div id="icon-customtaxorder"></div>

	<?php
	if ( $_GET['page'] == 'customtaxorder' ) {
		// Main admin page with just a set of links to the taxonomy pages.

		// Set your custom capability through this filter.
		$custom_cap = apply_filters( 'customtaxorder_custom_cap', 'manage_categories' );

		if ( function_exists('current_user_can') && !current_user_can( $custom_cap ) ) {
			die(__( 'Cheatin&#8217; uh?', 'custom-taxonomy-order-ne' ));
		}

		?>
		<h1>Custom Taxonomy Order NE</h1>
		<div class="order-widget">
			<p><?php _e('The ordering of categories and custom taxonomy terms through a simple drag-and-drop interface.', 'custom-taxonomy-order-ne'); ?></p>
		<?php
		if ( !empty( $taxonomies ) ) {
			echo "<h2>" . __('Taxonomies', 'custom-taxonomy-order-ne') . "</h2><ul>";
			$taxonomies = customtaxorder_sort_taxonomies( $taxonomies );
			echo '<li class="lineitem"><a href="' . admin_url( 'admin.php?page=customtaxorder-taxonomies' ) . '">' . __('Taxonomies', 'custom-taxonomy-order-ne') . '</a></li>
				';
			foreach ( $taxonomies as $taxonomy ) {
				echo '<li class="lineitem"><a href="' . admin_url( 'admin.php?page=customtaxorder-' . $taxonomy->name ) . '">' . $taxonomy->label . '</a></li>
				';
			}
		}
		echo '</ul></div></div><!-- #wrap -->';
		return;
	} else {
		if ( !empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$com_page = 'customtaxorder-'.$taxonomy->name;
				if ( !isset($options[$taxonomy->name]) ) {
					$options[$taxonomy->name] = 0; // default if not set in options yet
				}
				if ( $_GET['page'] == $com_page ) {
					$settings .= '<label><input type="radio" name="customtaxorder_settings[' . $taxonomy->name . ']" value="0" ' . checked('0', $options[$taxonomy->name], false) . ' /> ' . __('Order by ID (default).', 'custom-taxonomy-order-ne') . '</label><br />
						';
					$settings .= '<label><input type="radio" name="customtaxorder_settings[' . $taxonomy->name . ']" value="1" ' . checked('1', $options[$taxonomy->name], false) . ' /> ' . __('Custom Order as defined above.', 'custom-taxonomy-order-ne') . '</label><br />
						';
					$settings .= '<label><input type="radio" name="customtaxorder_settings[' . $taxonomy->name . ']" value="2" ' . checked('2', $options[$taxonomy->name], false) . ' /> ' . __('Alphabetical Order.', 'custom-taxonomy-order-ne') . '</label><br />
						';
					$tax_label = $taxonomy->label;
					$tax = $taxonomy->name;
				} else {
					if ( !isset($options[$taxonomy->name]) ) {
						$options[$taxonomy->name] = 0; // default if not set in options yet
					}
					$settings .= '<input name="customtaxorder_settings[' . $taxonomy->name . ']" type="hidden" value="' . $options[$taxonomy->name] . '" />';
				}
			}
		}
	}
	$parent_ID_order = 0;
	if (isset($_POST['go-sub-posts'])) {
		$parent_ID = $_POST['sub-posts'];
	}
	elseif (isset($_POST['hidden-parent-id'])) {
		$parent_term = get_term($_POST['hidden-parent-id'], $tax);
		$parent_ID = $_POST['hidden-parent-id'];
		if ( is_object($parent_term) && isset($parent_term->term_order) ) {
			$parent_ID_order = $parent_term->term_order;
		}
	}
	if (isset($_POST['return-sub-posts'])) {
		$parent_term = get_term($_POST['hidden-parent-id'], $tax);
		$parent_ID = $parent_term->parent;
	}
	$message = "";
	if (isset($_POST['order-submit'])) {
		customtaxorder_update_order();
	}
?>

	<h1><?php echo __('Order ', 'custom-taxonomy-order-ne') . $tax_label; ?></h1>
	<form name="custom-order-form" method="post" action="">
		<?php
		$args = array(
			'orderby' => 'term_order',
			'order' => 'ASC',
			'hide_empty' => false,
			'parent' => $parent_ID,
		);
		$terms = get_terms( $tax, $args );
		if ( $terms ) {
			usort($terms, 'customtax_cmp');
			?>
			<div id="poststuff" class="metabox-holder">
				<div class="widget order-widget">
					<h2 class="widget-top"><?php _e( $tax_label) ?> | <small><?php _e('Order the taxonomies by dragging and dropping them into the desired order.', 'custom-taxonomy-order-ne') ?></small></h2>
					<div class="misc-pub-section">
						<ul id="custom-order-list">
							<?php foreach ( $terms as $term ) : ?>
							<li id="id_<?php echo $term->term_id; ?>" class="lineitem"><?php echo $term->name; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="misc-pub-section misc-pub-section-last">
						<?php if ($parent_ID != 0) { ?>
							<input type="submit" class="button" style="float:left" id="return-sub-posts" name="return-sub-posts" value="<?php _e('Return to Parent', 'custom-taxonomy-order-ne'); ?>" />
						<?php } ?>
						<div id="publishing-action">
							<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" id="custom-loading" style="display:none" alt="" />
							<input type="submit" name="order-submit" id="order-submit" class="button-primary" value="<?php _e('Update Order', 'custom-taxonomy-order-ne') ?>" />
							<input type="submit" name="order-alpha" id="order-alpha" class="button" value="<?php _e('Sort Alphabetical', 'custom-taxonomy-order-ne') ?>" />
						</div>
						<div class="clear"></div>
					</div>
					<input type="hidden" id="hidden-custom-order" name="hidden-custom-order" />
					<input type="hidden" id="hidden-parent-id" name="hidden-parent-id" value="<?php echo $parent_ID; ?>" />
					<input type="hidden" id="hidden-parent-id-order" name="hidden-parent-id-order" value="<?php echo $parent_ID_order; ?>" />
				</div>
				<?php $dropdown = customtaxorder_sub_query( $terms, $tax ); if( !empty($dropdown) ) { ?>
				<div class="widget order-widget">
					<h2 class="widget-top"><?php print(__('Sub-', 'custom-taxonomy-order-ne').$tax_label); ?> | <small><?php _e('Choose a term from the drop down to order its sub-terms.', 'custom-taxonomy-order-ne'); ?></small></h2>
					<div class="misc-pub-section misc-pub-section-last">
						<select id="sub-posts" name="sub-posts">
							<?php echo $dropdown; ?>
						</select>
						<input type="submit" name="go-sub-posts" class="button" id="go-sub-posts" value="<?php _e('Order Sub-terms', 'custom-taxonomy-order-ne') ?>" />
					</div>
				</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<p><?php _e('No terms found', 'custom-taxonomy-order-ne'); ?></p>
		<?php } ?>
	</form>
	<form method="post" action="options.php" class="clear">
		<?php settings_fields('customtaxorder_settings'); ?>
		<div class="metabox-holder">
			<div class="order-widget">
				<h2 class="widget-top"><?php _e( 'Settings', 'custom-taxonomy-order-ne' ); ?></h2>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Auto-Sort Queries of this Taxonomy', 'custom-taxonomy-order-ne') ?></th>
					</tr>
					<tr valign="top">
						<td><?php echo $settings; ?></td>
					</tr>
				</table>
				<input type="hidden" name="customtaxorder_settings[update]" value="Updated" />
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'custom-taxonomy-order-ne') ?>" />
				</p>
			</div>
		</div>
	</form>
</div>

<?php
}
