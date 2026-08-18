<?php
/**
 * Product detail page with dynamic characteristics.
 *
 * Characteristics are built from the current WooCommerce product,
 * so every category/product may have its own set of attributes.
 */

defined( 'ABSPATH' ) || exit;

$product = get_query_var( 'imidjstroy_single_product' );

if ( ! ( $product instanceof WC_Product ) ) {
    return;
}

$product_id  = $product->get_id();
$name        = $product->get_name();
$sku         = $product->get_sku();
$description = $product->get_description();
$image_id    = $product->get_image_id();
$image_url   = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : '';
$quantity    = $product->get_stock_quantity();
$available   = $product->is_in_stock() && ( null === $quantity || $quantity > 0 );

$badge = trim( (string) $product->get_meta( '_imidjstroy_badge', true ) );

if ( $product->is_on_sale() ) {
    $badge = 'Скидка';
}

/*
 * Unit:
 * 1) custom CSV field
 * 2) global WooCommerce attribute pa_unit
 * 3) default
 */
$unit = trim( (string) $product->get_meta( '_imidjstroy_unit', true ) );

if ( '' === $unit ) {
    $unit = trim( (string) $product->get_attribute( 'pa_unit' ) );
}

if ( '' === $unit ) {
    $unit = 'шт.';
}

/*
 * DYNAMIC PRODUCT CHARACTERISTICS
 *
 * No hardcoded "Series / Color / Width / ..." list.
 * We show exactly the WooCommerce attributes assigned to THIS product.
 *
 * Examples:
 * Film:
 *   Color, Width, Length, Adhesive, Manufacturer
 *
 * Cement:
 *   Weight, Brand, Strength grade, Package
 *
 * Screw:
 *   Diameter, Length, Material, Head type
 *
 * Each product can therefore have a completely different table.
 */
$characteristics = [];

foreach ( $product->get_attributes() as $attribute ) {
    if ( ! $attribute->get_visible() ) {
        continue;
    }

    $attribute_name = $attribute->get_name();

    /*
     * Unit is shown separately below, so don't duplicate it
     * if it is stored as a WooCommerce attribute.
     */
    if ( in_array( $attribute_name, [ 'pa_unit', 'unit' ], true ) ) {
        continue;
    }

    if ( $attribute->is_taxonomy() ) {
        $label = wc_attribute_label( $attribute_name, $product );

        $values = wc_get_product_terms(
            $product_id,
            $attribute_name,
            [ 'fields' => 'names' ]
        );
    } else {
        $label  = wc_attribute_label( $attribute_name, $product );
        $values = $attribute->get_options();
    }

    $values = array_values(
        array_filter(
            array_map(
                static function ( $value ) {
                    return trim( wp_strip_all_tags( (string) $value ) );
                },
                is_array( $values ) ? $values : []
            )
        )
    );

    if ( empty( $values ) ) {
        continue;
    }

    $characteristics[] = [
        'label' => $label,
        'value' => implode( ', ', $values ),
    ];
}

/*
 * Optional custom characteristics imported from CSV.
 *
 * Meta format:
 * _imidjstroy_characteristics
 *
 * It may be:
 * 1. PHP array:
 *    [
 *      ['label' => 'Плотность', 'value' => '120 г/м²'],
 *      ['label' => 'Основа', 'value' => 'ПВХ']
 *    ]
 *
 * 2. Associative array:
 *    [
 *      'Плотность' => '120 г/м²',
 *      'Основа' => 'ПВХ'
 *    ]
 *
 * This is useful if some supplier CSV fields should not become
 * global WooCommerce attributes.
 */
$custom_characteristics = $product->get_meta( '_imidjstroy_characteristics', true );

if ( is_array( $custom_characteristics ) ) {
    foreach ( $custom_characteristics as $key => $item ) {
        $label = '';
        $value = '';

        if ( is_array( $item ) ) {
            $label = isset( $item['label'] ) ? trim( (string) $item['label'] ) : '';
            $value = isset( $item['value'] ) ? trim( (string) $item['value'] ) : '';
        } elseif ( ! is_int( $key ) ) {
            $label = trim( (string) $key );
            $value = trim( (string) $item );
        }

        if ( '' === $label || '' === $value ) {
            continue;
        }

        $characteristics[] = [
            'label' => $label,
            'value' => $value,
        ];
    }
}

/*
 * Unit remains a characteristic, but only once.
 */
if ( '' !== $unit ) {
    $characteristics[] = [
        'label' => 'Единица измерения',
        'value' => $unit,
    ];
}

/*
 * Deepest assigned product category.
 */
$terms = get_the_terms( $product_id, 'product_cat' );
$category = null;

if ( $terms && ! is_wp_error( $terms ) ) {
    usort(
        $terms,
        static function ( $a, $b ) {
            $a_depth = count( get_ancestors( $a->term_id, 'product_cat', 'taxonomy' ) );
            $b_depth = count( get_ancestors( $b->term_id, 'product_cat', 'taxonomy' ) );

            return $b_depth <=> $a_depth;
        }
    );

    $category = $terms[0];
}

