<?php
/**
 * Plugin Name: JAWSDAYS 2016 Setting
 * Plugin URI:  https://github.com/jaws-ug/jawsdays2016
 * Description: JAWSDAYS 2016 Settings.
 * Version:     0.1.0
 * Author:      IGARASHI Kazue
 * Author URI:  http://gatespace.jp/
 * License:     GPLv2
 * Text Domain: jawsdays2016
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



define( 'JAWSDAYS2016_URL',  plugins_url( '', __FILE__ ) );
define( 'JAWSDAYS2016_PATH', dirname( __FILE__ ) );

$jawsdays2016 = new JAWSDAYS_2016_Setting();
$jawsdays2016->register();

class JAWSDAYS_2016_Setting {

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
		'jawsdays2016',
		false,
		dirname( plugin_basename( __FILE__ ) ) . $this->langs
	);

	// Register Custom Post Type
	add_action( 'init', array( $this, 'custom_post_type_speaker' ), 0 );
	add_action( 'init', array( $this, 'custom_post_type_supporter' ), 0 );
	// Query pre_get_posts
	add_action( 'pre_get_posts', array( $this, 'jaws_modify_main_query' ) );
	// jaws_acf
	add_action( 'init', array( $this, 'jaws_acf' ) );

}

// Register Custom Post Type
public function custom_post_type_speaker() {
	$labels = array(
		'name'                => _x( 'Speakers', 'Post Type General Name', 'jawsdays2016' ),
		'singular_name'       => _x( 'Speaker', 'Post Type Singular Name', 'jawsdays2016' ),
		'menu_name'           => _x( 'Speakers', 'Post Type Menu Name', 'jawsdays2016' ),
		'parent_item_colon'   => __( 'Parent Item:', 'jawsdays2016' ),
		'all_items'           => __( 'All Items', 'jawsdays2016' ),
		'view_item'           => __( 'View Item', 'jawsdays2016' ),
		'add_new_item'        => __( 'Add New Item', 'jawsdays2016' ),
		'add_new'             => __( 'Add New', 'jawsdays2016' ),
		'edit_item'           => __( 'Edit Item', 'jawsdays2016' ),
		'update_item'         => __( 'Update Item', 'jawsdays2016' ),
		'search_items'        => __( 'Search Item', 'jawsdays2016' ),
		'not_found'           => __( 'Not found', 'jawsdays2016' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jawsdays2016' ),
	);
	$args = array(
		'label'               => _x( 'Speakers', 'Post Type label', 'jawsdays2016' ),
		'description'         => _x( 'Speaker', 'Post Type description', 'jawsdays2016' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'publicize', 'wpcom-markdown' ),
		'hierarchical'        => false,
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
		'rewrite'             => array( 'slug' => 'speaker', 'with_front' => false ),
		'sptp_permalink_structure' => 'speaker/%post_id%',
	);
	register_post_type( 'speaker', $args );
}

// Register Custom Post Type
public function custom_post_type_supporter() {
	$labels = array(
		'name'                => _x( 'Supporters', 'Post Type General Name', 'jawsdays2016' ),
		'singular_name'       => _x( 'Supporter', 'Post Type Singular Name', 'jawsdays2016' ),
		'menu_name'           => _x( 'Supporters', 'Post Type Menu Name', 'jawsdays2016' ),
		'parent_item_colon'   => __( 'Parent Item:', 'jawsdays2016' ),
		'all_items'           => __( 'All Items', 'jawsdays2016' ),
		'view_item'           => __( 'View Item', 'jawsdays2016' ),
		'add_new_item'        => __( 'Add New Item', 'jawsdays2016' ),
		'add_new'             => __( 'Add New', 'jawsdays2016' ),
		'edit_item'           => __( 'Edit Item', 'jawsdays2016' ),
		'update_item'         => __( 'Update Item', 'jawsdays2016' ),
		'search_items'        => __( 'Search Item', 'jawsdays2016' ),
		'not_found'           => __( 'Not found', 'jawsdays2016' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jawsdays2016' ),
	);
	$args = array(
		'label'               => _x( 'Supporters', 'Post Type label', 'jawsdays2016' ),
		'description'         => _x( 'Supporter', 'Post Type description', 'jawsdays2016' ),
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

/**
 * Query pre_get_posts
 * http://notnil-creative.com/blog/archives/1996
 */
