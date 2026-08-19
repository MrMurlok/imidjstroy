<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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

    $recipient = get_option( 'admin_email' );

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

