<?php
/**
 * Imidjstroy order review.
 *
 * The root class "woocommerce-checkout-review-order-table" is intentionally
 * preserved because WooCommerce checkout.js replaces it via AJAX fragments.
 *
 * @package Imidjstroy
 */

defined( 'ABSPATH' ) || exit;

$total_items = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$delivery_method = WC()->session ? WC()->session->get( 'imidjstroy_delivery_method', 'pickup' ) : 'pickup';
$delivery_label  = function_exists( 'imidjstroy_checkout_delivery_label' )
    ? imidjstroy_checkout_delivery_label( $delivery_method )
    : ( 'delivery' === $delivery_method ? 'Доставка' : 'Самовывоз' );

$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
$chosen_payment     = WC()->session ? WC()->session->get( 'chosen_payment_method' ) : '';
$payment_label      = '';

if ( $chosen_payment && isset( $available_gateways[ $chosen_payment ] ) ) {
    $payment_label = $available_gateways[ $chosen_payment ]->get_title();
} elseif ( ! empty( $available_gateways ) ) {
    $first_gateway = reset( $available_gateways );
    $payment_label = $first_gateway ? $first_gateway->get_title() : '';
}
?>

<div class="woocommerce-checkout-review-order-table imidjstroy-checkout-review">
    <h2 class="imidjstroy-checkout-review__title">Ваш заказ</h2>

    <div class="imidjstroy-checkout-review__items">
        <?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
            <?php
            $_product = apply_filters(
                'woocommerce_cart_item_product',
                $cart_item['data'],
                $cart_item,
                $cart_item_key
            );

            $visible = apply_filters(
                'woocommerce_checkout_cart_item_visible',
                true,
                $cart_item,
                $cart_item_key
            );

            if ( ! ( $_product instanceof WC_Product ) || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! $visible ) {
                continue;
            }

            $name = apply_filters(
                'woocommerce_cart_item_name',
                $_product->get_name(),
                $cart_item,
                $cart_item_key
            );
            ?>

            <div class="imidjstroy-checkout-review__item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                <div class="imidjstroy-checkout-review__item-name">
                    <?php echo wp_kses_post( $name ); ?>
                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span class="imidjstroy-checkout-review__item-qty">× <?php echo esc_html( $cart_item['quantity'] ); ?></span>
                </div>

                <div class="imidjstroy-checkout-review__item-total">
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
                </div>
            </div>
        <?php endforeach; ?>

        <?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>
    </div>

    <div class="imidjstroy-checkout-review__box">
        <div class="imidjstroy-checkout-review__row">
            <span>Товаров</span>
            <strong><?php echo esc_html( $total_items ); ?></strong>
        </div>

        <div class="imidjstroy-checkout-review__row">
            <span>Сумма товаров</span>
            <strong><?php wc_cart_totals_subtotal_html(); ?></strong>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="imidjstroy-checkout-review__row">
                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <strong><?php wc_cart_totals_coupon_html( $coupon ); ?></strong>
            </div>
        <?php endforeach; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="imidjstroy-checkout-review__row">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <strong><?php wc_cart_totals_fee_html( $fee ); ?></strong>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $tax ) : ?>
                    <div class="imidjstroy-checkout-review__row">
                        <span><?php echo esc_html( $tax->label ); ?></span>
                        <strong><?php echo wp_kses_post( $tax->formatted_amount ); ?></strong>
                    </div>
                <?php endforeach; ?>
            <?php elseif ( WC()->cart->get_taxes_total() > 0 ) : ?>
                <div class="imidjstroy-checkout-review__row">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <strong><?php wc_cart_totals_taxes_total_html(); ?></strong>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="imidjstroy-checkout-review__row">
            <span>Доставка</span>
            <strong class="js-checkout-delivery-label"><?php echo esc_html( $delivery_label ); ?></strong>
        </div>

        <div class="imidjstroy-checkout-review__row">
            <span>Оплата</span>
            <strong class="js-checkout-payment-label"><?php echo esc_html( $payment_label ? $payment_label : 'Выберите способ' ); ?></strong>
        </div>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <div class="imidjstroy-checkout-review__row imidjstroy-checkout-review__row--total">
            <span>Итого</span>
            <strong><?php wc_cart_totals_order_total_html(); ?></strong>
        </div>

        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
    </div>
</div>