public function jaws_modify_main_query( $query ) {

	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'supporter' ) ) {
		$query->set( 'posts_per_archive_page', -1 );
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'ASC' );
		return;
	}

}

// ACF
public function jaws_acf() {
	if( function_exists( "register_field_group" ) ) {
		// sub title
		register_field_group(array (
			'id' => 'acf_jaws_page_sub_title',
			'title' => 'サブタイトル',
			'fields' => array (
				array (
					'key' => 'field_549296c534538',
					'label' => 'サブタイトル',
					'name' => 'sub_title',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'acf_after_title',
				'layout' => 'no_box',
				'hide_on_screen' => array (
				),
			),
			'menu_order' => 0,
		));
		
		// supporter
		register_field_group(array (
			'id' => 'acf_jaws_supporter',
			'title' => 'サポーター',
			'fields' => array (
				array (
					'key' => 'field_54939e6d347ac',
					'label' => 'URL',
					'name' => '_supporter_url',
					'type' => 'text',
					'required' => 1,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'none',
					'maxlength' => '',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'supporter',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'acf_after_title',
				'layout' => 'no_box',
				'hide_on_screen' => array (
				),
			),
			'menu_order' => 0,
		));

		// speaker
		register_field_group(array (
			'id' => 'acf_speaker1',
			'title' => 'スピーカー1',
			'fields' => array (
				array (
					'key' => 'field_5657d25d2d81a',
					'label' => '所属',
					'name' => 'group',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_5657d2772d81b',
					'label' => '自己紹介+Web',
					'name' => 'profile',
					'type' => 'wysiwyg',
					'instructions' => '',
					'default_value' => '',
					'toolbar' => 'full',
					'media_upload' => 'yes',
				),
				array (
					'key' => 'field_56544249c76c9',
					'label' => 'Twitterユーザー名',
					'name' => 'twitter',
					'type' => 'text',
					'default_value' => '',
					'instructions' => '@以降のユーザー名',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_56544249c7610',
					'label' => 'Facebookユーザー名',
					'name' => 'facebook',
					'type' => 'text',
					'default_value' => '',
					'instructions' => 'ユーザー名',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_56544249c7611',
					'label' => 'GitHubユーザー名',
					'name' => 'github',
					'type' => 'text',
					'default_value' => '',
					'instructions' => 'ユーザー名',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_56544249c76c8',
					'label' => 'セッションタイトル',
					'name' => 'session_title',
					'type' => 'text',
					'required' => 1,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_5654427ac76c9',
					'label' => 'トラック',
					'name' => 'track',
					'type' => 'radio',
					'required' => 1,
					'choices' => array (
						'センタートラック' => 'センタートラック',
						'AWS Technical Deep Dive' => 'AWS Technical Deep Dive',
						'ユーザートラック' => 'ユーザートラック',
						'The Next Cloud' => 'The Next Cloud',
						'Workshop' => 'Workshop',
						'HackDay' => 'HackDay',
						'JAWS-UG出張勉強会' => 'JAWS-UG出張勉強会',
						'LT' => 'LT',
					),
					'other_choice' => 0,
					'save_other_choice' => 0,
					'default_value' => 'ビッグトラック',
					'layout' => 'horizontal',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'speaker',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'acf_after_title',
				'layout' => 'no_box',
				'hide_on_screen' => array (
				),
			),
			'menu_order' => 0,
		));
		register_field_group(array (
			'id' => 'acf_speaker2',
			'title' => 'スピーカー2',
			'fields' => array (
				array (
					'key' => 'field_5657d21e2d819',
					'label' => '主な聴講者',
					'name' => 'target',
					'type' => 'wysiwyg',
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'formatting' => 'br',
				),
				array (
					'key' => 'field_5657d3352d81f',
					'label' => 'スライド資料',
					'name' => 'slide_url',
					'type' => 'wysiwyg',
					'instructions' => 'slideshare ならURLのみでスライドが展開されます',
					'default_value' => '',
					'toolbar' => 'full',
					'media_upload' => 'yes',
				),
				array (
					'key' => 'field_5657d3452d820',
					'label' => 'その他',
					'name' => 'other',
					'type' => 'wysiwyg',
					'default_value' => '',
					'toolbar' => 'full',
					'media_upload' => 'yes',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'speaker',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'normal',
				'layout' => 'no_box',
				'hide_on_screen' => array (
				),
			),
			'menu_order' => 1,
		));

	}
}

} // end class JAWSDAYS_2016_Setting

// EOF
