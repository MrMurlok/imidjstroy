<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_template_directory() . '/inc/site-settings.php';
require_once get_template_directory() . '/inc/home-blocks.php';

function imidjstroy_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );

    add_theme_support( 'woocommerce' );

    register_nav_menus( [
        'header_menu' => 'Главное меню',
        'footer_menu' => 'Меню в подвале',
    ] );
}

add_action( 'after_setup_theme', 'imidjstroy_setup' );


function imidjstroy_assets() {

wp_enqueue_style(
    'imidjstroy-fonts',
    'https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;600;700&display=swap',
    [],
    null
);

    wp_enqueue_style(
        'imidjstroy-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_script(
        'imidjstroy-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        wp_get_theme()->get( 'Version' ),
        true
    );
}

add_action( 'wp_enqueue_scripts', 'imidjstroy_assets' );

/* =========================================================
   CONTACT FORM HANDLER
========================================================= */

function imidjstroy_handle_contact_form() {

    $redirect_url = home_url( '/' ) . '#contact-form';

    if (
        ! isset( $_POST['imidjstroy_contact_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['imidjstroy_contact_nonce'] ) ),
            'imidjstroy_contact_form'
        )
    ) {
        wp_safe_redirect(
            add_query_arg( 'contact_status', 'error', $redirect_url )
        );
        exit;
    }

    // Honeypot. Bots often fill this hidden field.
    if (
        isset( $_POST['website'] ) &&
        '' !== trim( sanitize_text_field( wp_unslash( $_POST['website'] ) ) )
    ) {
        wp_safe_redirect(
            add_query_arg( 'contact_status', 'success', $redirect_url )
        );
        exit;
    }

    $name = isset( $_POST['name'] )
        ? sanitize_text_field( wp_unslash( $_POST['name'] ) )
        : '';

    $phone = isset( $_POST['phone'] )
        ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )
        : '';

    $message = isset( $_POST['message'] )
        ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) )
        : '';

    if ( '' === $name || '' === $phone ) {
        wp_safe_redirect(
            add_query_arg( 'contact_status', 'required', $redirect_url )
        );
        exit;
    }

    // Basic limits against oversized spam submissions.
    $name    = mb_substr( $name, 0, 120 );
    $phone   = mb_substr( $phone, 0, 50 );
    $message = mb_substr( $message, 0, 3000 );

    $recipient = sanitize_email( imidjstroy_get_site_setting( 'form_recipient_email' ) );

    $subject = sprintf(
        'Новая заявка с сайта Имидж Строй — %s',
        $name
    );

    $body  = "Новая заявка с сайта Имидж Строй\n\n";
    $body .= "ФИО: {$name}\n";
    $body .= "Телефон: {$phone}\n";

    if ( '' !== $message ) {
        $body .= "Сообщение: {$message}\n";
    }

    $body .= "\nСтраница: " . home_url( '/' ) . "\n";
    $body .= 'Дата: ' . wp_date( 'd.m.Y H:i' ) . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $sent = wp_mail(
        $recipient,
        $subject,
        $body,
        $headers
    );

    wp_safe_redirect(
        add_query_arg(
            'contact_status',
            $sent ? 'success' : 'error',
            $redirect_url
        )
    );

    exit;
}

add_action(
    'admin_post_nopriv_imidjstroy_contact_form',
    'imidjstroy_handle_contact_form'
);

add_action(
    'admin_post_imidjstroy_contact_form',
    'imidjstroy_handle_contact_form'
);

/* =========================================================
   PRODUCT CARD HELPERS
========================================================= */

if ( ! function_exists( 'imidjstroy_product_unit' ) ) {
    function imidjstroy_product_unit( $product ) {
        if ( ! ( $product instanceof WC_Product ) ) {
            return 'шт.';
        }

        $candidates = [
            $product->get_meta( '_imidjstroy_unit', true ),
            $product->get_attribute( 'pa_unit' ),
            $product->get_meta( 'unit', true ),
            $product->get_meta( '_unit', true ),
            $product->get_attribute( 'Единица измерения' ),
        ];

        foreach ( $candidates as $candidate ) {
            $candidate = trim( (string) $candidate );

            if ( '' !== $candidate ) {
                return $candidate;
            }
        }

        return 'шт.';
    }
}

