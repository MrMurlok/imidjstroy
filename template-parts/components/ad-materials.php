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

if ( ! function_exists( 'imidjstroy_product_unit' ) ) {
    function imidjstroy_product_unit( $product ) {
        if ( ! $product ) {
            return 'шт.';
        }

        $unit = $product->get_meta( 'unit' );

        if ( ! $unit ) {
            $unit = $product->get_meta( '_unit' );
        }

        if ( ! $unit ) {
            $unit = $product->get_attribute( 'pa_unit' );
        }

        if ( ! $unit ) {
            $unit = $product->get_attribute( 'Единица измерения' );
        }

        return $unit ? $unit : 'шт.';
    }
}

if ( ! function_exists( 'imidjstroy_unit_label' ) ) {
    function imidjstroy_unit_label( $quantity, $unit ) {
        $unit = trim( (string) $unit );
        $normalized = mb_strtolower( str_replace( '.', '', $unit ) );

        if ( 'шт' === $normalized ) {
            return 'шт.';
        }

        if ( 'м' === $normalized ) {
            return 'м';
        }

        if ( in_array( $normalized, [ 'м2', 'м²' ], true ) ) {
            return 'м²';
        }

        if ( in_array( $normalized, [ 'м3', 'м³' ], true ) ) {
            return 'м³';
        }

        return $unit;
    }
}
?>

<section class="home-ad-materials">
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

                    $product_url = get_permalink( $product->get_id() );
                    $image_id    = $product->get_image_id();
                    $image_url   = $image_id
                        ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' )
                        : '';

                    $categories = get_the_terms( $product->get_id(), 'product_cat' );
                    $category_name = '';

                    if ( $categories && ! is_wp_error( $categories ) ) {
                        foreach ( $categories as $category ) {
                            if (
                                $ad_parent &&
                                ! is_wp_error( $ad_parent ) &&
                                (int) $category->term_id !== (int) $ad_parent->term_id
                            ) {
                                $category_name = $category->name;
                                break;
                            }
                        }

                        if ( ! $category_name && isset( $categories[0] ) ) {
                            $category_name = $categories[0]->name;
                        }
                    }

                    $sku      = $product->get_sku();
                    $stock    = $product->is_in_stock();
                    $quantity = $product->get_stock_quantity();
                    $unit     = imidjstroy_product_unit( $product );

                    $badge = $product->get_meta( 'badge' );

                    if ( ! $badge ) {
                        $badge = $product->get_meta( '_badge' );
                    }

                    if ( ! $badge && $product->is_on_sale() ) {
                        $badge = 'Скидка';
                    }

                    $is_discount = 'скидка' === mb_strtolower( trim( (string) $badge ) );
                    ?>

                    <article class="ad-product-card">

                        <a
                            href="<?php echo esc_url( $product_url ); ?>"
                            class="ad-product-card__media"
                            aria-label="<?php echo esc_attr( $product->get_name() ); ?>"
                        >
                            <?php if ( $badge && 'none' !== mb_strtolower( trim( (string) $badge ) ) ) : ?>
                                <span class="ad-product-card__badge <?php echo $is_discount ? 'is-discount' : ''; ?>">
                                    <?php echo esc_html( $badge ); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ( $image_url ) : ?>
                                <img
                                    src="<?php echo esc_url( $image_url ); ?>"
                                    alt="<?php echo esc_attr( $product->get_name() ); ?>"
                                    loading="lazy"
                                >
                            <?php else : ?>
                                <div class="ad-product-card__placeholder">
                                    Фото товара
                                </div>
                            <?php endif; ?>
                        </a>

                        <div class="ad-product-card__body">

                            <?php if ( $category_name ) : ?>
                                <span class="ad-product-card__category">
                                    <?php echo esc_html( $category_name ); ?>
                                </span>
                            <?php endif; ?>

                            <h3 class="ad-product-card__name">
                                <a href="<?php echo esc_url( $product_url ); ?>">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </a>
                            </h3>

                            <?php if ( $sku ) : ?>
                                <span class="ad-product-card__sku">
                                    Артикул: <?php echo esc_html( $sku ); ?>
                                </span>
                            <?php endif; ?>

                            <span class="ad-product-card__stock <?php echo $stock ? 'is-in-stock' : 'is-out-of-stock'; ?>">
                                <?php if ( $stock ) : ?>

                                    <?php if ( null !== $quantity ) : ?>
                                        В наличии:
                                        <?php echo esc_html( $quantity ); ?>
                                        <?php echo esc_html( imidjstroy_unit_label( $quantity, $unit ) ); ?>
                                    <?php else : ?>
                                        В наличии
                                    <?php endif; ?>

                                <?php else : ?>
                                    Нет в наличии
                                <?php endif; ?>
                            </span>

                            <div class="ad-product-card__footer">
                                <div class="ad-product-card__price">
                                    <span class="ad-product-card__amount">
                                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                    </span>

                                    <span class="ad-product-card__unit">
                                        <?php echo esc_html( $unit ); ?>
                                    </span>
                                </div>

                                <?php if ( $product->is_purchasable() && $stock ) : ?>
                                    <a
                                        href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                                        data-quantity="1"
                                        data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                                        data-product_sku="<?php echo esc_attr( $sku ); ?>"
                                        class="ad-product-card__cart button add_to_cart_button ajax_add_to_cart"
                                        rel="nofollow"
                                    >
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="19" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"></path>
                                        </svg>

                                        <span>В корзину</span>
                                    </a>
                                <?php else : ?>
                                    <span class="ad-product-card__cart is-disabled">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="19" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"></path>
                                        </svg>

                                        <span>В корзину</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </article>

                <?php endforeach; ?>

            </div>

        <?php else : ?>

            <div class="home-ad-materials__empty">
                В категории «Рекламные материалы» пока нет товаров.
            </div>

        <?php endif; ?>

    </div>
</section>
