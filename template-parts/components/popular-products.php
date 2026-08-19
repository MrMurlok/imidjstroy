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

<section class="home-popular home-product-section">
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

                get_template_part(
                    'template-parts/components/product-card',
                    null,
                    [ 'product' => $product ]
                );
                ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