if ( ! function_exists( 'imidjstroy_unit_label' ) ) {
    function imidjstroy_unit_label( $quantity, $unit ) {
        $unit       = trim( (string) $unit );
        $normalized = mb_strtolower( str_replace( '.', '', $unit ) );

        if ( 'шт' === $normalized ) {
            return 'шт.';
        }

        if ( 'м' === $normalized ) {
            return 'м';
        }

        if ( in_array( $normalized, [ 'м2', 'м²' ], true ) ) {
            return 'м²';
        }

        if ( in_array( $normalized, [ 'м3', 'м³' ], true ) ) {
            return 'м³';
        }

        return '' !== $unit ? $unit : 'шт.';
    }
}

/* =========================================================
   CART NOTICES
========================================================= */

/**
 * Make the removed-item notice read naturally in Russian:
 * "Товар «Название» удалён."
 */
function imidjstroy_cart_removed_item_title( $title, $cart_item ) {
    unset( $cart_item );

    return 'Товар ' . $title;
}
add_filter( 'woocommerce_cart_item_removed_title', 'imidjstroy_cart_removed_item_title', 10, 2 );

/* =========================================================
   CHECKOUT
========================================================= */


/**
 * Imidjstroy uses its own pickup/delivery selector instead of native
 * WooCommerce shipping rates. Prevent WooCommerce core from blocking
 * checkout with "No shipping method selected".
 *
 * Our own delivery validation remains active:
 * - pickup: no address required;
 * - delivery: imidjstroy_delivery_address is required.
 */
function imidjstroy_disable_native_cart_shipping_requirement( $needs_shipping ) {
    if ( is_admin() && ! wp_doing_ajax() ) {
        return $needs_shipping;
    }

    return false;
}
add_filter(
    'woocommerce_cart_needs_shipping',
    'imidjstroy_disable_native_cart_shipping_requirement',
    100
);

/**
 * Keep the classic checkout compact: contacts are billing data,
 * while delivery address is stored separately by our checkout UI.
 */
function imidjstroy_checkout_fields( $fields ) {
    if ( isset( $fields['billing'] ) ) {
        $keep = [
            'billing_first_name',
            'billing_last_name',
            'billing_phone',
            'billing_email',
        ];

        foreach ( array_keys( $fields['billing'] ) as $key ) {
            if ( ! in_array( $key, $keep, true ) ) {
                unset( $fields['billing'][ $key ] );
            }
        }

        if ( isset( $fields['billing']['billing_first_name'] ) ) {
            $fields['billing']['billing_first_name']['label']       = 'Имя';
            $fields['billing']['billing_first_name']['placeholder'] = 'Введите имя';
            $fields['billing']['billing_first_name']['priority']    = 10;
            $fields['billing']['billing_first_name']['class']       = [ 'form-row-first' ];
        }

        if ( isset( $fields['billing']['billing_last_name'] ) ) {
            $fields['billing']['billing_last_name']['label']       = 'Фамилия';
            $fields['billing']['billing_last_name']['placeholder'] = 'Введите фамилию';
            $fields['billing']['billing_last_name']['priority']    = 20;
            $fields['billing']['billing_last_name']['class']       = [ 'form-row-last' ];
        }

        if ( isset( $fields['billing']['billing_phone'] ) ) {
            $fields['billing']['billing_phone']['label']       = 'Телефон';
            $fields['billing']['billing_phone']['placeholder'] = '+7 (___) ___-__-__';
            $fields['billing']['billing_phone']['priority']    = 30;
            $fields['billing']['billing_phone']['class']       = [ 'form-row-wide' ];
        }

        if ( isset( $fields['billing']['billing_email'] ) ) {
            $fields['billing']['billing_email']['label']       = 'Email';
            $fields['billing']['billing_email']['placeholder'] = 'example@mail.ru';
            $fields['billing']['billing_email']['priority']    = 40;
            $fields['billing']['billing_email']['class']       = [ 'form-row-wide' ];
        }
    }

    // Shipping fields are replaced by the project delivery selector/address.
    $fields['shipping'] = [];

    if ( isset( $fields['order']['order_comments'] ) ) {
        $fields['order']['order_comments']['label']       = 'Комментарий к заказу';
        $fields['order']['order_comments']['placeholder'] = 'Дополнительная информация для заказа';
        $fields['order']['order_comments']['class']       = [ 'form-row-wide' ];
    }

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'imidjstroy_checkout_fields', 20 );

