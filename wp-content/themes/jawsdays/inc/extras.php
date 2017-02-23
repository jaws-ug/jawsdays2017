<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package JAWSDAYS
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function jawsdays_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'jawsdays_body_classes' );

/**
 * Filter the archive title..
 *
 * @param string $title Archive title to be displayed.
 * @return string
 */
function jawsdays_get_the_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'jawsdays_get_the_archive_title' );

/**
 * WordPressの投稿作成画面で必須項目を作る（空欄ならJavaScriptのアラート）
 *
 * @link https://gist.github.com/gatespace/11020994
 */
add_action( 'admin_head-post-new.php', 'jawsdays_post_edit_required' ); // 新規投稿画面でフック
add_action( 'admin_head-post.php', 'jawsdays_post_edit_required' );     // 投稿編集画面でフック
function jawsdays_post_edit_required() {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	if( 'supporter' == $('#post_type').val() ){ // サポーター
		$("#post").submit(function(e){ // 更新あるいは下書き保存を押したとき
			if( $("#set-post-thumbnail img").length < 1 ) { // アイキャッチ画像
				alert('アイキャッチ画像を設定してください！');
				$('.spinner').hide();
				$('#publish').removeClass('button-primary-disabled');
				$('#set-post-thumbnail').focus();
				return false;
			}
		});
	}
});
</script>
<?php
}

/**
 * TinyMCE
 */
// Callback function to insert 'styleselect' into the $buttons array
function jawsdays_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'jawsdays_mce_buttons_2' );

// Callback function to filter the MCE settings
function jawsdays_mce_before_init_insert_formats( $init_array ) {
	// Define the style_formats array
	$style_formats = array(
		// Each array child is a format with it's own settings
		array(  
			'title' => 'A ボタン',
			'inline' => 'a',
			'classes' => 'btn'
		),
	);
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );

	return $init_array;  

}
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'jawsdays_mce_before_init_insert_formats' );
