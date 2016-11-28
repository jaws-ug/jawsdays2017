<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package JAWSDAYS 2016
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
	if( 'supporter' == $('#post_type').val() || 'speaker' == $('#post_type').val() ){ // サポーターとスピーカー
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