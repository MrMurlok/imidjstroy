<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="checkout-page">
    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php get_footer(); ?>
