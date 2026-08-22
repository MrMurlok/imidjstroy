<?php
/**
 * Imidjstroy classic WooCommerce checkout.
 *
 * @package Imidjstroy
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', 'Для оформления заказа необходимо войти в аккаунт.' ) );
    return;
}

$delivery_method = 'pickup';

if ( WC()->session ) {
    $saved_method = WC()->session->get( 'imidjstroy_delivery_method' );

    if ( in_array( $saved_method, [ 'pickup', 'delivery' ], true ) ) {
        $delivery_method = $saved_method;
    }
}

if ( isset( $_POST['imidjstroy_delivery_method'] ) ) {
    $posted_method = sanitize_key( wp_unslash( $_POST['imidjstroy_delivery_method'] ) );

    if ( in_array( $posted_method, [ 'pickup', 'delivery' ], true ) ) {
        $delivery_method = $posted_method;
    }
}

$delivery_address = '';

if ( isset( $_POST['imidjstroy_delivery_address'] ) ) {
    $delivery_address = sanitize_text_field( wp_unslash( $_POST['imidjstroy_delivery_address'] ) );
} elseif ( WC()->customer ) {
    $delivery_address = WC()->customer->get_shipping_address_1();

    if ( '' === $delivery_address ) {
        $delivery_address = WC()->customer->get_billing_address_1();
    }
}
?>

<section class="imidjstroy-checkout">
    <div class="container imidjstroy-checkout__container">

        <header class="imidjstroy-checkout__header">
            <h1 class="imidjstroy-checkout__title">Оформление заказа</h1>
            <p class="imidjstroy-checkout__subtitle">
                Проверьте контактные данные, выберите доставку и способ оплаты.
            </p>
        </header>

        <form
            name="checkout"
            method="post"
            class="checkout woocommerce-checkout"
            action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
            enctype="multipart/form-data"
            aria-label="Оформление заказа"
        >
            <div class="imidjstroy-checkout__layout">

                <div class="imidjstroy-checkout__main">

                    <?php if ( $checkout->get_checkout_fields() ) : ?>
                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                        <section class="imidjstroy-checkout__card" id="customer_details">
                            <h2 class="imidjstroy-checkout__card-title">Контактные данные</h2>
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </section>

                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                    <?php endif; ?>

                    <section class="imidjstroy-checkout__card">
                        <h2 class="imidjstroy-checkout__card-title">Способ доставки</h2>

                        <div class="imidjstroy-delivery-options">
                            <div class="imidjstroy-choice">
                                <input
                                    class="imidjstroy-choice__input"
                                    type="radio"
                                    name="imidjstroy_delivery_method"
                                    id="imidjstroy_delivery_pickup"
                                    value="pickup"
                                    <?php checked( $delivery_method, 'pickup' ); ?>
                                >
                                <label class="imidjstroy-choice__label" for="imidjstroy_delivery_pickup">
                                    <span class="imidjstroy-choice__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M3 9l2-5h14l2 5"></path>
                                            <path d="M5 13v7h14v-7"></path>
                                            <path d="M9 20v-5h6v5"></path>
                                            <path d="M3 9h18v3a2 2 0 0 1-4 0 2 2 0 0 1-4 0 2 2 0 0 1-4 0 2 2 0 0 1-4 0 2 2 0 0 1-2-2V9z"></path>
                                        </svg>
                                    </span>
                                    <span class="imidjstroy-choice__text">
                                        <span class="imidjstroy-choice__title">Самовывоз</span>
                                        <span class="imidjstroy-choice__description">Забрать заказ самостоятельно</span>
                                    </span>
                                </label>
                            </div>

                            <div class="imidjstroy-choice">
                                <input
                                    class="imidjstroy-choice__input"
                                    type="radio"
                                    name="imidjstroy_delivery_method"
                                    id="imidjstroy_delivery_delivery"
                                    value="delivery"
                                    <?php checked( $delivery_method, 'delivery' ); ?>
                                >
                                <label class="imidjstroy-choice__label" for="imidjstroy_delivery_delivery">
                                    <span class="imidjstroy-choice__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M3 6h11v10H3z"></path>
                                            <path d="M14 10h4l3 3v3h-7z"></path>
                                            <circle cx="7" cy="18" r="2"></circle>
                                            <circle cx="18" cy="18" r="2"></circle>
                                        </svg>
                                    </span>
                                    <span class="imidjstroy-choice__text">
                                        <span class="imidjstroy-choice__title">Доставка</span>
                                        <span class="imidjstroy-choice__description">Доставка по указанному адресу</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div
                            class="imidjstroy-delivery-address js-delivery-address"
                            <?php echo 'delivery' === $delivery_method ? '' : 'hidden'; ?>
                        >
                            <label for="imidjstroy_delivery_address">Адрес доставки <span class="required">*</span></label>
                            <input
                                type="text"
                                id="imidjstroy_delivery_address"
                                name="imidjstroy_delivery_address"
                                value="<?php echo esc_attr( $delivery_address ); ?>"
                                placeholder="Введите адрес доставки"
                                autocomplete="street-address"
                            >
                        </div>
                    </section>

                    <section class="imidjstroy-checkout__card">
                        <h2 class="imidjstroy-checkout__card-title">Комментарий к заказу</h2>

                        <div class="imidjstroy-checkout__order-fields">
                            <?php
                            foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) {
                                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                            }
                            ?>
                        </div>
                    </section>

                </div>

                <aside class="imidjstroy-checkout__sidebar">
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                    <section class="imidjstroy-checkout__card" id="order_review">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </section>

                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                    <a class="imidjstroy-checkout__back" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        Вернуться в корзину
                    </a>
                </aside>

            </div>
        </form>

    </div>
</section>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