/**
 * Remember delivery choice during WooCommerce checkout AJAX refreshes.
 */
function imidjstroy_checkout_update_delivery_session( $post_data ) {
    if ( ! WC()->session ) {
        return;
    }

    parse_str( $post_data, $data );

    if ( isset( $data['imidjstroy_delivery_method'] ) ) {
        $method = sanitize_key( $data['imidjstroy_delivery_method'] );

        if ( in_array( $method, [ 'pickup', 'delivery' ], true ) ) {
            WC()->session->set( 'imidjstroy_delivery_method', $method );
        }
    }
}
add_action( 'woocommerce_checkout_update_order_review', 'imidjstroy_checkout_update_delivery_session' );

function imidjstroy_checkout_delivery_label( $method ) {
    return 'delivery' === $method ? 'Доставка' : 'Самовывоз';
}

/**
 * Validate our project-specific delivery fields.
 */
function imidjstroy_checkout_validate_delivery() {
    $method = isset( $_POST['imidjstroy_delivery_method'] )
        ? sanitize_key( wp_unslash( $_POST['imidjstroy_delivery_method'] ) )
        : 'pickup';

    if ( ! in_array( $method, [ 'pickup', 'delivery' ], true ) ) {
        wc_add_notice( 'Выберите способ доставки.', 'error' );
        return;
    }

    if ( 'delivery' === $method ) {
        $address = isset( $_POST['imidjstroy_delivery_address'] )
            ? sanitize_text_field( wp_unslash( $_POST['imidjstroy_delivery_address'] ) )
            : '';

        if ( '' === trim( $address ) ) {
            wc_add_notice( 'Укажите адрес доставки.', 'error' );
        }
    }
}
add_action( 'woocommerce_checkout_process', 'imidjstroy_checkout_validate_delivery' );

/**
 * Save delivery data into the native WooCommerce order.
 */
function imidjstroy_checkout_save_delivery_to_order( $order, $data ) {
    unset( $data );

    $method = isset( $_POST['imidjstroy_delivery_method'] )
        ? sanitize_key( wp_unslash( $_POST['imidjstroy_delivery_method'] ) )
        : 'pickup';

    if ( ! in_array( $method, [ 'pickup', 'delivery' ], true ) ) {
        $method = 'pickup';
    }

    $address = isset( $_POST['imidjstroy_delivery_address'] )
        ? sanitize_text_field( wp_unslash( $_POST['imidjstroy_delivery_address'] ) )
        : '';

    $label = imidjstroy_checkout_delivery_label( $method );

    $order->update_meta_data( '_imidjstroy_delivery_method', $method );
    $order->update_meta_data( '_imidjstroy_delivery_label', $label );

    if ( '' === $order->get_billing_country() && function_exists( 'WC' ) && WC()->countries ) {
        $order->set_billing_country( WC()->countries->get_base_country() );
    }

    if ( 'delivery' === $method ) {
        $order->update_meta_data( '_imidjstroy_delivery_address', $address );
        $order->set_shipping_first_name( $order->get_billing_first_name() );
        $order->set_shipping_last_name( $order->get_billing_last_name() );
        $order->set_shipping_address_1( $address );

        if ( function_exists( 'WC' ) && WC()->countries ) {
            $order->set_shipping_country( WC()->countries->get_base_country() );
        }
    }
}
add_action( 'woocommerce_checkout_create_order', 'imidjstroy_checkout_save_delivery_to_order', 20, 2 );

