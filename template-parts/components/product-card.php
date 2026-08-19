<?php
/**
 * Unified product card for home-page product sections.
 *
 * Expected args:
 * - product: WC_Product instance.
 * - exclude_category_id: optional parent category ID which should not be
 *   displayed as the product's category label.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$product = isset( $args['product'] ) && $args['product'] instanceof WC_Product
    ? $args['product']
    : null;

if ( ! $product ) {
    return;
}

$exclude_category_id = isset( $args['exclude_category_id'] )
    ? absint( $args['exclude_category_id'] )
    : 0;

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

$categories    = get_the_terms( $product_id, 'product_cat' );
$category_name = '';

if ( $categories && ! is_wp_error( $categories ) ) {
    foreach ( $categories as $category ) {
        if ( $exclude_category_id && (int) $category->term_id === $exclude_category_id ) {
            continue;
        }

        $category_name = $category->name;
        break;
    }

    if ( ! $category_name && isset( $categories[0] ) ) {
        $category_name = $categories[0]->name;
    }
}

$sku      = $product->get_sku();
$in_stock = $product->is_in_stock();
$quantity = $product->get_stock_quantity();
$unit     = function_exists( 'imidjstroy_product_unit' )
    ? imidjstroy_product_unit( $product )
    : 'шт.';

$badge = trim( (string) $product->get_meta( '_imidjstroy_badge', true ) );

if ( '' === $badge ) {
    $badge = trim( (string) $product->get_meta( 'badge', true ) );
}

if ( '' === $badge ) {
    $badge = trim( (string) $product->get_meta( '_badge', true ) );
}

if ( '' === $badge && $product->is_on_sale() ) {
    $badge = 'Скидка';
}

$is_discount = 'скидка' === mb_strtolower( $badge );
$available   = $in_stock && ( null === $quantity || $quantity > 0 );
?>

<article class="product-card popular-product-card">

    <a
        href="<?php echo esc_url( $product_url ); ?>"
        class="product-card__media popular-product-card__media"
        aria-label="<?php echo esc_attr( 'Подробнее о товаре: ' . $product->get_name() ); ?>"
    >
        <?php if ( '' !== $badge && 'none' !== mb_strtolower( $badge ) ) : ?>
            <span class="product-card__badge popular-product-card__badge <?php echo $is_discount ? 'is-discount' : ''; ?>">
                <?php echo esc_html( $badge ); ?>
            </span>
        <?php endif; ?>

        <?php if ( ! $available ) : ?>
            <span class="product-card__availability popular-product-card__availability">
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
            <div class="product-card__placeholder popular-product-card__placeholder">
                Фото товара
            </div>
        <?php endif; ?>
    </a>

    <div class="product-card__body popular-product-card__body">

        <?php if ( $category_name ) : ?>
            <span class="product-card__category popular-product-card__category">
                <?php echo esc_html( $category_name ); ?>
            </span>
        <?php endif; ?>

        <h3 class="product-card__name popular-product-card__name">
            <a href="<?php echo esc_url( $product_url ); ?>">
                <?php echo esc_html( $product->get_name() ); ?>
            </a>
        </h3>

        <?php if ( $description ) : ?>
            <p class="product-card__description popular-product-card__description">
                <?php echo esc_html( $description ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $sku ) : ?>
            <span class="product-card__sku popular-product-card__sku">
                Артикул: <?php echo esc_html( $sku ); ?>
            </span>
        <?php endif; ?>

        <?php if ( null !== $quantity ) : ?>
            <span class="product-card__stock popular-product-card__stock <?php echo $available ? 'is-in-stock' : 'is-out-of-stock'; ?>">
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
        <?php elseif ( $available ) : ?>
            <span class="product-card__stock popular-product-card__stock is-in-stock">
                В наличии
            </span>
        <?php endif; ?>

        <div class="product-card__footer popular-product-card__footer">

            <div class="product-card__price popular-product-card__price">
                <span class="product-card__amount popular-product-card__amount">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </span>

                <span class="product-card__unit popular-product-card__unit">
                    <?php echo esc_html( $unit ); ?>
                </span>
            </div>

            <div class="product-card__actions popular-product-card__actions">

                <a
                    href="<?php echo esc_url( $product_url ); ?>"
                    class="product-card__action product-card__action--view popular-product-card__action popular-product-card__action--view"
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
                        class="product-card__action product-card__action--cart popular-product-card__action popular-product-card__action--cart button add_to_cart_button ajax_add_to_cart"
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
                        class="product-card__action product-card__action--cart popular-product-card__action popular-product-card__action--cart is-disabled"
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
