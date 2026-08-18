<?php
/**
 * Product card used by leaf category pages.
 *
 * Expected query var:
 * category_product
 */

defined( 'ABSPATH' ) || exit;

$product = get_query_var( 'category_product' );

if ( ! ( $product instanceof WC_Product ) ) {
    return;
}

$product_id   = $product->get_id();
$name         = $product->get_name();
$permalink    = $product->get_permalink();
$image_id     = $product->get_image_id();
$image_url    = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';
$description  = wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() );
$sku          = $product->get_sku();
$quantity     = $product->get_stock_quantity();
$stock_status = $product->get_stock_status();
$available    = $product->is_in_stock() && ( null === $quantity || $quantity > 0 );

$unit = trim( (string) $product->get_meta( '_imidjstroy_unit', true ) );

if ( '' === $unit ) {
    $attribute_unit = trim( (string) $product->get_attribute( 'pa_unit' ) );

    if ( '' !== $attribute_unit ) {
        $unit = $attribute_unit;
    }
}

if ( '' === $unit ) {
    $unit = 'шт.';
}

$badge = trim( (string) $product->get_meta( '_imidjstroy_badge', true ) );

if ( $product->is_on_sale() ) {
    $badge = 'Скидка';
}

$terms = get_the_terms( $product_id, 'product_cat' );
$category_name = '';

if ( $terms && ! is_wp_error( $terms ) ) {
    $category_name = $terms[0]->name;
}
?>

<article class="category-product-card">

    <a
        href="<?php echo esc_url( $permalink ); ?>"
        class="category-product-card__media"
        aria-label="<?php echo esc_attr( sprintf( 'Подробнее о товаре: %s', $name ) ); ?>"
    >
        <?php if ( '' !== $badge && 'none' !== mb_strtolower( $badge ) ) : ?>
            <span class="category-product-card__badge <?php echo 'Скидка' === $badge ? 'category-product-card__badge--sale' : ''; ?>">
                <?php echo esc_html( $badge ); ?>
            </span>
        <?php endif; ?>

        <?php if ( ! $available ) : ?>
            <span class="category-product-card__badge category-product-card__badge--stock">
                Нет в наличии
            </span>
        <?php endif; ?>

        <?php if ( $image_url ) : ?>
            <img
                src="<?php echo esc_url( $image_url ); ?>"
                alt="<?php echo esc_attr( $name ); ?>"
                class="category-product-card__image"
                loading="lazy"
            >
        <?php else : ?>
            <span class="category-product-card__placeholder">
                Фото товара
            </span>
        <?php endif; ?>
    </a>

    <div class="category-product-card__body">

        <?php if ( $category_name ) : ?>
            <span class="category-product-card__category">
                <?php echo esc_html( $category_name ); ?>
            </span>
        <?php endif; ?>

        <h2 class="category-product-card__name">
            <a href="<?php echo esc_url( $permalink ); ?>">
                <?php echo esc_html( $name ); ?>
            </a>
        </h2>

        <?php if ( $description ) : ?>
            <p class="category-product-card__description">
                <?php echo esc_html( $description ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $sku ) : ?>
            <span class="category-product-card__sku">
                Артикул: <?php echo esc_html( $sku ); ?>
            </span>
        <?php endif; ?>

        <?php if ( null !== $quantity ) : ?>
            <span class="category-product-card__stock <?php echo $available ? 'is-available' : 'is-unavailable'; ?>">
                <?php if ( $available ) : ?>
                    В наличии:
                    <?php echo esc_html( $quantity ); ?>
                    <?php echo esc_html( $unit ); ?>
                <?php else : ?>
                    Нет в наличии
                <?php endif; ?>
            </span>
        <?php endif; ?>

        <div class="category-product-card__bottom">

            <div class="category-product-card__price-wrap">
                <span class="category-product-card__price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </span>

                <span class="category-product-card__unit">
                    <?php echo esc_html( $unit ); ?>
                </span>
            </div>

            <div class="category-product-card__actions">

                <a
                    href="<?php echo esc_url( $permalink ); ?>"
                    class="category-product-card__button category-product-card__button--details"
                    aria-label="<?php echo esc_attr( sprintf( 'Подробнее о товаре: %s', $name ) ); ?>"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.2 8.6 6.1 6 12 6s8.8 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.8 15.4 17.9 18 12 18s-8.8-2.6-9.94-5.65Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </a>

                <?php if ( $available && $product->is_purchasable() ) : ?>
                    <a
                        href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                        data-quantity="1"
                        data-product_id="<?php echo esc_attr( $product_id ); ?>"
                        data-product_sku="<?php echo esc_attr( $sku ); ?>"
                        class="category-product-card__button category-product-card__button--cart add_to_cart_button ajax_add_to_cart"
                        rel="nofollow"
                        aria-label="<?php echo esc_attr( sprintf( 'Добавить в корзину: %s', $name ) ); ?>"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h7.78a2 2 0 0 0 2-1.58L20.12 6H5.12"></path>
                        </svg>
                    </a>
                <?php else : ?>
                    <span class="category-product-card__button category-product-card__button--cart is-disabled" aria-disabled="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h7.78a2 2 0 0 0 2-1.58L20.12 6H5.12"></path>
                        </svg>
                    </span>
                <?php endif; ?>

            </div>

        </div>

    </div>

</article>