/**
 * Show delivery data in WooCommerce order emails.
 */
function imidjstroy_checkout_email_meta_fields( $fields, $sent_to_admin, $order ) {
    unset( $sent_to_admin );

    if ( ! ( $order instanceof WC_Order ) ) {
        return $fields;
    }

    $method  = $order->get_meta( '_imidjstroy_delivery_method' );
    $label   = $order->get_meta( '_imidjstroy_delivery_label' );
    $address = $order->get_meta( '_imidjstroy_delivery_address' );

    if ( $method ) {
        $fields['imidjstroy_delivery_method'] = [
            'label' => 'Способ доставки',
            'value' => $label ? $label : imidjstroy_checkout_delivery_label( $method ),
        ];
    }

    if ( 'delivery' === $method && $address ) {
        $fields['imidjstroy_delivery_address'] = [
            'label' => 'Адрес доставки',
            'value' => $address,
        ];
    }

    return $fields;
}
add_filter( 'woocommerce_email_order_meta_fields', 'imidjstroy_checkout_email_meta_fields', 20, 3 );

/**
 * Show delivery information in the order screen in wp-admin.
 */
function imidjstroy_checkout_admin_delivery_meta( $order ) {
    if ( ! ( $order instanceof WC_Order ) ) {
        return;
    }

    $method  = $order->get_meta( '_imidjstroy_delivery_method' );
    $label   = $order->get_meta( '_imidjstroy_delivery_label' );
    $address = $order->get_meta( '_imidjstroy_delivery_address' );

    if ( ! $method ) {
        return;
    }

    echo '<p><strong>Способ доставки:</strong> ' . esc_html( $label ? $label : imidjstroy_checkout_delivery_label( $method ) ) . '</p>';

    if ( 'delivery' === $method && $address ) {
        echo '<p><strong>Адрес доставки:</strong> ' . esc_html( $address ) . '</p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'imidjstroy_checkout_admin_delivery_meta' );

/**
 * Three project offline gateways matching the original React checkout.
 * They remain editable from WooCommerce > Settings > Payments.
 */
function imidjstroy_init_offline_payment_gateways() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) || class_exists( 'WC_Gateway_Imidjstroy_Offline' ) ) {
        return;
    }

    class WC_Gateway_Imidjstroy_Offline extends WC_Payment_Gateway {
        protected $imidjstroy_default_title = '';
        protected $imidjstroy_default_description = '';
        protected $imidjstroy_order_status = 'on-hold';
        protected $imidjstroy_order_note = '';

        public function setup_gateway( $id, $method_title, $title, $description, $status, $note ) {
            $this->id                 = $id;
            $this->method_title       = $method_title;
            $this->method_description = $description;
            $this->has_fields         = false;
            $this->supports           = [ 'products' ];

            $this->imidjstroy_default_title       = $title;
            $this->imidjstroy_default_description = $description;
            $this->imidjstroy_order_status        = $status;
            $this->imidjstroy_order_note          = $note;

            $this->init_form_fields();
            $this->init_settings();

            $this->enabled     = $this->get_option( 'enabled', 'yes' );
            $this->title       = $this->get_option( 'title', $title );
            $this->description = $this->get_option( 'description', $description );

            add_action(
                'woocommerce_update_options_payment_gateways_' . $this->id,
                [ $this, 'process_admin_options' ]
            );
        }

        public function init_form_fields() {
            $this->form_fields = [
                'enabled' => [
                    'title'   => 'Включить/выключить',
                    'type'    => 'checkbox',
                    'label'   => 'Включить этот способ оплаты',
                    'default' => 'yes',
                ],
                'title' => [
                    'title'       => 'Название',
                    'type'        => 'text',
                    'default'     => $this->imidjstroy_default_title,
                    'desc_tip'    => true,
                    'description' => 'Название, которое увидит покупатель при оформлении заказа.',
                ],
                'description' => [
                    'title'       => 'Описание',
                    'type'        => 'textarea',
                    'default'     => $this->imidjstroy_default_description,
                    'description' => 'Краткое пояснение для покупателя.',
                ],
            ];
        }

        public function process_payment( $order_id ) {
            $order = wc_get_order( $order_id );

            if ( ! $order ) {
                return [ 'result' => 'failure' ];
            }

            $status = $this->imidjstroy_order_status;

            if ( 'processing' === $status && ! $order->needs_processing() ) {
                $status = 'completed';
            }

            $order->update_status( $status, $this->imidjstroy_order_note );

            if ( WC()->cart ) {
                WC()->cart->empty_cart();
            }

            return [
                'result'   => 'success',
                'redirect' => $this->get_return_url( $order ),
            ];
        }
    }

    class WC_Gateway_Imidjstroy_Cash extends WC_Gateway_Imidjstroy_Offline {
        public function __construct() {
            $this->setup_gateway(
                'imidjstroy_cash',
                'Имидж Строй — При получении',
                'При получении',
                'Оплата при получении заказа',
                'processing',
                'Заказ оформлен с оплатой при получении.'
            );
        }
    }

    class WC_Gateway_Imidjstroy_Transfer extends WC_Gateway_Imidjstroy_Offline {
        public function __construct() {
            $this->setup_gateway(
                'imidjstroy_transfer',
                'Имидж Строй — Переводом',
                'Переводом',
                'Оплата переводом по реквизитам',
                'on-hold',
                'Заказ ожидает оплату переводом.'
            );
        }
    }

    class WC_Gateway_Imidjstroy_Invoice extends WC_Gateway_Imidjstroy_Offline {
        public function __construct() {
            $this->setup_gateway(
                'imidjstroy_invoice',
                'Имидж Строй — Оплата по счёту',
                'Оплата по счёту',
                'Выставление счёта для оплаты',
                'on-hold',
                'Заказ ожидает выставления и оплаты счёта.'
            );
        }
    }

    add_filter(
        'woocommerce_payment_gateways',
        static function ( $gateways ) {
            $gateways[] = 'WC_Gateway_Imidjstroy_Cash';
            $gateways[] = 'WC_Gateway_Imidjstroy_Transfer';
            $gateways[] = 'WC_Gateway_Imidjstroy_Invoice';

            return $gateways;
        }
    );
}
add_action( 'after_setup_theme', 'imidjstroy_init_offline_payment_gateways', 30 );


