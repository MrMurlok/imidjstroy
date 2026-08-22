<?php
/**
 * Imidjstroy payment method card.
 *
 * @package Imidjstroy
 */

defined( 'ABSPATH' ) || exit;

$gateway_id = $gateway->id;

$icon_type = 'card';
if ( 'imidjstroy_cash' === $gateway_id ) {
    $icon_type = 'wallet';
} elseif ( 'imidjstroy_invoice' === $gateway_id ) {
    $icon_type = 'landmark';
}
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway_id ); ?> imidjstroy-payment-method">
    <input
        id="payment_method_<?php echo esc_attr( $gateway_id ); ?>"
        type="radio"
        class="input-radio"
        name="payment_method"
        value="<?php echo esc_attr( $gateway_id ); ?>"
        <?php checked( $gateway->chosen, true ); ?>
        data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
    >

    <label class="imidjstroy-payment-method__label" for="payment_method_<?php echo esc_attr( $gateway_id ); ?>">
        <span class="imidjstroy-payment-method__icon" aria-hidden="true">
            <?php if ( 'wallet' === $icon_type ) : ?>
                <svg viewBox="0 0 24 24">
                    <path d="M4 6h14a2 2 0 0 1 2 2v10H4a2 2 0 0 1-2-2V6z"></path>
                    <path d="M4 6l12-3v3"></path>
                    <path d="M15 11h5v4h-5a2 2 0 0 1 0-4z"></path>
                </svg>
            <?php elseif ( 'landmark' === $icon_type ) : ?>
                <svg viewBox="0 0 24 24">
                    <path d="M3 10h18"></path>
                    <path d="M5 10v8"></path>
                    <path d="M9 10v8"></path>
                    <path d="M15 10v8"></path>
                    <path d="M19 10v8"></path>
                    <path d="M2 21h20"></path>
                    <path d="M12 3l9 5H3l9-5z"></path>
                </svg>
            <?php else : ?>
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <path d="M3 10h18"></path>
                    <path d="M7 15h2"></path>
                </svg>
            <?php endif; ?>
        </span>

        <span class="imidjstroy-payment-method__text">
            <span class="imidjstroy-payment-method__title">
                <?php echo wp_kses_post( $gateway->get_title() ); ?>
            </span>
            <?php if ( $gateway->get_description() ) : ?>
                <span class="imidjstroy-payment-method__description">
                    <?php echo wp_kses_post( $gateway->get_description() ); ?>
                </span>
            <?php endif; ?>
        </span>

        <?php if ( $gateway->get_icon() ) : ?>
            <span class="imidjstroy-payment-method__gateway-icon">
                <?php echo wp_kses_post( $gateway->get_icon() ); ?>
            </span>
        <?php endif; ?>
    </label>

    <?php if ( $gateway->has_fields() ) : ?>
        <div
            class="payment_box payment_method_<?php echo esc_attr( $gateway_id ); ?>"
            <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>
        >
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>
