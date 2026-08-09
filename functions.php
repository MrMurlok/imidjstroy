function imidjstroy_assets() {

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