/**
 * Hide the standard WooCommerce coupon prompt on the custom checkout page.
 * Coupons are not part of the current Imidjstroy checkout UI.
 */
function imidjstroy_hide_checkout_coupon_form() {
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
    }
}
add_action( 'wp', 'imidjstroy_hide_checkout_coupon_form', 20 );

/* =========================================================
   MY ACCOUNT
========================================================= */

/**
 * Keep the customer account focused on the flows used by this project.
 */
function imidjstroy_account_menu_items( $items ) {
    return [
        'edit-account'     => 'Профиль',
        'orders'           => 'Заказы',
        'customer-logout'  => 'Выйти',
    ];
}
add_filter( 'woocommerce_account_menu_items', 'imidjstroy_account_menu_items', 50 );

/**
 * The header account button opens the useful profile screen directly.
 */
function imidjstroy_account_root_redirect() {
    if (
        function_exists( 'is_account_page' ) &&
        is_account_page() &&
        is_user_logged_in() &&
        function_exists( 'is_wc_endpoint_url' ) &&
        ! is_wc_endpoint_url()
    ) {
        wp_safe_redirect( wc_get_account_endpoint_url( 'edit-account' ) );
        exit;
    }
}
add_action( 'template_redirect', 'imidjstroy_account_root_redirect', 20 );

/**
 * Registration fields used by the custom WooCommerce account UI.
 */