$category_link = $category ? get_term_link( $category ) : '';

if ( is_wp_error( $category_link ) ) {
    $category_link = '';
}
?>

<section class="product-page">
    <div class="container">

        <button
            type="button"
            class="product-page__back js-product-back"
            data-fallback="<?php echo esc_url( $category_link ?: wc_get_page_permalink( 'shop' ) ); ?>"
        >
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>

            Назад
        </button>

        <div class="product-page__grid">

            <div class="product-page__media">

                <?php if ( '' !== $badge && 'none' !== mb_strtolower( $badge ) ) : ?>
                    <span class="product-page__badge <?php echo 'Скидка' === $badge ? 'product-page__badge--sale' : ''; ?>">
                        <?php echo esc_html( $badge ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( ! $available ) : ?>
                    <span class="product-page__badge product-page__badge--stock">
                        Нет в наличии
                    </span>
                <?php endif; ?>

                <?php if ( $image_url ) : ?>
                    <img
                        src="<?php echo esc_url( $image_url ); ?>"
                        alt="<?php echo esc_attr( $name ); ?>"
                        class="product-page__image"
                    >
                <?php else : ?>
                    <div class="product-page__placeholder">
                        Фото товара
                    </div>
                <?php endif; ?>

            </div>

            <div class="product-page__info">

                <?php if ( $category instanceof WP_Term ) : ?>
                    <?php if ( $category_link ) : ?>
                        <a href="<?php echo esc_url( $category_link ); ?>" class="product-page__category">
                            <?php echo esc_html( $category->name ); ?>
                        </a>
                    <?php else : ?>
                        <span class="product-page__category">
                            <?php echo esc_html( $category->name ); ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>

                <h1 class="product-page__title">
                    <?php echo esc_html( $name ); ?>
                </h1>

                <?php if ( $sku ) : ?>
                    <p class="product-page__sku">
                        Артикул: <?php echo esc_html( $sku ); ?>
                    </p>
                <?php endif; ?>

                <div class="product-page__price-row">
                    <div class="product-page__price">
                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                    </div>

                    <span class="product-page__unit">
                        / <?php echo esc_html( $unit ); ?>
                    </span>
                </div>

                <?php if ( null !== $quantity ) : ?>
                    <p class="product-page__stock <?php echo $available ? 'is-available' : 'is-unavailable'; ?>">
                        <?php if ( $available ) : ?>
                            В наличии:
                            <?php echo esc_html( $quantity ); ?>
                            <?php echo esc_html( $unit ); ?>
                        <?php else : ?>
                            Нет в наличии
                        <?php endif; ?>
                    </p>
                <?php elseif ( ! $available ) : ?>
                    <p class="product-page__stock is-unavailable">
                        Нет в наличии
                    </p>
                <?php endif; ?>

                <div class="product-page__actions">

                    <?php if ( $product->is_type( 'simple' ) && $available && $product->is_purchasable() ) : ?>

                        <form
                            class="cart product-page__cart-form"
                            action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
                            method="post"
                            enctype="multipart/form-data"
                        >
                            <button
                                type="submit"
                                name="add-to-cart"
                                value="<?php echo esc_attr( $product_id ); ?>"
                                class="product-page__cart-button"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="8" cy="21" r="1"></circle>
                                    <circle cx="19" cy="21" r="1"></circle>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h7.78a2 2 0 0 0 2-1.58L20.12 6H5.12"></path>
                                </svg>

                                В корзину
                            </button>
                        </form>

                    <?php elseif ( $product->is_type( 'variable' ) ) : ?>

                        <div class="product-page__native-cart">
                            <?php woocommerce_variable_add_to_cart(); ?>
                        </div>

                    <?php else : ?>

                        <button type="button" class="product-page__cart-button" disabled>
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h7.78a2 2 0 0 0 2-1.58L20.12 6H5.12"></path>
                            </svg>

                            В корзину
                        </button>

                    <?php endif; ?>

                </div>

                <?php if ( $description ) : ?>

                    <div class="product-page__section">
                        <h2>Описание</h2>

                        <div class="product-page__description">
                            <?php echo wp_kses_post( wpautop( $description ) ); ?>
                        </div>
                    </div>

                <?php endif; ?>

                <?php if ( ! empty( $characteristics ) ) : ?>

                    <div class="product-page__section">
                        <h2>Характеристики</h2>

                        <dl class="product-page__characteristics">

                            <?php foreach ( $characteristics as $characteristic ) : ?>

                                <div class="product-page__characteristic">
                                    <dt>
                                        <?php echo esc_html( $characteristic['label'] ); ?>
                                    </dt>

                                    <dd>
                                        <?php echo esc_html( $characteristic['value'] ); ?>
                                    </dd>
                                </div>

                            <?php endforeach; ?>

                        </dl>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>
</section>
