<?php
/**
 * Custom single product page.
 *
 * Port of original React:
 * src/pages/ProductPage.tsx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
    the_post();

    $product = wc_get_product( get_the_ID() );

    if ( ! $product ) {
        continue;
    }

    set_query_var( 'imidjstroy_single_product', $product );
    get_template_part( 'template-parts/product/product-page' );

endwhile;

get_footer();
