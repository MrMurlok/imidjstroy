<?php

defined( 'ABSPATH' ) || exit;

$sections = [
    [
        'name'        => 'Стройматериалы',
        'slug'        => 'stroymaterialy',
        'icon'        => 'blocks',
        'description' => [
            [
                'highlight' => 'Широкий ассортимент',
                'text'      => 'строительных материалов',
            ],
            [
                'highlight' => '',
                'text'      => 'от базовых до специализированных.',
            ],
        ],
        'features'    => [
            'Стабильное качество для повседневных задач',
            'Оптимальный выбор для экономичного ремонта',
            'Материалы для уверенного результата',
            'Рациональное решение без переплат',
        ],
    ],
    [
        'name'        => 'Рекламные материалы',
        'slug'        => 'reklamnye-materialy',
        'icon'        => 'palette',
        'description' => [
            [
                'highlight' => 'Широкий ассортимент',
                'text'      => 'материалов для наружной и интерьерной рекламы, баннерные ткани, плёнки, пластик ПВХ, профили и комплектующие.',
            ],
        ],
        'features'    => [
            'Материалы для наружной и интерьерной рекламы',
            'Практичные решения для производства и монтажа',
            'Расходные материалы для печати',
        ],
    ],
];

foreach ( $sections as &$section ) {
    $term = get_term_by( 'name', $section['name'], 'product_cat' );

    if ( ! $term || is_wp_error( $term ) ) {
        $term = get_term_by( 'slug', $section['slug'], 'product_cat' );
    }

    $section['term']  = $term && ! is_wp_error( $term ) ? $term : null;
    $section['url']   = $section['term']
        ? get_term_link( $section['term'] )
        : wc_get_page_permalink( 'shop' );

    $thumbnail_id = $section['term']
        ? absint( get_term_meta( $section['term']->term_id, 'thumbnail_id', true ) )
        : 0;

    $section['image'] = $thumbnail_id
        ? wp_get_attachment_image_url( $thumbnail_id, 'large' )
        : '';
}
unset( $section );
?>

<section class="catalog-landing">
    <div class="container">

        <h1 class="catalog-landing__title">Каталог</h1>

        <div class="catalog-landing__sections">

            <?php foreach ( $sections as $section ) : ?>
                <a
                    href="<?php echo esc_url( $section['url'] ); ?>"
                    class="catalog-section-card"
                >
                    <div class="catalog-section-card__background">
                        <?php if ( $section['image'] ) : ?>
                            <img
                                src="<?php echo esc_url( $section['image'] ); ?>"
                                alt=""
                                loading="eager"
                            >
                        <?php else : ?>
                            <div class="catalog-section-card__placeholder"></div>
                        <?php endif; ?>
                    </div>

                    <div class="catalog-section-card__shimmer" aria-hidden="true"></div>

                    <div class="catalog-section-card__content">

                        <div class="catalog-section-card__icon">
                            <?php if ( 'blocks' === $section['icon'] ) : ?>
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                </svg>
                            <?php else : ?>
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="13.5" cy="6.5" r="2.5"></circle>
                                    <circle cx="17.5" cy="10.5" r="2.5"></circle>
                                    <circle cx="8.5" cy="7.5" r="2.5"></circle>
                                    <circle cx="6.5" cy="12.5" r="2.5"></circle>
                                    <path d="M12 22a10 10 0 1 1 10-10c0 3-1.5 4-3 4h-2.5a2.5 2.5 0 0 0-2.5 2.5V19a3 3 0 0 1-3 3h1Z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>

                        <h2 class="catalog-section-card__title">
                            <?php echo esc_html( $section['name'] ); ?>
                        </h2>

                        <div class="catalog-section-card__description">
                            <?php foreach ( $section['description'] as $paragraph ) : ?>
                                <p>
                                    <?php if ( $paragraph['highlight'] ) : ?>
                                        <span class="catalog-section-card__highlight">
                                            <?php echo esc_html( $paragraph['highlight'] ); ?>
                                        </span>
                                    <?php endif; ?>

                                    <span>
                                        <?php echo esc_html( $paragraph['text'] ); ?>
                                    </span>
                                </p>
                            <?php endforeach; ?>
                        </div>

                        <ul class="catalog-section-card__features">
                            <?php foreach ( $section['features'] as $feature ) : ?>
                                <li>
                                    <span></span>
                                    <?php echo esc_html( $feature ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="catalog-section-card__footer">
                        <span class="catalog-section-card__button">
                            <span>Перейти в каталог</span>

                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m13 6 6 6-6 6"></path>
                            </svg>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<?php
/*
 * Reuse the already ported original PopularProducts block.
 */
get_template_part( 'template-parts/components/popular-products' );
