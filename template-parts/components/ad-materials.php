<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$ad_products = [];
$ad_parent   = null;

if ( class_exists( 'WooCommerce' ) && taxonomy_exists( 'product_cat' ) ) {
    $candidate_names = [
        'Рекламные материалы',
        'Материалы для рекламы',
        'Рекламные материалы и оборудование',
    ];

    foreach ( $candidate_names as $candidate_name ) {
        $term = get_term_by( 'name', $candidate_name, 'product_cat' );

        if ( $term && ! is_wp_error( $term ) ) {
            $ad_parent = $term;
            break;
        }
    }

    if ( ! $ad_parent ) {
        $candidate_slugs = [
            'reklamnye-materialy',
            'materialy-dlya-reklamy',
            'reklamnye-materialy-i-oborudovanie',
        ];

        foreach ( $candidate_slugs as $candidate_slug ) {
            $term = get_term_by( 'slug', $candidate_slug, 'product_cat' );

            if ( $term && ! is_wp_error( $term ) ) {
                $ad_parent = $term;
                break;
            }
        }
    }

    if ( $ad_parent && ! is_wp_error( $ad_parent ) ) {
        $child_ids = get_term_children( $ad_parent->term_id, 'product_cat' );

        if ( is_wp_error( $child_ids ) ) {
            $child_ids = [];
        }

        $category_ids = array_map(
            'absint',
            array_merge( [ $ad_parent->term_id ], $child_ids )
        );

        $query = new WP_Query( [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 8,
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

        $ad_products = $query->posts;
        wp_reset_postdata();
    }
}
?>

<section class="home-ad-materials home-product-section">
    <div class="container">

        <div class="home-ad-materials__heading">
            <div>
                <span class="home-ad-materials__eyebrow">Для производства рекламы</span>
                <h2 class="home-ad-materials__title">Рекламные материалы</h2>
            </div>

            <a
                href="<?php
                echo esc_url(
                    $ad_parent && ! is_wp_error( $ad_parent )
                        ? get_term_link( $ad_parent )
                        : wc_get_page_permalink( 'shop' )
                );
                ?>"
                class="home-ad-materials__all"
            >
                Смотреть все

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>

        <?php if ( ! empty( $ad_products ) ) : ?>
            <div class="home-ad-materials__grid">
                <?php foreach ( $ad_products as $product_post ) : ?>
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
                            'exclude_category_id' => $ad_parent && ! is_wp_error( $ad_parent )
                                ? $ad_parent->term_id
                                : 0,
                        ]
                    );
                    ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="home-ad-materials__empty">
                В категории «Рекламные материалы» пока нет товаров.
            </div>
        <?php endif; ?>

    </div>
</section>
