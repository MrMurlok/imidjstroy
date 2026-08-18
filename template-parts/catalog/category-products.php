<?php
/**
 * Leaf category page.
 * Port of src/pages/CategoryProducts.tsx
 */

defined( 'ABSPATH' ) || exit;

$current_term = get_query_var( 'category_products_term' );

if ( ! ( $current_term instanceof WP_Term ) ) {
    return;
}

$items_per_page = 15;

$search        = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
$in_stock_only = isset( $_GET['stock'] ) && '1' === $_GET['stock'];
$sort_by       = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'default';
$selected_unit = isset( $_GET['unit'] ) ? sanitize_text_field( wp_unslash( $_GET['unit'] ) ) : 'all';
$current_page  = isset( $_GET['product_page'] ) ? max( 1, absint( $_GET['product_page'] ) ) : 1;

/*
 * Get price bounds and available units for the current leaf category.
 * This avoids loading every product object just to build the filters.
 */
global $wpdb;

$price_bounds = $wpdb->get_row(
    $wpdb->prepare(
        "
        SELECT
            MIN(CAST(pm.meta_value AS DECIMAL(20,4))) AS min_price,
            MAX(CAST(pm.meta_value AS DECIMAL(20,4))) AS max_price
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr
            ON tr.object_id = p.ID
        INNER JOIN {$wpdb->term_taxonomy} tt
            ON tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN {$wpdb->postmeta} pm
            ON pm.post_id = p.ID
            AND pm.meta_key = '_price'
        WHERE
            p.post_type = 'product'
            AND p.post_status = 'publish'
            AND tt.taxonomy = 'product_cat'
            AND tt.term_id = %d
            AND pm.meta_value <> ''
        ",
        $current_term->term_id
    )
);

$bound_min = $price_bounds && null !== $price_bounds->min_price
    ? (float) $price_bounds->min_price
    : 0;

$bound_max = $price_bounds && null !== $price_bounds->max_price
    ? (float) $price_bounds->max_price
    : 100000;

$bound_min = floor( $bound_min );
$bound_max = ceil( max( $bound_min, $bound_max ) );

$min_price = isset( $_GET['min_price'] )
    ? max( $bound_min, (float) wp_unslash( $_GET['min_price'] ) )
    : $bound_min;

$max_price = isset( $_GET['max_price'] )
    ? min( $bound_max, (float) wp_unslash( $_GET['max_price'] ) )
    : $bound_max;

if ( $min_price > $max_price ) {
    $min_price = $bound_min;
    $max_price = $bound_max;
}

$available_units = $wpdb->get_col(
    $wpdb->prepare(
        "
        SELECT DISTINCT pm.meta_value
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr
            ON tr.object_id = p.ID
        INNER JOIN {$wpdb->term_taxonomy} tt
            ON tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN {$wpdb->postmeta} pm
            ON pm.post_id = p.ID
            AND pm.meta_key = '_imidjstroy_unit'
        WHERE
            p.post_type = 'product'
            AND p.post_status = 'publish'
            AND tt.taxonomy = 'product_cat'
            AND tt.term_id = %d
            AND pm.meta_value <> ''
        ORDER BY pm.meta_value ASC
        ",
        $current_term->term_id
    )
);

$available_units = array_values(
    array_filter(
        array_map( 'trim', is_array( $available_units ) ? $available_units : [] )
    )
);

$meta_query = [
    'relation' => 'AND',
    [
        'key'     => '_price',
        'value'   => [ $min_price, $max_price ],
        'compare' => 'BETWEEN',
        'type'    => 'NUMERIC',
    ],
];

if ( $in_stock_only ) {
    $meta_query[] = [
        'key'     => '_stock_status',
        'value'   => 'instock',
        'compare' => '=',
    ];
}

if ( 'all' !== $selected_unit && '' !== $selected_unit ) {
    $meta_query[] = [
        'key'     => '_imidjstroy_unit',
        'value'   => $selected_unit,
        'compare' => '=',
    ];
}

$query_args = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $items_per_page,
    'paged'               => $current_page,
    'ignore_sticky_posts' => true,
    'tax_query'           => [
        [
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => [ $current_term->term_id ],
            'include_children' => false,
        ],
    ],
    'meta_query'          => $meta_query,
];

