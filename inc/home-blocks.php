<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function imidjstroy_home_block_defaults() {
    return [
        'hero' => [
            'titleFirst'=>'Строительные материалы', 'titleAccent'=>'оптом и в розницу',
            'text'=>'Качественные стройматериалы по лучшим ценам во Владивостоке. Доставка и самовывоз.',
            'primaryText'=>'Смотреть каталог', 'primaryUrl'=>home_url('/shop/'),
            'secondaryText'=>'Связаться с нами', 'secondaryUrl'=>home_url('/contacts/'), 'backgroundUrl'=>'',
        ],
        'features' => [ 'items'=>[
            [ 'title'=>'Быстрая доставка', 'description'=>'Доставка по Владивостоку и области' ],
            [ 'title'=>'Гарантия качества', 'description'=>'Только сертифицированные товары' ],
            [ 'title'=>'Удобное время', 'description'=>imidjstroy_get_site_setting('hours_feature') ],
            [ 'title'=>'Поддержка', 'description'=>'Консультации по выбору материалов' ],
        ] ],
    ];
}

function imidjstroy_render_template_block( $template, $attributes ) {
    ob_start();
    get_template_part( $template, null, $attributes );
    return ob_get_clean();
}
function imidjstroy_render_hero_block( $a, $content = '' ) {
    if ( '' === trim( $content ) ) {
        return imidjstroy_render_template_block( 'template-parts/blocks/hero', $a );
    }

    $bg = ! empty( $a['backgroundUrl'] )
        ? esc_url( $a['backgroundUrl'] )
        : esc_url( get_template_directory_uri() . '/assets/images/hero-bg.jpg' );

    ob_start();
    ?>
    <section class="home-hero home-hero--blocks">
        <div class="home-hero__background" style="background-image:url('<?php echo $bg; ?>');" aria-hidden="true"></div>
        <div class="home-hero__overlay" aria-hidden="true"></div>
        <div class="container home-hero__container">
            <div class="home-hero__content">
                <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
function imidjstroy_render_features_block( $a ){ return imidjstroy_render_template_block('template-parts/blocks/features',$a); }
function imidjstroy_render_categories_block( $a ){ return imidjstroy_render_template_block('template-parts/components/categories',$a); }
function imidjstroy_render_product_section_block( $a ){
    $type = isset($a['sectionType']) ? sanitize_key($a['sectionType']) : 'popular';
    $map = [ 'building'=>'building-materials', 'ad'=>'ad-materials', 'popular'=>'popular-products' ];
    $part = isset($map[$type]) ? $map[$type] : $map['popular'];
    return imidjstroy_render_template_block('template-parts/components/'.$part,$a);
}
function imidjstroy_render_news_block( $a ){ return imidjstroy_render_template_block('template-parts/blocks/news',$a); }
function imidjstroy_render_gallery_block( $a ){ return imidjstroy_render_template_block('template-parts/blocks/gallery',$a); }
function imidjstroy_render_contact_block( $a ){ ob_start(); echo '<div id="contact-form">'; get_template_part('template-parts/components/contact-form',null,$a); echo '</div>'; return ob_get_clean(); }
function imidjstroy_render_cta_block( $a ){ return imidjstroy_render_template_block('template-parts/blocks/cta',$a); }
function imidjstroy_render_text_block( $a ){ return imidjstroy_render_template_block('template-parts/blocks/text',$a); }

function imidjstroy_register_home_blocks() {
    $script_path = get_template_directory() . '/assets/js/home-blocks.js';
    wp_register_script('imidjstroy-home-blocks', get_template_directory_uri().'/assets/js/home-blocks.js', ['wp-blocks','wp-element','wp-components','wp-block-editor','wp-i18n'], file_exists($script_path)?filemtime($script_path):null, true );

    wp_localize_script(
        'imidjstroy-home-blocks',
        'imidjstroyHomeEditor',
        [
            'phone'          => imidjstroy_get_site_setting( 'phone_1' ),
            'city'           => imidjstroy_get_site_setting( 'city_short' ),
            'heroBackground' => get_template_directory_uri() . '/assets/images/hero-bg.jpg',
        ]
    );
    $defaults = imidjstroy_home_block_defaults();
    $front_style_path = get_template_directory() . '/assets/css/flexible-sections.css';
    wp_register_style(
        'imidjstroy-flexible-sections',
        get_template_directory_uri() . '/assets/css/flexible-sections.css',
        [],
        file_exists( $front_style_path ) ? filemtime( $front_style_path ) : null
    );

    $common = [ 'editor_script'=>'imidjstroy-home-blocks', 'supports'=>[ 'html'=>false ] ];

    register_block_type( 'imidjstroy/section', [
        'editor_script' => 'imidjstroy-home-blocks',
        'style'         => 'imidjstroy-flexible-sections',
        'attributes'    => [
            'background'     => [ 'type' => 'string', 'default' => 'white' ],
            'padding'        => [ 'type' => 'string', 'default' => 'large' ],
            'contentWidth'   => [ 'type' => 'string', 'default' => 'container' ],
            'backgroundUrl'  => [ 'type' => 'string', 'default' => '' ],
            'backgroundId'   => [ 'type' => 'number', 'default' => 0 ],
            'overlay'        => [ 'type' => 'number', 'default' => 0 ],
            'verticalAlign'  => [ 'type' => 'string', 'default' => 'center' ],
        ],
        'supports'      => [
            'html'   => false,
            'anchor' => true,
        ],
    ] );
    register_block_type( 'imidjstroy/button', [
        'editor_script' => 'imidjstroy-home-blocks',
        'supports'      => [ 'html' => false ],
    ] );
    register_block_type( 'imidjstroy/contact-item', [
        'editor_script' => 'imidjstroy-home-blocks',
        'supports'      => [ 'html' => false ],
    ] );
    register_block_type( 'imidjstroy/row', [
        'editor_script' => 'imidjstroy-home-blocks',
        'supports'      => [ 'html' => false ],
    ] );
    register_block_type('imidjstroy/hero', array_merge($common,[ 'attributes'=>[
        'titleFirst'=>['type'=>'string','default'=>$defaults['hero']['titleFirst']], 'titleAccent'=>['type'=>'string','default'=>$defaults['hero']['titleAccent']], 'text'=>['type'=>'string','default'=>$defaults['hero']['text']],
        'primaryText'=>['type'=>'string','default'=>$defaults['hero']['primaryText']], 'primaryUrl'=>['type'=>'string','default'=>$defaults['hero']['primaryUrl']], 'secondaryText'=>['type'=>'string','default'=>$defaults['hero']['secondaryText']], 'secondaryUrl'=>['type'=>'string','default'=>$defaults['hero']['secondaryUrl']], 'backgroundUrl'=>['type'=>'string','default'=>''] ], 'render_callback'=>'imidjstroy_render_hero_block' ]));
    register_block_type('imidjstroy/features', array_merge($common,[ 'attributes'=>[ 'items'=>['type'=>'array','default'=>$defaults['features']['items']] ], 'render_callback'=>'imidjstroy_render_features_block' ]));
    register_block_type('imidjstroy/categories', array_merge($common,[ 'attributes'=>[ 'title'=>['type'=>'string','default'=>'Категории'], 'link_text'=>['type'=>'string','default'=>'Смотреть все'] ], 'render_callback'=>'imidjstroy_render_categories_block' ]));
    register_block_type('imidjstroy/product-section', array_merge($common,[ 'attributes'=>[ 'sectionType'=>['type'=>'string','default'=>'popular'], 'title'=>['type'=>'string','default'=>'Популярные товары'], 'eyebrow'=>['type'=>'string','default'=>''], 'link_text'=>['type'=>'string','default'=>'Смотреть все'], 'count'=>['type'=>'number','default'=>8] ], 'render_callback'=>'imidjstroy_render_product_section_block' ]));
    register_block_type('imidjstroy/news', array_merge($common,[ 'attributes'=>[ 'title'=>['type'=>'string','default'=>'Новости'], 'count'=>['type'=>'number','default'=>3], 'linkText'=>['type'=>'string','default'=>'Все новости'] ], 'render_callback'=>'imidjstroy_render_news_block' ]));
    register_block_type('imidjstroy/gallery', array_merge($common,[ 'attributes'=>[ 'title'=>['type'=>'string','default'=>'Галерея'], 'imageIds'=>['type'=>'array','default'=>[]], 'columns'=>['type'=>'number','default'=>4] ], 'render_callback'=>'imidjstroy_render_gallery_block' ]));
    register_block_type('imidjstroy/contact', array_merge($common,[ 'attributes'=>[ 'title'=>['type'=>'string','default'=>'Оставить заявку'], 'lead'=>['type'=>'string','default'=>'Оставьте заявку и мы свяжемся с вами в ближайшее время'] ], 'render_callback'=>'imidjstroy_render_contact_block' ]));
    register_block_type('imidjstroy/cta', array_merge($common,[ 'attributes'=>[ 'title'=>['type'=>'string','default'=>'Нужна консультация?'], 'text'=>['type'=>'string','default'=>'Поможем подобрать материалы под вашу задачу.'], 'buttonText'=>['type'=>'string','default'=>'Связаться с нами'], 'buttonUrl'=>['type'=>'string','default'=>home_url('/contacts/')] ], 'render_callback'=>'imidjstroy_render_cta_block' ]));
    register_block_type('imidjstroy/text', array_merge($common,[ 'attributes'=>[ 'eyebrow'=>['type'=>'string','default'=>''], 'title'=>['type'=>'string','default'=>'Заголовок блока'], 'content'=>['type'=>'string','default'=>'Добавьте текст в редакторе главной страницы.'] ], 'render_callback'=>'imidjstroy_render_text_block' ]));
}
add_action('init','imidjstroy_register_home_blocks');

function imidjstroy_home_block_category( $categories ) {
    array_unshift($categories,[ 'slug'=>'imidjstroy', 'title'=>'Имидж Строй' ]);
    return $categories;
}
add_filter('block_categories_all','imidjstroy_home_block_category');

function imidjstroy_home_editor_assets() {
    $css = get_template_directory().'/assets/css/home-blocks-editor.css';
    wp_enqueue_style('imidjstroy-home-blocks-editor', get_template_directory_uri().'/assets/css/home-blocks-editor.css', [], file_exists($css)?filemtime($css):null );
}
add_action('enqueue_block_editor_assets','imidjstroy_home_editor_assets');

function imidjstroy_allowed_home_blocks( $allowed, $context ) {
    if ( empty($context->post) || (int)$context->post->ID !== (int)get_option('page_on_front') ) return $allowed;
    return [
        'imidjstroy/section',
        'imidjstroy/hero',
        'imidjstroy/features',
        'imidjstroy/categories',
        'imidjstroy/product-section',
        'imidjstroy/news',
        'imidjstroy/gallery',
        'imidjstroy/contact',
        'imidjstroy/cta',
        'imidjstroy/text',
        'imidjstroy/button',
        'imidjstroy/contact-item',
        'imidjstroy/row',
        'core/paragraph',
        'core/heading',
        'core/image',
        'core/buttons',
        'core/button',
        'core/columns',
        'core/column',
        'core/group',
        'core/list',
        'core/list-item',
        'core/gallery',
        'core/media-text',
        'core/quote',
        'core/separator',
        'core/spacer',
    ];
}
add_filter('allowed_block_types_all','imidjstroy_allowed_home_blocks',10,2);

function imidjstroy_get_default_home_block_content() {
    $blocks = [
        '<!-- wp:imidjstroy/hero /-->',
        '<!-- wp:imidjstroy/features /-->',
        '<!-- wp:imidjstroy/categories /-->',
        '<!-- wp:imidjstroy/product-section {"sectionType":"building","title":"Стройматериалы","count":8} /-->',
        '<!-- wp:imidjstroy/product-section {"sectionType":"ad","title":"Рекламные материалы","eyebrow":"Для производства рекламы","count":8} /-->',
        '<!-- wp:imidjstroy/product-section {"sectionType":"popular","title":"Популярные товары","count":8} /-->',
        '<!-- wp:imidjstroy/news {"title":"Новости","count":3,"linkText":"Все новости"} /-->',
        '<!-- wp:imidjstroy/contact /-->',
    ];
    return implode("\n\n",$blocks);
}


/**
 * Flexible Gutenberg patterns for visually composing pages.
 * These patterns use the custom Section wrapper plus native Core blocks,
 * so users can add/remove/reorder text, buttons, images and columns freely.
 */
function imidjstroy_register_flexible_patterns() {
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category(
            'imidjstroy-layouts',
            [ 'label' => 'Имидж Строй — макеты' ]
        );
    }

    register_block_pattern(
        'imidjstroy/blank-section',
        [
            'title'      => 'Свободная секция',
            'categories' => [ 'imidjstroy-layouts' ],
            'content'    => '<!-- wp:imidjstroy/section -->\n<!-- wp:heading -->\n<h2 class="wp-block-heading">Заголовок секции</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Добавьте текст, изображения, кнопки, колонки или другие элементы.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:imidjstroy/section -->',
        ]
    );

    register_block_pattern(
        'imidjstroy/flexible-hero',
        [
            'title'      => 'Hero — свободный',
            'categories' => [ 'imidjstroy-layouts' ],
            'content'    => '<!-- wp:imidjstroy/section {"background":"dark","padding":"xlarge","overlay":30} -->\n<!-- wp:heading {"level":1,"textColor":"white"} -->\n<h1 class="wp-block-heading has-white-color has-text-color">Строительные материалы</h1>\n<!-- /wp:heading -->\n\n<!-- wp:heading {"level":2,"style":{"color":{"text":"#22a862"}}} -->\n<h2 class="wp-block-heading has-text-color" style="color:#22a862">оптом и в розницу</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {"textColor":"white"} -->\n<p class="has-white-color has-text-color">Качественные стройматериалы по лучшим ценам. Доставка и самовывоз.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons -->\n<div class="wp-block-buttons"><!-- wp:button -->\n<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Смотреть каталог</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {"className":"is-style-outline"} -->\n<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Связаться с нами</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n<!-- /wp:imidjstroy/section -->',
        ]
    );

    register_block_pattern(
        'imidjstroy/text-image',
        [
            'title'      => 'Текст + изображение',
            'categories' => [ 'imidjstroy-layouts' ],
            'content'    => '<!-- wp:imidjstroy/section {"background":"white"} -->\n<!-- wp:columns {"verticalAlignment":"center"} -->\n<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->\n<div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading -->\n<h2 class="wp-block-heading">Заголовок</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Добавьте нужное количество абзацев, списков и кнопок. Все элементы можно перетаскивать.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons -->\n<div class="wp-block-buttons"><!-- wp:button -->\n<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Подробнее</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {"verticalAlignment":"center"} -->\n<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image -->\n<figure class="wp-block-image"><img alt=""/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n<!-- /wp:imidjstroy/section -->',
        ]
    );

    register_block_pattern(
        'imidjstroy/two-columns',
        [
            'title'      => 'Две колонки',
            'categories' => [ 'imidjstroy-layouts' ],
            'content'    => '<!-- wp:imidjstroy/section {"background":"gray"} -->\n<!-- wp:columns -->\n<div class="wp-block-columns"><!-- wp:column -->\n<div class="wp-block-column"><!-- wp:heading {"level":3} -->\n<h3 class="wp-block-heading">Первая колонка</h3>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p>Текст первой колонки.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n<!-- wp:column -->\n<div class="wp-block-column"><!-- wp:heading {"level":3} -->\n<h3 class="wp-block-heading">Вторая колонка</h3>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p>Текст второй колонки.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n<!-- /wp:imidjstroy/section -->',
        ]
    );

    register_block_pattern(
        'imidjstroy/flexible-cta',
        [
            'title'      => 'CTA — свободный',
            'categories' => [ 'imidjstroy-layouts' ],
            'content'    => '<!-- wp:imidjstroy/section {"background":"green","padding":"medium"} -->\n<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->\n<div class="wp-block-group"><!-- wp:group {"layout":{"type":"constrained"}} -->\n<div class="wp-block-group"><!-- wp:heading {"level":2,"textColor":"white"} -->\n<h2 class="wp-block-heading has-white-color has-text-color">Нужна консультация?</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph {"textColor":"white"} -->\n<p class="has-white-color has-text-color">Поможем подобрать материалы под вашу задачу.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n<!-- wp:buttons -->\n<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"black"} -->\n<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-white-background-color has-text-color has-background wp-element-button">Связаться с нами</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:group -->\n<!-- /wp:imidjstroy/section -->',
        ]
    );
}
add_action( 'init', 'imidjstroy_register_flexible_patterns', 30 );


/**
 * Load the custom WYSIWYG stylesheet inside the Gutenberg editing canvas.
 * This complements enqueue_block_editor_assets and works with iframe-based
 * versions of the block editor as well.
 */
function imidjstroy_add_block_editor_styles() {
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/home-blocks-editor.css' );
}
add_action( 'after_setup_theme', 'imidjstroy_add_block_editor_styles', 30 );
