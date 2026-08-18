<?php
/**
 * Custom WooCommerce product archive router.
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_shop() ) {
    get_template_part( 'template-parts/catalog/catalog-landing' );
    get_footer();
    return;
}

if ( is_product_category() ) {
    $current_term = get_queried_object();

    if ( $current_term instanceof WP_Term ) {
        $children = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $current_term->term_id,
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
        ] );

        if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
            set_query_var( 'catalog_type_term', $current_term );
            set_query_var( 'catalog_type_children', $children );

            get_template_part( 'template-parts/catalog/catalog-type' );

            get_footer();
            return;
        }

        /*
         * Leaf category: exact React CategoryProducts page port.
         */
        set_query_var( 'category_products_term', $current_term );
        get_template_part( 'template-parts/catalog/category-products' );

        get_footer();
        return;
    }
}

/*
 * Fallback for any other WooCommerce archive.
 */
?>
<section class="catalog-native">
    <div class="container">
        <?php
        if ( woocommerce_product_loop() ) {

            do_action( 'woocommerce_before_shop_loop' );

            woocommerce_product_loop_start();

            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();

                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product' );
                }
            }

            woocommerce_product_loop_end();

            do_action( 'woocommerce_after_shop_loop' );

        } else {
            do_action( 'woocommerce_no_products_found' );
        }
        ?>
    </div>
</section>

<?php
get_footer();