function imidjstroy_validate_registration_fields( $errors, $username, $email ) {
    $first_name = isset( $_POST['reg_first_name'] )
        ? trim( sanitize_text_field( wp_unslash( $_POST['reg_first_name'] ) ) )
        : '';
    $last_name = isset( $_POST['reg_last_name'] )
        ? trim( sanitize_text_field( wp_unslash( $_POST['reg_last_name'] ) ) )
        : '';

    if ( '' === $first_name ) {
        $errors->add( 'imidjstroy_reg_first_name', 'Введите имя.' );
    }

    if ( '' === $last_name ) {
        $errors->add( 'imidjstroy_reg_last_name', 'Введите фамилию.' );
    }

    return $errors;
}
add_filter( 'woocommerce_registration_errors', 'imidjstroy_validate_registration_fields', 20, 3 );

function imidjstroy_save_registration_fields( $customer_id ) {
    $first_name = isset( $_POST['reg_first_name'] )
        ? sanitize_text_field( wp_unslash( $_POST['reg_first_name'] ) )
        : '';
    $last_name = isset( $_POST['reg_last_name'] )
        ? sanitize_text_field( wp_unslash( $_POST['reg_last_name'] ) )
        : '';
    $phone = isset( $_POST['reg_billing_phone'] )
        ? sanitize_text_field( wp_unslash( $_POST['reg_billing_phone'] ) )
        : '';

    if ( $first_name ) {
        update_user_meta( $customer_id, 'first_name', $first_name );
        update_user_meta( $customer_id, 'billing_first_name', $first_name );
    }

    if ( $last_name ) {
        update_user_meta( $customer_id, 'last_name', $last_name );
        update_user_meta( $customer_id, 'billing_last_name', $last_name );
    }

    if ( $phone ) {
        update_user_meta( $customer_id, 'billing_phone', $phone );
    }

    $user = get_userdata( $customer_id );
    if ( $user && $user->user_email ) {
        update_user_meta( $customer_id, 'billing_email', $user->user_email );
    }

    if ( $first_name || $last_name ) {
        $display_name = trim( $first_name . ' ' . $last_name );
        if ( $display_name ) {
            wp_update_user(
                [
                    'ID'           => $customer_id,
                    'display_name' => $display_name,
                ]
            );
        }
    }
}
add_action( 'woocommerce_created_customer', 'imidjstroy_save_registration_fields', 20 );

/**
 * Save project-specific profile fields and keep WooCommerce billing data in sync.
 * Checkout reads these fields automatically for logged-in customers.
 */
function imidjstroy_save_account_profile_fields( $user_id ) {
    $phone = isset( $_POST['billing_phone'] )
        ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) )
        : '';
    $address = isset( $_POST['billing_address_1'] )
        ? sanitize_text_field( wp_unslash( $_POST['billing_address_1'] ) )
        : '';

    update_user_meta( $user_id, 'billing_phone', $phone );
    update_user_meta( $user_id, 'billing_address_1', $address );

    $first_name = get_user_meta( $user_id, 'first_name', true );
    $last_name  = get_user_meta( $user_id, 'last_name', true );
    $user       = get_userdata( $user_id );

    update_user_meta( $user_id, 'billing_first_name', $first_name );
    update_user_meta( $user_id, 'billing_last_name', $last_name );

    if ( $user && $user->user_email ) {
        update_user_meta( $user_id, 'billing_email', $user->user_email );
    }
}
add_action( 'woocommerce_save_account_details', 'imidjstroy_save_account_profile_fields', 20 );

/**
 * Show the project delivery choice in the customer's order details too.
 */
function imidjstroy_account_order_delivery_details( $order ) {
    if ( ! $order instanceof WC_Order ) {
        return;
    }

    $method  = $order->get_meta( '_imidjstroy_delivery_method' );
    $label   = $order->get_meta( '_imidjstroy_delivery_label' );
    $address = $order->get_meta( '_imidjstroy_delivery_address' );

    if ( ! $method ) {
        return;
    }
    ?>
    <section class="imidjstroy-order-delivery">
        <h2>Получение заказа</h2>
        <div class="imidjstroy-order-delivery__rows">
            <div>
                <span>Способ</span>
                <strong><?php echo esc_html( $label ? $label : imidjstroy_checkout_delivery_label( $method ) ); ?></strong>
            </div>
            <?php if ( 'delivery' === $method && $address ) : ?>
                <div>
                    <span>Адрес</span>
                    <strong><?php echo esc_html( $address ); ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}
