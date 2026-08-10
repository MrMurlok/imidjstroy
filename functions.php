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