if ( '' !== $search ) {
    $query_args['s'] = $search;
}

switch ( $sort_by ) {
    case 'price-asc':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'ASC';
        break;

    case 'price-desc':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'DESC';
        break;

    case 'name-asc':
        $query_args['orderby'] = 'title';
        $query_args['order']   = 'ASC';
        break;

    default:
        $query_args['orderby'] = 'date';
        $query_args['order']   = 'DESC';
        break;
}

$products_query = new WP_Query( $query_args );

$total_products = (int) $products_query->found_posts;
$total_pages    = max( 1, (int) $products_query->max_num_pages );
$current_page   = min( $current_page, $total_pages );

/*
 * Breadcrumb:
 * Каталог / parent category / current category
 */
$shop_url = wc_get_page_permalink( 'shop' );
$parent_term = $current_term->parent
    ? get_term( $current_term->parent, 'product_cat' )
    : null;

$base_url = get_term_link( $current_term );

if ( is_wp_error( $base_url ) ) {
    $base_url = $shop_url;
}

$filter_args = [];

if ( '' !== $search ) {
    $filter_args['q'] = $search;
}

if ( $in_stock_only ) {
    $filter_args['stock'] = '1';
}

if ( 'default' !== $sort_by ) {
    $filter_args['sort'] = $sort_by;
}

if ( 'all' !== $selected_unit && '' !== $selected_unit ) {
    $filter_args['unit'] = $selected_unit;
}

if ( $min_price != $bound_min ) {
    $filter_args['min_price'] = $min_price;
}

if ( $max_price != $bound_max ) {
    $filter_args['max_price'] = $max_price;
}

$pagination_items = [];

if ( $total_pages > 1 ) {
    $visible_pages = [];

    for ( $page_number = 1; $page_number <= $total_pages; $page_number++ ) {
        if (
            1 === $page_number
            || $total_pages === $page_number
            || abs( $page_number - $current_page ) <= 2
        ) {
            $visible_pages[] = $page_number;
        }
    }

    $previous_visible = null;

    foreach ( $visible_pages as $visible_page ) {
        if ( null !== $previous_visible && $visible_page - $previous_visible > 1 ) {
            $pagination_items[] = 'dots';
        }

        $pagination_items[] = $visible_page;
        $previous_visible   = $visible_page;
    }
}

if ( ! function_exists( 'imidjstroy_category_products_count_text' ) ) {
    function imidjstroy_category_products_count_text( $count ) {
        $count = absint( $count );
        $mod10 = $count % 10;
        $mod100 = $count % 100;

        if ( 1 === $mod10 && 11 !== $mod100 ) {
            $word = 'товар';
        } elseif ( $mod10 >= 2 && $mod10 <= 4 && ( $mod100 < 12 || $mod100 > 14 ) ) {
            $word = 'товара';
        } else {
            $word = 'товаров';
        }

        return sprintf( '%d %s', $count, $word );
    }
}

$format_price = static function ( $price ) {
    return number_format_i18n( (float) $price, 0 );
};
?>

