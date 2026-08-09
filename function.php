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
        'imidjstroy-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get( 'Version' )
    );
}

add_action( 'wp_enqueue_scripts', 'imidjstroy_assets' );
