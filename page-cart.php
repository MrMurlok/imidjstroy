<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="cart-page">
    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>

    <?php get_template_part( 'template-parts/components/popular-products' ); ?>
</main>

<?php get_footer(); ?>