add_action( 'woocommerce_order_details_after_order_table', 'imidjstroy_account_order_delivery_details', 20 );


/* =========================================================
   CONTENT PAGES / BLOG / CONTACTS
========================================================= */

/**
 * Keep the public blog grid compact and predictable.
 */
function imidjstroy_blog_posts_per_page( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( $query->is_home() ) {
        $query->set( 'posts_per_page', 9 );
    }
}
add_action( 'pre_get_posts', 'imidjstroy_blog_posts_per_page' );

/**
 * Contacts page form handler.
 */
function imidjstroy_handle_contacts_page_form() {
    $redirect_url = home_url( '/contacts/' ) . '#contact-form';

    if (
        ! isset( $_POST['imidjstroy_contacts_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['imidjstroy_contacts_nonce'] ) ),
            'imidjstroy_contacts_form'
        )
    ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'error', $redirect_url ) );
        exit;
    }

    if ( isset( $_POST['website'] ) && '' !== trim( sanitize_text_field( wp_unslash( $_POST['website'] ) ) ) ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'success', $redirect_url ) );
        exit;
    }

    $name = isset( $_POST['name'] )
        ? sanitize_text_field( wp_unslash( $_POST['name'] ) )
        : '';
    $phone = isset( $_POST['phone'] )
        ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )
        : '';
    $message = isset( $_POST['message'] )
        ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) )
        : '';

    $captcha_a = isset( $_POST['captcha_a'] ) ? absint( $_POST['captcha_a'] ) : 0;
    $captcha_b = isset( $_POST['captcha_b'] ) ? absint( $_POST['captcha_b'] ) : 0;
    $captcha_answer = isset( $_POST['captcha'] ) ? absint( $_POST['captcha'] ) : -1;

    if ( '' === $name || '' === $phone ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'required', $redirect_url ) );
        exit;
    }

    if (
        $captcha_a < 1 || $captcha_a > 10 ||
        $captcha_b < 1 || $captcha_b > 10 ||
        ( $captcha_a + $captcha_b ) !== $captcha_answer
    ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'captcha', $redirect_url ) );
        exit;
    }

    $name    = mb_substr( $name, 0, 100 );
    $phone   = mb_substr( $phone, 0, 20 );
    $message = mb_substr( $message, 0, 1000 );

    $ip = isset( $_SERVER['REMOTE_ADDR'] )
        ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
        : 'unknown';
    $rate_key = 'imidjstroy_contacts_' . md5( $ip );
    $rate = (int) get_transient( $rate_key );

    if ( $rate >= 3 ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'rate', $redirect_url ) );
        exit;
    }

    set_transient( $rate_key, $rate + 1, 10 * MINUTE_IN_SECONDS );

    $recipient = sanitize_email( imidjstroy_get_site_setting( 'form_recipient_email' ) );
    $subject   = sprintf( 'Заявка со страницы Контакты — %s', $name );

    $body  = "Новая заявка со страницы «Контакты»\n\n";
    $body .= "ФИО: {$name}\n";
    $body .= "Телефон: {$phone}\n";
    if ( '' !== $message ) {
        $body .= "Сообщение: {$message}\n";
    }
    $body .= "\nДата: " . wp_date( 'd.m.Y H:i' ) . "\n";
    $body .= 'Страница: ' . home_url( '/contacts/' ) . "\n";

    $sent = wp_mail(
        $recipient,
        $subject,
        $body,
        [ 'Content-Type: text/plain; charset=UTF-8' ]
    );

    wp_safe_redirect(
        add_query_arg( 'contact_status', $sent ? 'success' : 'error', $redirect_url )
    );
    exit;
}
add_action( 'admin_post_nopriv_imidjstroy_contacts_form', 'imidjstroy_handle_contacts_page_form' );
add_action( 'admin_post_imidjstroy_contacts_form', 'imidjstroy_handle_contacts_page_form' );
