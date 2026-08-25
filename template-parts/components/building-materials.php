<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [ 'title' => 'Стройматериалы', 'eyebrow' => 'Всё для ремонта и строительства', 'link_text' => 'Смотреть все', 'count' => 8 ] );
if ( '' === trim( (string) $args['eyebrow'] ) ) {
    $args['eyebrow'] = 'Всё для ремонта и строительства';
}
$args['count'] = max( 1, min( 12, absint( $args['count'] ) ) );

$building_products = [];
$building_parent   = null;

if ( class_exists( 'WooCommerce' ) && taxonomy_exists( 'product_cat' ) ) {
    $building_parent = get_term_by( 'name', 'Стройматериалы', 'product_cat' );

    if ( ! $building_parent || is_wp_error( $building_parent ) ) {
        $building_parent = get_term_by( 'slug', 'stroymaterialy', 'product_cat' );
    }

    if ( $building_parent && ! is_wp_error( $building_parent ) ) {
        $child_ids = get_term_children( $building_parent->term_id, 'product_cat' );

        if ( is_wp_error( $child_ids ) ) {
            $child_ids = [];
        }

        $category_ids = array_map(
            'absint',
            array_merge( [ $building_parent->term_id ], $child_ids )
        );

        $query = new WP_Query( [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $args['count'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [
                [
                    'taxonomy'         => 'product_cat',
                    'field'            => 'term_id',
                    'terms'            => $category_ids,
                    'include_children' => true,
                ],
            ],
        ] );

        $building_products = $query->posts;
        wp_reset_postdata();
    }
}
?>

<section class="home-building home-product-section">
    <div class="container">

        <div class="home-building__heading home-ad-materials__heading">
            <div>
                <span class="home-building__eyebrow home-ad-materials__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
                <h2 class="home-building__title"><?php echo esc_html( $args['title'] ); ?></h2>
            </div>

            <a
                href="<?php
                echo esc_url(
                    $building_parent && ! is_wp_error( $building_parent )
                        ? get_term_link( $building_parent )
                        : wc_get_page_permalink( 'shop' )
                );
                ?>"
                class="home-building__all"
            >
                <?php echo esc_html( $args['link_text'] ); ?>

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>

        <?php if ( ! empty( $building_products ) ) : ?>
            <div class="home-building__grid">
                <?php foreach ( $building_products as $product_post ) : ?>
                    <?php
                    $product = wc_get_product( $product_post->ID );

                    if ( ! $product ) {
                        continue;
                    }

                    get_template_part(
                        'template-parts/components/product-card',
                        null,
                        [
                            'product'             => $product,
                            'exclude_category_id' => $building_parent && ! is_wp_error( $building_parent )
                                ? $building_parent->term_id
                                : 0,
                        ]
                    );
                    ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="home-building__empty">
                В категории «Стройматериалы» пока нет товаров.
            </div>
        <?php endif; ?>

    </div>
</section>