<section class="category-products">
    <div class="container">

        <nav class="category-products__breadcrumb" aria-label="Хлебные крошки">

            <a href="<?php echo esc_url( $shop_url ); ?>">
                Каталог
            </a>

            <?php if ( $parent_term instanceof WP_Term ) : ?>
                <?php $parent_link = get_term_link( $parent_term ); ?>

                <?php if ( ! is_wp_error( $parent_link ) ) : ?>
                    <span>/</span>

                    <a href="<?php echo esc_url( $parent_link ); ?>">
                        <?php echo esc_html( $parent_term->name ); ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <span>/</span>

            <strong>
                <?php echo esc_html( $current_term->name ); ?>
            </strong>

        </nav>

        <h1 class="category-products__title">
            <?php echo esc_html( $current_term->name ); ?>
        </h1>

        <div class="category-products__layout">

            <aside class="category-products__sidebar">

                <form
                    action="<?php echo esc_url( $base_url ); ?>"
                    method="get"
                    class="category-products__filters js-category-products-filters"
                >

                    <div class="category-products__filter">
                        <label for="category-search">Поиск</label>

                        <div class="category-products__search">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>

                            <input
                                id="category-search"
                                type="search"
                                name="q"
                                value="<?php echo esc_attr( $search ); ?>"
                                placeholder="Название товара..."
                            >
                        </div>
                    </div>

                    <div class="category-products__filter">
                        <label for="category-sort">Сортировка</label>

                        <select id="category-sort" name="sort" class="js-category-filter-submit">
                            <option value="default" <?php selected( $sort_by, 'default' ); ?>>
                                По умолчанию
                            </option>
                            <option value="price-asc" <?php selected( $sort_by, 'price-asc' ); ?>>
                                Цена: по возрастанию
                            </option>
                            <option value="price-desc" <?php selected( $sort_by, 'price-desc' ); ?>>
                                Цена: по убыванию
                            </option>
                            <option value="name-asc" <?php selected( $sort_by, 'name-asc' ); ?>>
                                По названию
                            </option>
                        </select>
                    </div>

                    <label class="category-products__checkbox">
                        <input
                            type="checkbox"
                            name="stock"
                            value="1"
                            class="js-category-filter-submit"
                            <?php checked( $in_stock_only ); ?>
                        >
                        <span class="category-products__checkbox-box" aria-hidden="true"></span>
                        <span>Только в наличии</span>
                    </label>

                    <?php if ( $bound_max > 0 ) : ?>

                        <div class="category-products__filter">
                            <label>
                                Цена:
                                <span class="js-category-price-label">
                                    <?php echo esc_html( $format_price( $min_price ) ); ?>
                                    –
                                    <?php echo esc_html( $format_price( $max_price ) ); ?>
                                    ₽
                                </span>
                            </label>

                            <div
                                class="category-products__range js-category-price-range"
                                data-bound-min="<?php echo esc_attr( $bound_min ); ?>"
                                data-bound-max="<?php echo esc_attr( $bound_max ); ?>"
                            >
                                <div class="category-products__range-track"></div>
                                <div class="category-products__range-fill js-category-range-fill"></div>

                                <input
                                    type="range"
                                    min="<?php echo esc_attr( $bound_min ); ?>"
                                    max="<?php echo esc_attr( $bound_max ); ?>"
                                    step="<?php echo esc_attr( max( 1, floor( max( 1, $bound_max - $bound_min ) / 100 ) ) ); ?>"
                                    value="<?php echo esc_attr( $min_price ); ?>"
                                    class="category-products__range-input js-category-range-min"
                                    aria-label="Минимальная цена"
                                >

                                <input
                                    type="range"
                                    min="<?php echo esc_attr( $bound_min ); ?>"
                                    max="<?php echo esc_attr( $bound_max ); ?>"
                                    step="<?php echo esc_attr( max( 1, floor( max( 1, $bound_max - $bound_min ) / 100 ) ) ); ?>"
                                    value="<?php echo esc_attr( $max_price ); ?>"
                                    class="category-products__range-input js-category-range-max"
                                    aria-label="Максимальная цена"
                                >

                                <input
                                    type="hidden"
                                    name="min_price"
                                    value="<?php echo esc_attr( $min_price ); ?>"
                                    class="js-category-price-min-hidden"
                                >

                                <input
                                    type="hidden"
                                    name="max_price"
                                    value="<?php echo esc_attr( $max_price ); ?>"
                                    class="js-category-price-max-hidden"
                                >
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php if ( count( $available_units ) > 1 ) : ?>

                        <div class="category-products__filter">
                            <label for="category-unit">Единица</label>

                            <select id="category-unit" name="unit" class="js-category-filter-submit">
                                <option value="all" <?php selected( $selected_unit, 'all' ); ?>>
                                    Все
                                </option>

                                <?php foreach ( $available_units as $unit ) : ?>
                                    <option
                                        value="<?php echo esc_attr( $unit ); ?>"
                                        <?php selected( $selected_unit, $unit ); ?>
                                    >
                                        <?php echo esc_html( $unit ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <?php endif; ?>

                    <div class="category-products__filter-actions">
                        <button type="submit" class="category-products__apply">
                            Применить
                        </button>

                        <a href="<?php echo esc_url( $base_url ); ?>" class="category-products__reset">
                            Сбросить
                        </a>
                    </div>

                    <p class="category-products__result">
                        Найдено:
                        <?php echo esc_html( imidjstroy_category_products_count_text( $total_products ) ); ?>
                    </p>

                </form>

            </aside>

            <div class="category-products__content">

                <?php if ( $products_query->have_posts() ) : ?>

                    <div class="category-products__grid">

                        <?php
                        while ( $products_query->have_posts() ) :
                            $products_query->the_post();

                            $product = wc_get_product( get_the_ID() );

                            if ( ! $product ) {
                                continue;
                            }

                            set_query_var( 'category_product', $product );
                            get_template_part( 'template-parts/catalog/product-card' );
                        endwhile;

                        wp_reset_postdata();
                        ?>

                    </div>

                    <?php if ( $total_pages > 1 ) : ?>

                        <nav class="category-products__pagination" aria-label="Навигация по товарам">

                            <?php
                            $first_args = array_merge( $filter_args, [ 'product_page' => 1 ] );
                            $prev_args  = array_merge( $filter_args, [ 'product_page' => max( 1, $current_page - 1 ) ] );
                            $next_args  = array_merge( $filter_args, [ 'product_page' => min( $total_pages, $current_page + 1 ) ] );
                            $last_args  = array_merge( $filter_args, [ 'product_page' => $total_pages ] );
                            ?>

                            <a
                                href="<?php echo esc_url( add_query_arg( $first_args, $base_url ) ); ?>"
                                class="category-products__page <?php echo $current_page <= 1 ? 'is-disabled' : ''; ?>"
                                aria-label="Первая страница"
                                <?php echo $current_page <= 1 ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="m11 17-5-5 5-5"></path>
                                    <path d="m18 17-5-5 5-5"></path>
                                </svg>
                            </a>

                            <a
                                href="<?php echo esc_url( add_query_arg( $prev_args, $base_url ) ); ?>"
                                class="category-products__page <?php echo $current_page <= 1 ? 'is-disabled' : ''; ?>"
                                aria-label="Предыдущая страница"
                                <?php echo $current_page <= 1 ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                            </a>

                            <?php foreach ( $pagination_items as $item ) : ?>

                                <?php if ( 'dots' === $item ) : ?>
                                    <span class="category-products__dots">…</span>
                                <?php else : ?>
                                    <?php
                                    $page_args = array_merge(
                                        $filter_args,
                                        [ 'product_page' => (int) $item ]
                                    );
                                    ?>

                                    <a
                                        href="<?php echo esc_url( add_query_arg( $page_args, $base_url ) ); ?>"
                                        class="category-products__page <?php echo (int) $item === $current_page ? 'is-current' : ''; ?>"
                                        <?php echo (int) $item === $current_page ? 'aria-current="page"' : ''; ?>
                                    >
                                        <?php echo esc_html( $item ); ?>
                                    </a>
                                <?php endif; ?>

                            <?php endforeach; ?>

                            <a
                                href="<?php echo esc_url( add_query_arg( $next_args, $base_url ) ); ?>"
                                class="category-products__page <?php echo $current_page >= $total_pages ? 'is-disabled' : ''; ?>"
                                aria-label="Следующая страница"
                                <?php echo $current_page >= $total_pages ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </a>

                            <a
                                href="<?php echo esc_url( add_query_arg( $last_args, $base_url ) ); ?>"
                                class="category-products__page <?php echo $current_page >= $total_pages ? 'is-disabled' : ''; ?>"
                                aria-label="Последняя страница"
                                <?php echo $current_page >= $total_pages ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="m13 17 5-5-5-5"></path>
                                    <path d="m6 17 5-5-5-5"></path>
                                </svg>
                            </a>

                        </nav>

                    <?php endif; ?>

                <?php else : ?>

                    <?php wp_reset_postdata(); ?>

                    <div class="category-products__empty">
                        <p>Товары не найдены</p>
                        <span>Попробуйте изменить фильтры</span>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>
</section>
