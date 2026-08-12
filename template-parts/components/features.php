<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$features = [
    [
        'icon'        => 'truck',
        'title'       => 'Быстрая доставка',
        'description' => 'Доставка по Владивостоку и области',
    ],
    [
        'icon'        => 'shield',
        'title'       => 'Гарантия качества',
        'description' => 'Только сертифицированные товары',
    ],
    [
        'icon'        => 'clock',
        'title'       => 'Удобное время',
        'description' => 'Работаем ежедневно с 11:00 до 19:00',
    ],
    [
        'icon'        => 'headphones',
        'title'       => 'Поддержка',
        'description' => 'Консультации по выбору материалов',
    ],
];
?>

<section class="home-features">
    <div class="container">
        <div class="home-features__grid">

            <?php foreach ( $features as $feature ) : ?>
                <article class="home-feature-card">

                    <div class="home-feature-card__icon" aria-hidden="true">

                        <?php if ( 'truck' === $feature['icon'] ) : ?>
                            <svg viewBox="0 0 24 24">
                                <path d="M10 17h4V5H2v12h3"></path>
                                <path d="M14 9h4l4 4v4h-3"></path>
                                <circle cx="7.5" cy="17.5" r="2.5"></circle>
                                <circle cx="16.5" cy="17.5" r="2.5"></circle>
                            </svg>

                        <?php elseif ( 'shield' === $feature['icon'] ) : ?>
                            <svg viewBox="0 0 24 24">
                                <path d="M20 13c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V5l8-3 8 3v8Z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>

                        <?php elseif ( 'clock' === $feature['icon'] ) : ?>
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v5l3 2"></path>
                            </svg>

                        <?php else : ?>
                            <svg viewBox="0 0 24 24">
                                <path d="M4 13a8 8 0 0 1 16 0"></path>
                                <path d="M18 19h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-1v7Z"></path>
                                <path d="M6 19H5a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1v7Z"></path>
                            </svg>
                        <?php endif; ?>

                    </div>

                    <div class="home-feature-card__content">
                        <h3 class="home-feature-card__title">
                            <?php echo esc_html( $feature['title'] ); ?>
                        </h3>

                        <p class="home-feature-card__description">
                            <?php echo esc_html( $feature['description'] ); ?>
                        </p>
                    </div>

                </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>
