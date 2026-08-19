<?php
/**
 * Imidjstroy Cart Page.
 *
 * Custom classic WooCommerce cart template based on the current WooCommerce
 * cart API and the original React Cart.tsx design.
 *
 * @package Imidjstroy
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

$get_unit = static function ( $product ) {
    if ( ! ( $product instanceof WC_Product ) ) {
        return 'шт.';
    }

    $unit = trim( (string) $product->get_meta( '_imidjstroy_unit', true ) );

    if ( '' === $unit ) {
        $unit = trim( (string) $product->get_attribute( 'pa_unit' ) );
    }

    if ( '' === $unit && $product->is_type( 'variation' ) ) {
        $parent = wc_get_product( $product->get_parent_id() );

        if ( $parent instanceof WC_Product ) {
            $unit = trim( (string) $parent->get_meta( '_imidjstroy_unit', true ) );

            if ( '' === $unit ) {
                $unit = trim( (string) $parent->get_attribute( 'pa_unit' ) );
            }
        }
    }

    return '' !== $unit ? $unit : 'шт.';
};

$total_items = WC()->cart->get_cart_contents_count();

do_action( 'woocommerce_before_cart' );
?>

<section class="imidjstroy-cart">
    <div class="container imidjstroy-cart-notices">
        <?php wc_print_notices(); ?>
    </div>

    <div class="container imidjstroy-cart__container">

        <header class="imidjstroy-cart__header">
            <h1 class="imidjstroy-cart__title">Корзина</h1>
            <p class="imidjstroy-cart__subtitle">
                Товаров в корзине: <?php echo esc_html( $total_items ); ?>
            </p>
        </header>

        <form
            class="woocommerce-cart-form imidjstroy-cart__form"
            action="<?php echo esc_url( wc_get_cart_url() ); ?>"
            method="post"
        >
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="imidjstroy-cart__layout">

                <div class="imidjstroy-cart__items">
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
                        <?php
                        $_product = apply_filters(
                            'woocommerce_cart_item_product',
                            $cart_item['data'],
                            $cart_item,
                            $cart_item_key
                        );

                        $product_id = apply_filters(
                            'woocommerce_cart_item_product_id',
                            $cart_item['product_id'],
                            $cart_item,
                            $cart_item_key
                        );

                        $visible = apply_filters(
                            'woocommerce_cart_item_visible',
                            true,
                            $cart_item,
                            $cart_item_key
                        );

                        if (
                            ! ( $_product instanceof WC_Product ) ||
                            ! $_product->exists() ||
                            $cart_item['quantity'] <= 0 ||
                            ! $visible
                        ) {
                            continue;
                        }

                        $product_name = apply_filters(
                            'woocommerce_cart_item_name',
                            $_product->get_name(),
                            $cart_item,
                            $cart_item_key
                        );

                        $product_permalink = apply_filters(
                            'woocommerce_cart_item_permalink',
                            $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '',
                            $cart_item,
                            $cart_item_key
                        );

                        $thumbnail = apply_filters(
                            'woocommerce_cart_item_thumbnail',
                            $_product->get_image(
                                'woocommerce_thumbnail',
                                [
                                    'class'   => 'imidjstroy-cart-item__image',
                                    'loading' => 'lazy',
                                ]
                            ),
                            $cart_item,
                            $cart_item_key
                        );

                        $sku      = $_product->get_sku();
                        $unit     = $get_unit( $_product );
                        $quantity = $_product->get_stock_quantity();
                        $in_stock = $_product->is_in_stock();

                        if ( $_product->is_sold_individually() ) {
                            $min_quantity = 1;
                            $max_quantity = 1;
                        } else {
                            $min_quantity = 0;
                            $max_quantity = $_product->get_max_purchase_quantity();
                        }
                        ?>

                        <article class="imidjstroy-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <div class="imidjstroy-cart-item__media">
                                <?php if ( $product_permalink ) : ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>">
                                        <?php echo wp_kses_post( $thumbnail ); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo wp_kses_post( $thumbnail ); ?>
                                <?php endif; ?>
                            </div>

                            <div class="imidjstroy-cart-item__info">
                                <h2 class="imidjstroy-cart-item__name">
                                    <?php if ( $product_permalink ) : ?>
                                        <a href="<?php echo esc_url( $product_permalink ); ?>">
                                            <?php echo wp_kses_post( $product_name ); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo wp_kses_post( $product_name ); ?>
                                    <?php endif; ?>
                                </h2>

                                <?php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); ?>

                                <?php if ( $sku ) : ?>
                                    <p class="imidjstroy-cart-item__sku">
                                        Артикул: <?php echo esc_html( $sku ); ?>
                                    </p>
                                <?php endif; ?>

                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                                <p class="imidjstroy-cart-item__price">
                                    <?php
                                    echo wp_kses_post(
                                        apply_filters(
                                            'woocommerce_cart_item_price',
                                            WC()->cart->get_product_price( $_product ),
                                            $cart_item,
                                            $cart_item_key
                                        )
                                    );
                                    ?>
                                    <span>/ <?php echo esc_html( $unit ); ?></span>
                                </p>

                                <?php if ( $in_stock ) : ?>
                                    <p class="imidjstroy-cart-item__stock is-in-stock">
                                        <?php if ( null !== $quantity ) : ?>
                                            В наличии: <?php echo esc_html( $quantity ); ?> <?php echo esc_html( $unit ); ?>
                                        <?php else : ?>
                                            В наличии
                                        <?php endif; ?>
                                    </p>
                                <?php else : ?>
                                    <p class="imidjstroy-cart-item__stock is-out-of-stock">Нет в наличии</p>
                                <?php endif; ?>

                                <?php if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) : ?>
                                    <p class="imidjstroy-cart-item__backorder">
                                        <?php esc_html_e( 'Available on backorder', 'woocommerce' ); ?>
                                    </p>
                                <?php endif; ?>

                                <p class="imidjstroy-cart-item__subtotal">
                                    <span>Сумма:</span>
                                    <strong>
                                        <?php
                                        echo wp_kses_post(
                                            apply_filters(
                                                'woocommerce_cart_item_subtotal',
                                                WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
                                                $cart_item,
                                                $cart_item_key
                                            )
                                        );
                                        ?>
                                    </strong>
                                </p>
                            </div>

                            <div class="imidjstroy-cart-item__controls">
                                <div class="imidjstroy-cart-item__quantity js-cart-quantity">
                                    <?php if ( ! $_product->is_sold_individually() ) : ?>
                                        <button
                                            type="button"
                                            class="imidjstroy-cart-item__qty-button js-cart-qty-minus"
                                            aria-label="Уменьшить количество"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M5 12h14"></path>
                                            </svg>
                                        </button>
                                    <?php endif; ?>

                                    <?php
                                    $product_quantity = woocommerce_quantity_input(
                                        [
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $product_name,
                                        ],
                                        $_product,
                                        false
                                    );

                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        'woocommerce_cart_item_quantity',
                                        $product_quantity,
                                        $cart_item_key,
                                        $cart_item
                                    );
                                    ?>

                                    <?php if ( ! $_product->is_sold_individually() ) : ?>
                                        <button
                                            type="button"
                                            class="imidjstroy-cart-item__qty-button js-cart-qty-plus"
                                            aria-label="Увеличить количество"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 5v14"></path>
                                                <path d="M5 12h14"></path>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <?php
                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a role="button" href="%1$s" class="imidjstroy-cart-item__remove remove" aria-label="%2$s" data-product_id="%3$s" data-product_sku="%4$s"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v5"></path><path d="M14 11v5"></path></svg><span>Удалить</span></a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr( sprintf( 'Удалить %s из корзины', wp_strip_all_tags( $product_name ) ) ),
                                        esc_attr( $product_id ),
                                        esc_attr( $sku )
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </div>

                        </article>
                    <?php endforeach; ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>
                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </div>

                <aside class="imidjstroy-cart-summary">
                    <div class="imidjstroy-cart-summary__card">
                        <h2 class="imidjstroy-cart-summary__title">Оформление заказа</h2>

                        <div class="imidjstroy-cart-summary__box">
                            <div class="imidjstroy-cart-summary__row">
                                <span>Товаров</span>
                                <strong><?php echo esc_html( $total_items ); ?></strong>
                            </div>

                            <div class="imidjstroy-cart-summary__row">
                                <span>Сумма товаров</span>
                                <strong><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></strong>
                            </div>

                            <?php if ( WC()->cart->get_discount_total() > 0 ) : ?>
                                <div class="imidjstroy-cart-summary__row">
                                    <span>Скидка</span>
                                    <strong>-<?php echo wp_kses_post( wc_price( WC()->cart->get_discount_total() ) ); ?></strong>
                                </div>
                            <?php endif; ?>

                            <div class="imidjstroy-cart-summary__row imidjstroy-cart-summary__row--total">
                                <span>Итого</span>
                                <strong><?php echo wp_kses_post( WC()->cart->get_total() ); ?></strong>
                            </div>
                        </div>

                        <p class="imidjstroy-cart-summary__hint">
                            Доставка и способ оплаты выбираются при оформлении заказа.
                        </p>

                        <a
                            href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                            class="imidjstroy-cart-summary__checkout"
                        >
                            Оформить заказ
                        </a>
                    </div>

                    <a
                        href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                        class="imidjstroy-cart-summary__continue"
                    >
                        Продолжить покупки
                    </a>
                </aside>

            </div>

            <div class="imidjstroy-cart__native-actions" aria-hidden="true">
                <button
                    type="submit"
                    name="update_cart"
                    value="Обновить корзину"
                    class="js-cart-update"
                >
                    Обновить корзину
                </button>

                <?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
    </div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
