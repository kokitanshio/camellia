<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<aside id="sidebar" class="l-sidebar">
    <?php if ( is_search() ) : ?>
        <!-- 検索ページ専用サイドバー -->
        <div id="block-2" class="c-widget widget_block widget_search">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="wp-block-search__button-outside wp-block-search__text-button wp-block-search">
                <label class="wp-block-search__label" for="wp-block-search__input-1">検索</label>
                <div class="wp-block-search__inside-wrapper">
                    <input class="wp-block-search__input" id="wp-block-search__input-1" type="search" name="s" required="">
                    <button aria-label="検索" class="wp-block-search__button wp-element-button" type="submit">検索</button>
                </div>
            </form>
        </div>

        <!-- アーカイブリスト -->
        <div id="block-5" class="c-widget widget_block">
            <div class="wp-block-group">
                <div class="wp-block-group__inner-container">
                    <h2 class="wp-block-heading">アーカイブ</h2>
                    <ul class="wp-block-archives-list c-listMenu wp-block-archives">
                        <?php wp_get_archives(array('type' => 'monthly', 'limit' => 5)); ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- カスタムHTMLウィジェット (ブログカテゴリ一覧) -->
        <div id="custom_html-4" class="widget_text c-widget widget_custom_html">
            <div class="textwidget custom-html-widget">
                <div class="p-blogParts post_content" data-partsid="2567">
                    <p class="u-mb-ctrl u-mb-5 has-text-color has-link-color" style="color:#083061">
                        <strong style="font-weight: bold;">ブログ</strong>　<strong>カテゴリ一覧</strong>
                    </p>
                    <ul class="wp-block-categories-list c-listMenu wp-block-categories">
                        <?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'category')); ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- カスタムHTMLウィジェット (お知らせカテゴリ一覧) -->
        <div id="custom_html-3" class="widget_text c-widget widget_custom_html">
            <div class="textwidget custom-html-widget">
                <div class="p-blogParts post_content" data-partsid="2562">
                    <p class="u-mb-ctrl u-mb-5 has-text-color has-link-color" style="color:#083061">
                        <strong style="font-weight: bold;">お知らせ</strong><strong>　</strong><strong>カテゴリ一覧</strong>
                    </p>
                    <ul class="wp-block-categories-list c-listMenu wp-block-categories">
                        <?php
                        wp_list_categories(array(
                            'title_li'   => '',
                            'taxonomy'   => 'news-cat', // カスタムタクソノミーの場合は適宜変更
                            'include'    => '8,9,10', // 指定カテゴリIDを含める（適宜変更）
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        </div>

    <?php else : ?>
        <!-- 通常のサイドバー -->
        <?php
            $cache_key = '';
            if ( SWELL_Theme::get_setting( 'cache_sidebar' ) ) :
                if ( SWELL_Theme::is_top() ) :
                    $cache_key = 'sidebar_top';
                elseif ( is_single() ) :
                    $cache_key = 'sidebar_single';
                elseif ( is_page() || is_home() ) :
                    $cache_key = 'sidebar_page';
                elseif ( is_archive() ) :
                    $cache_key = 'sidebar_archive';
                endif;

                if ( '' !== $cache_key && IS_MOBILE ) :
                    $cache_key .= '_sp';
                endif;
            endif;
            SWELL_Theme::get_parts( 'parts/sidebar_content', '', $cache_key, 24 * HOUR_IN_SECONDS ); // キャッシュは24時間だけ
        ?>
    <?php endif; ?>
</aside>
