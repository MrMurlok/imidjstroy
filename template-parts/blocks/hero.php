<?php
defined( 'ABSPATH' ) || exit;
$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [
    'titleFirst'    => 'Строительные материалы',
    'titleAccent'   => 'оптом и в розницу',
    'text'          => 'Качественные стройматериалы по лучшим ценам во Владивостоке. Доставка и самовывоз.',
    'primaryText'   => 'Смотреть каталог',
    'primaryUrl'    => home_url( '/shop/' ),
    'secondaryText' => 'Связаться с нами',
    'secondaryUrl'  => home_url( '/contacts/' ),
    'backgroundUrl' => '',
] );
$phone = imidjstroy_get_site_setting( 'phone_1' );
$city  = imidjstroy_get_site_setting( 'city_short' );
$bg    = $args['backgroundUrl'] ? $args['backgroundUrl'] : get_template_directory_uri() . '/assets/images/hero-bg.jpg';
?>
<section class="home-hero">
    <div class="home-hero__background" style="background-image:url('<?php echo esc_url( $bg ); ?>');" aria-hidden="true"></div>
    <div class="home-hero__overlay" aria-hidden="true"></div>
    <div class="container home-hero__container"><div class="home-hero__content">
        <h1 class="home-hero__title"><?php echo esc_html( $args['titleFirst'] ); ?> <span><?php echo esc_html( $args['titleAccent'] ); ?></span></h1>
        <p class="home-hero__text"><?php echo esc_html( $args['text'] ); ?></p>
        <div class="home-hero__actions">
            <a href="<?php echo esc_url( $args['primaryUrl'] ); ?>" class="home-hero__button home-hero__button--primary"><?php echo esc_html( $args['primaryText'] ); ?><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path></svg></a>
            <a href="<?php echo esc_url( $args['secondaryUrl'] ); ?>" class="home-hero__button home-hero__button--outline"><?php echo esc_html( $args['secondaryText'] ); ?></a>
        </div>
        <div class="home-hero__meta">
            <a href="<?php echo esc_attr( imidjstroy_phone_href( $phone ) ); ?>" class="home-hero__meta-item"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path></svg><span><?php echo esc_html( $phone ); ?></span></a>
            <span class="home-hero__meta-item"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle></svg><span><?php echo esc_html( $city ); ?></span></span>
        </div>
    </div></div>
</section>
