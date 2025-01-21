<?php

/* 子テーマのfunctions.phpは、親テーマのfunctions.phpより先に読み込まれることに注意してください。 */


/**
 * 親テーマのfunctions.phpのあとで読み込みたいコードはこの中に。
 */
// add_filter('after_setup_theme', function(){

// }, 11);


/**
 * 子テーマでのファイルの読み込み
 */
add_action('wp_enqueue_scripts', function () {

    $timestamp = date('Ymdgis', filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_style('child_style', get_stylesheet_directory_uri() . '/style.css', [], $timestamp);

    /* その他の読み込みファイルはこの下に記述 */
}, 11);

/*====================================================
# Contact Form 7で自動挿入されるPタグ、brタグを削除
=====================================================*/
add_filter('wpcf7_autop_or_not', 'wpcf7_autop_return_false');
function wpcf7_autop_return_false() {
    return false;
}


/*====================================================
# カテゴリーとカスタムタクソノミー「news」のラベル表示
=====================================================*/
function swl_parts__post_list_category($args) {
    $the_id = $args['post_id'] ?? get_the_ID();
    $cat_data = get_the_category($the_id);
    $genre_data = get_the_terms($the_id, 'news-cat'); // 「news」に独自に設定したタクソノミースラッグを入れる

    if (!empty($cat_data)) { // カテゴリーのラベル表示用
        echo '<span class="c-postThumb__cat icon-folder" data-cat-id="' . $cat_data[0]->slug . '">' . $cat_data[0]->name . '</span>';

        if (empty($cat_data)) {
            return;
        }
    }


    if (!empty($genre_data)) { // タームのラベル表示用
        echo '<span class="c-postThumb__cat icon-folder" data-cat-id="' . $genre_data[0]->slug . '">' . $genre_data[0]->name . '</span>';

        if (empty($genre_data)) {
            return;
        }
    }
}

/*====================================================
# サイドバー 日付アーカイブ ページに適した内容に変更
=====================================================*/
add_filter( 'widget_archives_args', 'my_widget_archives_args', 10, 1);
add_filter( 'widget_archives_dropdown_args', 'my_widget_archives_args', 10, 1);
function my_widget_archives_args( $args ){
    if ( ! is_admin() ) {
        if ( 'news' == get_post_type() || is_post_type_archive('news') ) {
            $args['post_type'] = 'news';
        }
    }
    return $args;
}

/*====================================================
# 個別投稿ページの関連記事表示をデフォルト(8)から(4)に
=====================================================*/
function article_display() {
    return 4;
}
add_filter('swell_related_post_maxnum', 'article_display');


/**
 * wp_head内不要な出力の削除
 *
 * @codex https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/remove_action
 */
// バージョン表記削除
remove_action('wp_head', 'wp_generator');
// canonical情報削除
remove_action('wp_head', 'rel_canonical');
// Shortlink削除
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
// RSSフィードのURLの削除
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
// 前の記事、次の記事のリンク削除
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
// DNS Prefetchingの削除
remove_action('wp_head', 'wp_resource_hints', 2); //読み込み速度簡易高速化停止

// 外部ブログツールからの投稿を受け入れ削除
remove_action('wp_head', 'rsd_link'); //EditURI削除
remove_action('wp_head', 'wlwmanifest_link'); //wlwmanifest削除

// Embed機能の停止
remove_action('wp_head', 'rest_output_link_wp_head'); //REST APIのURL表示
remove_action('wp_head', 'wp_oembed_add_discovery_links'); //外部コンテンツの埋め込み
remove_action('wp_head', 'wp_oembed_add_host_js'); //外部コンテンツの埋め込み

// 絵文字利用の削除
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles', 10);

// Global Stylesのコード削除
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

// 最近のコメントウィジェット削除
function remove_wp_widget_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}
add_action('widgets_init', 'remove_wp_widget_recent_comments_style');


/**
 * 管理画面不要メニュー削除 ※コメントアウトしたものは表示される
 *
 * @codex https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/remove_menu_page
 */
function remove_menus() {
    // remove_menu_page( 'index.php' ); //ダッシュボード
    // remove_menu_page( 'edit.php' ); //投稿メニュー
    // remove_menu_page( 'upload.php' ); //メディア
    // remove_menu_page( 'edit.php?post_type=page' ); //固定ページ
    remove_menu_page('edit-comments.php'); //コメントメニュー
    // remove_menu_page( 'themes.php' ); //外観メニュー
    // remove_menu_page( 'plugins.php' ); //プラグインメニュー
    // remove_menu_page( 'users.php' );  // ユーザー
    // remove_menu_page( 'edit.php?post_type=acf-field-group' ); //ACF
    // remove_menu_page( 'tools.php' ); //ツールメニュー
    // remove_menu_page( 'options-general.php' ); //設定メニュー
}
add_action('admin_menu', 'remove_menus');


/**
 * 【管理画面】投稿編集画面で不要な項目を非表示にする ※コメントアウトしたものは表示される
 *
 * @developer.wordpress https://developer.wordpress.org/reference/functions/remove_meta_box/
 */
function my_remove_meta_boxes() {
    // remove_meta_box('postexcerpt', 'post', 'normal');      // 抜粋
    remove_meta_box('trackbacksdiv', 'post', 'normal');    // トラックバック
    // remove_meta_box('slugdiv', 'post', 'normal');           // スラッグ
    // remove_meta_box('postcustom', 'post', 'normal');       // カスタムフィールド
    remove_meta_box('commentsdiv', 'post', 'normal');      // コメント
    // remove_meta_box('submitdiv', 'post', 'normal');        // 公開
    // remove_meta_box('categorydiv', 'post', 'normal');       // カテゴリー
    // remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); // タグ
    remove_meta_box('commentstatusdiv', 'post', 'normal'); // ディスカッション
    // remove_meta_box('authordiv', 'post', 'normal');        // 作成者
    remove_meta_box('revisionsdiv', 'post', 'normal');     // リビジョン
    // remove_meta_box('formatdiv', 'post', 'normal');        // フォーマット
    // remove_meta_box('pageparentdiv', 'post', 'normal');    // 属性
}
add_action('admin_menu', 'my_remove_meta_boxes');


/**
 * 投稿の名前を変更する
 *
 * @codex http://wpdocs.osdn.jp/%E7%AE%A1%E7%90%86%E3%83%A1%E3%83%8B%E3%83%A5%E3%83%BC%E3%81%AE%E8%BF%BD%E5%8A%A0
 */
function Change_menulabel() {
    global $menu;
    global $submenu;
    $name = 'ブログ';
    $menu[5][0] = $name;
    $submenu['edit.php'][5][0] = $name . '一覧';
    $submenu['edit.php'][10][0] = '新しい' . $name;
}
function Change_objectlabel() {
    global $wp_post_types;
    $name = 'ブログ';
    $labels = &$wp_post_types['post']->labels;
    $labels->name = $name;
    $labels->singular_name = $name;
    $labels->add_new = _x('追加', $name);
    $labels->add_new_item = $name . 'の新規追加';
    $labels->edit_item = $name . 'の編集';
    $labels->new_item = '新規' . $name;
    $labels->view_item = $name . 'を表示';
    $labels->search_items = $name . 'を検索';
    $labels->not_found = $name . 'が見つかりませんでした';
    $labels->not_found_in_trash = 'ゴミ箱に' . $name . 'は見つかりませんでした';
}
add_action('init', 'Change_objectlabel');
add_action('admin_menu', 'Change_menulabel');
