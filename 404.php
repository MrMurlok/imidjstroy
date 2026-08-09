<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="container">
    <h1>404</h1>
    <h2>Страница не найдена</h2>

    <p>
        Возможно, страница была удалена или её адрес изменился.
    </p>

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        Вернуться на главную
    </a>
</div>

<?php get_footer(); ?>
