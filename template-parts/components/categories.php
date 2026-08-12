<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'imidjstroy_product_count_text' ) ) {
    function imidjstroy_product_count_text( $count ) {
        $count = absint( $count );
        $mod10 = $count % 10;
        $mod100 = $count % 100;

        if ( $mod10 === 1 && $mod100 !== 11 ) {
            $word = 'товар';
        } elseif ( $mod10 >= 2 && $mod10 <= 4 && ( $mod100 < 12 || $mod100 > 14 ) ) {
            $word = 'товара';
        } else {
            $word = 'товаров';
        }

        return sprintf( '%d %s', $count, $word );
    }
}

$categories = [];

if ( taxonomy_exists( 'product_cat' ) ) {
    $categories = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => 0,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ] );

    if ( is_wp_error( $categories ) ) {
        $categories = [];
    }
}
?>

<section class="home-categories">
    <div class="container">

        <div class="home-categories__heading">
            <h2 class="home-categories__title">Категории</h2>

            <a
                href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                class="home-categories__all"
            >
                Смотреть все

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>

        <?php if ( ! empty( $categories ) ) : ?>

            <div class="home-categories__carousel-wrap">

                <button
                    type="button"
                    class="home-categories__arrow home-categories__arrow--prev js-categories-prev"
                    aria-label="Предыдущие категории"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                </button>

                <div class="home-categories__viewport js-categories-viewport">
                    <div class="home-categories__track">

                        <?php foreach ( $categories as $category ) : ?>
                            <?php
                            $thumbnail_id = absint( get_term_meta( $category->term_id, 'thumbnail_id', true ) );
                            $image_url = $thumbnail_id
                                ? wp_get_attachment_image_url( $thumbnail_id, 'medium_large' )
                                : '';

                            $category_link = get_term_link( $category );

                            if ( is_wp_error( $category_link ) ) {
                                continue;
                            }
                            ?>

                            <div class="home-categories__item">
                                <a
                                    href="<?php echo esc_url( $category_link ); ?>"
                                    class="category-card"
                                >
                                    <?php if ( $image_url ) : ?>
                                        <div
                                            class="category-card__image"
                                            style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
                                            aria-hidden="true"
                                        ></div>
                                    <?php else : ?>
                                        <div class="category-card__fallback" aria-hidden="true">
                                            📦
                                        </div>
                                    <?php endif; ?>

                                    <div class="category-card__gradient" aria-hidden="true"></div>

                                    <div class="category-card__content">
                                        <span class="category-card__name">
                                            <?php echo esc_html( $category->name ); ?>
                                        </span>

                                        <span class="category-card__count">
                                            <?php echo esc_html( imidjstroy_product_count_text( $category->count ) ); ?>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <button
                    type="button"
                    class="home-categories__arrow home-categories__arrow--next js-categories-next"
                    aria-label="Следующие категории"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>

            </div>

        <?php else : ?>

            <div class="home-categories__empty">
                Категории WooCommerce пока не добавлены.
            </div>

        <?php endif; ?>

    </div>
</section>
