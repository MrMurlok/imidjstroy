<?php
/**
 * Imidjstroy checkout payment section.
 *
 * @package Imidjstroy
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment">
    <?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
        <h2 class="imidjstroy-payment__title">Способ оплаты</h2>

        <ul class="wc_payment_methods payment_methods methods" aria-label="Способы оплаты">
            <?php if ( ! empty( $available_gateways ) ) : ?>
                <?php foreach ( $available_gateways as $gateway ) : ?>
                    <?php wc_get_template( 'checkout/payment-method.php', [ 'gateway' => $gateway ] ); ?>
                <?php endforeach; ?>
            <?php else : ?>
                <li>
                    <?php
                    wc_print_notice(
                        apply_filters(
                            'woocommerce_no_available_payment_methods_message',
                            'Сейчас нет доступных способов оплаты. Пожалуйста, свяжитесь с нами для оформления заказа.'
                        ),
                        'notice'
                    );
                    ?>
                </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <div class="form-row place-order">
        <noscript>
            <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the Update Totals button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
            <br>
            <button
                type="submit"
                class="button alt"
                name="woocommerce_checkout_update_totals"
                value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"
            >
                <?php esc_html_e( 'Update totals', 'woocommerce' ); ?>
            </button>
        </noscript>

        <?php wc_get_template( 'checkout/terms.php' ); ?>

        <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

        <?php
        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'woocommerce_order_button_html',
            '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">Оформить заказ</button>'
        );
        ?>

        <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
    </div>
</div>

<?php
if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
}
