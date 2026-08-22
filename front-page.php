<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$site_phone_1  = imidjstroy_get_site_setting( 'phone_1' );
$site_city      = imidjstroy_get_site_setting( 'city_short' );
?>

<section class="home-hero">
    <div
        class="home-hero__background"
        style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-bg.jpg' ); ?>');"
        aria-hidden="true"
    ></div>

    <div class="home-hero__overlay" aria-hidden="true"></div>

    <div class="container home-hero__container">
        <div class="home-hero__content">

            <h1 class="home-hero__title">
                Строительные материалы
                <span>оптом и в розницу</span>
            </h1>

            <p class="home-hero__text">
                Качественные стройматериалы по лучшим ценам во Владивостоке.
                Доставка и самовывоз.
            </p>

            <div class="home-hero__actions">
                <a
                    href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"
                    class="home-hero__button home-hero__button--primary"
                >
                    Смотреть каталог

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </a>

                <a
                    href="<?php echo esc_url( home_url( '/contacts/' ) ); ?>"
                    class="home-hero__button home-hero__button--outline"
                >
                    Связаться с нами
                </a>
            </div>

            <div class="home-hero__meta">
                <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_1 ) ); ?>" class="home-hero__meta-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path>
                    </svg>

                    <span><?php echo esc_html( $site_phone_1 ); ?></span>
                </a>

                <span class="home-hero__meta-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="2.5"></circle>
                    </svg>

                    <span><?php echo esc_html( $site_city ); ?></span>
                </span>
            </div>

        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/components/features' );
get_template_part( 'template-parts/components/categories' );
get_template_part( 'template-parts/components/building-materials' );
get_template_part( 'template-parts/components/ad-materials' );
get_template_part( 'template-parts/components/popular-products' );
?>

<div id="contact-form">
    <?php get_template_part( 'template-parts/components/contact-form' ); ?>
</div>

<?php
get_footer();
