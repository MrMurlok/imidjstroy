<?php

defined( 'ABSPATH' ) || exit;

$current_term = get_query_var( 'catalog_type_term' );
$children     = get_query_var( 'catalog_type_children' );

if ( ! ( $current_term instanceof WP_Term ) ) {
    return;
}

if ( ! is_array( $children ) ) {
    $children = [];
}
?>

<section class="catalog-type">
    <div class="container">

        <div class="catalog-type__breadcrumb">
            <a
                href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                class="catalog-type__back"
            >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>

                <span>Каталог</span>
            </a>

            <span class="catalog-type__slash">/</span>

            <span class="catalog-type__current">
                <?php echo esc_html( $current_term->name ); ?>
            </span>
        </div>

        <h1 class="catalog-type__title">
            <?php echo esc_html( $current_term->name ); ?>
        </h1>

        <?php if ( ! empty( $children ) ) : ?>

            <div class="catalog-type__grid">

                <?php foreach ( $children as $category ) : ?>
                    <?php
                    $category_link = get_term_link( $category );

                    if ( is_wp_error( $category_link ) ) {
                        continue;
                    }

                    $thumbnail_id = absint(
                        get_term_meta( $category->term_id, 'thumbnail_id', true )
                    );

                    $image_url = $thumbnail_id
                        ? wp_get_attachment_image_url( $thumbnail_id, 'medium_large' )
                        : '';
                    ?>

                    <a
                        href="<?php echo esc_url( $category_link ); ?>"
                        class="catalog-type-card"
                    >
                        <?php if ( $image_url ) : ?>
                            <div
                                class="catalog-type-card__image"
                                style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
                                aria-hidden="true"
                            ></div>
                        <?php else : ?>
                            <div class="catalog-type-card__fallback" aria-hidden="true">
                                📦
                            </div>
                        <?php endif; ?>

                        <div class="catalog-type-card__gradient" aria-hidden="true"></div>

                        <div class="catalog-type-card__content">
                            <span class="catalog-type-card__name">
                                <?php echo esc_html( $category->name ); ?>
                            </span>

                            <span class="catalog-type-card__count">
                                <?php
                                echo esc_html(
                                    function_exists( 'imidjstroy_product_count_text' )
                                        ? imidjstroy_product_count_text( $category->count )
                                        : sprintf( '%d товаров', absint( $category->count ) )
                                );
                                ?>
                            </span>
                        </div>
                    </a>

                <?php endforeach; ?>

            </div>

        <?php else : ?>

            <p class="catalog-type__empty">
                Категории скоро появятся
            </p>

        <?php endif; ?>

    </div>
</section>
