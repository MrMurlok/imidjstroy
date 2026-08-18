<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$popular_products = [];

if ( class_exists( 'WooCommerce' ) ) {
    $query = new WP_Query( [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );

    $popular_products = $query->posts;
    wp_reset_postdata();
}

if ( empty( $popular_products ) ) {
    return;
}
?>

<section class="home-popular">
    <div class="container">

        <div class="home-popular__heading">
            <h2 class="home-popular__title">Популярные товары</h2>

            <a
                href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                class="home-popular__all"
            >
                Смотреть все

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>

        <div class="home-popular__grid">

            <?php foreach ( $popular_products as $product_post ) : ?>
                <?php
                $product = wc_get_product( $product_post->ID );

                if ( ! $product ) {
                    continue;
                }

                $product_id  = $product->get_id();
                $product_url = get_permalink( $product_id );
                $image_id    = $product->get_image_id();
                $image_url   = $image_id
                    ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' )
                    : '';

                $description = wp_strip_all_tags( $product->get_short_description() );

                if ( ! $description ) {
                    $description = wp_strip_all_tags( $product->get_description() );
                }

                $categories = get_the_terms( $product_id, 'product_cat' );
                $category_name = '';

                if ( $categories && ! is_wp_error( $categories ) && isset( $categories[0] ) ) {
                    $category_name = $categories[0]->name;
                }

                $sku      = $product->get_sku();
                $in_stock = $product->is_in_stock();
                $quantity = $product->get_stock_quantity();

                $unit = function_exists( 'imidjstroy_product_unit' )
                    ? imidjstroy_product_unit( $product )
                    : 'шт.';

                $badge = $product->get_meta( 'badge' );

                if ( ! $badge ) {
                    $badge = $product->get_meta( '_badge' );
                }

                if ( ! $badge && $product->is_on_sale() ) {
                    $badge = 'Скидка';
                }

                $is_discount = 'скидка' === mb_strtolower( trim( (string) $badge ) );

                $available = $in_stock && ( null === $quantity || $quantity > 0 );
                ?>

                <article class="popular-product-card">

                    <a
                        href="<?php echo esc_url( $product_url ); ?>"
                        class="popular-product-card__media"
                        aria-label="<?php echo esc_attr( 'Подробнее о товаре: ' . $product->get_name() ); ?>"
                    >

                        <?php if ( $badge && 'none' !== mb_strtolower( trim( (string) $badge ) ) ) : ?>
                            <span class="popular-product-card__badge <?php echo $is_discount ? 'is-discount' : ''; ?>">
                                <?php echo esc_html( $badge ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! $available ) : ?>
                            <span class="popular-product-card__availability">
                                Нет в наличии
                            </span>
                        <?php endif; ?>

                        <?php if ( $image_url ) : ?>
                            <img
                                src="<?php echo esc_url( $image_url ); ?>"
                                alt="<?php echo esc_attr( $product->get_name() ); ?>"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <div class="popular-product-card__placeholder">
                                Фото товара
                            </div>
                        <?php endif; ?>

                    </a>

                    <div class="popular-product-card__body">

                        <?php if ( $category_name ) : ?>
                            <span class="popular-product-card__category">
                                <?php echo esc_html( $category_name ); ?>
                            </span>
                        <?php endif; ?>

                        <h3 class="popular-product-card__name">
                            <?php echo esc_html( $product->get_name() ); ?>
                        </h3>

                        <?php if ( $description ) : ?>
                            <p class="popular-product-card__description">
                                <?php echo esc_html( $description ); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ( $sku ) : ?>
                            <span class="popular-product-card__sku">
                                Артикул: <?php echo esc_html( $sku ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( null !== $quantity ) : ?>
                            <span class="popular-product-card__stock <?php echo $available ? 'is-in-stock' : 'is-out-of-stock'; ?>">
                                <?php if ( $available ) : ?>
                                    В наличии:
                                    <?php echo esc_html( $quantity ); ?>
                                    <?php
                                    echo esc_html(
                                        function_exists( 'imidjstroy_unit_label' )
                                            ? imidjstroy_unit_label( $quantity, $unit )
                                            : $unit
                                    );
                                    ?>
                                <?php else : ?>
                                    Нет в наличии
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>

                        <div class="popular-product-card__footer">

                            <div class="popular-product-card__price">
                                <span class="popular-product-card__amount">
                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                </span>

                                <span class="popular-product-card__unit">
                                    <?php echo esc_html( $unit ); ?>
                                </span>
                            </div>

                            <div class="popular-product-card__actions">

                                <a
                                    href="<?php echo esc_url( $product_url ); ?>"
                                    class="popular-product-card__action popular-product-card__action--view"
                                    aria-label="Подробнее"
                                >
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>

                                <?php if ( $product->is_purchasable() && $available ) : ?>
                                    <a
                                        href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                                        data-quantity="1"
                                        data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                        data-product_sku="<?php echo esc_attr( $sku ); ?>"
                                        class="popular-product-card__action popular-product-card__action--cart button add_to_cart_button ajax_add_to_cart"
                                        aria-label="Добавить в корзину"
                                        rel="nofollow"
                                    >
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="19" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"></path>
                                        </svg>
                                    </a>
                                <?php else : ?>
                                    <span
                                        class="popular-product-card__action popular-product-card__action--cart is-disabled"
                                        aria-hidden="true"
                                    >
                                        <svg viewBox="0 0 24 24">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="19" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"></path>
                                        </svg>
                                    </span>
                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>
