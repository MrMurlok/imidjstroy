<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$address            = imidjstroy_get_site_setting( 'address' );
$map_link           = imidjstroy_get_site_setting( 'map_open_url' );
$route_link         = imidjstroy_get_site_setting( 'map_route_url' );
$map_widget_url     = imidjstroy_map_widget_url();
$site_phone_1       = imidjstroy_get_site_setting( 'phone_1' );
$site_phone_2       = imidjstroy_get_site_setting( 'phone_2' );
$site_email         = imidjstroy_get_site_setting( 'public_email' );
$site_hours_footer  = imidjstroy_get_site_setting( 'hours_footer' );
$site_company_name  = imidjstroy_get_site_setting( 'company_name' );
$footer_description = imidjstroy_get_site_setting( 'footer_description' );
?>

</main>

<footer class="site-footer">
    <div class="container site-footer__main">
        <div class="site-footer__grid">

            <div class="site-footer__column">
                <div class="site-footer__brand">
                    <div class="site-footer__brand-mark">С</div>
                    <span class="site-footer__brand-name"><?php echo esc_html( $site_company_name ); ?></span>
                </div>

                <p class="site-footer__description">
                    <?php echo esc_html( $footer_description ); ?>
                </p>
            </div>

            <div class="site-footer__column">
                <h3 class="site-footer__title">Каталог</h3>

                <ul class="site-footer__links">
                    <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Каталог</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">О компании</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Блог</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Галерея</a></li>
                </ul>
            </div>

            <div class="site-footer__column">
                <h3 class="site-footer__title">О компании</h3>

                <ul class="site-footer__links">
                    <li><a href="<?php echo esc_url( home_url( '/installation/' ) ); ?>">Монтаж</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/partnership/' ) ); ?>">Сотрудничество</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contacts/' ) ); ?>">Контакты</a></li>
                </ul>
            </div>

            <div class="site-footer__column">
                <h3 class="site-footer__title">Контакты</h3>

                <ul class="site-footer__contacts">
                    <li>
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path>
                            </svg>
                        </span>

                        <div>
                            <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_1 ) ); ?>"><?php echo esc_html( $site_phone_1 ); ?></a>
                            <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_2 ) ); ?>"><?php echo esc_html( $site_phone_2 ); ?></a>
                        </div>
                    </li>

                    <li>
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="2.5"></circle>
                            </svg>
                        </span>

                        <button
                            type="button"
                            class="site-footer__map-button js-map-open"
                        >
                            <?php echo esc_html( $address ); ?>
                        </button>
                    </li>

                    <li>
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v5l3 2"></path>
                            </svg>
                        </span>

                        <span><?php echo esc_html( $site_hours_footer ); ?></span>
                    </li>

                    <li>
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="m3 7 9 6 9-6"></path>
                            </svg>
                        </span>

                        <a href="mailto:<?php echo esc_attr( antispambot( $site_email ) ); ?>"><?php echo esc_html( antispambot( $site_email ) ); ?></a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container site-footer__bottom-inner">
            <span>© 2026 ИмиджСтрой. Все права защищены.</span>

            <span>
                Developed by
                <a
                    href="https://khimichdev.ru"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="site-footer__developer"
                >
                    Khimich Aleksandr
                </a>
            </span>
        </div>
    </div>
</footer>

<div
    class="map-modal js-map-modal"
    aria-hidden="true"
>
    <button
        type="button"
        class="map-modal__backdrop js-map-close"
        aria-label="Закрыть карту"
    ></button>

    <div
        class="map-modal__panel"
        role="dialog"
        aria-modal="true"
        aria-labelledby="map-modal-title"
    >
        <div class="map-modal__shine"></div>

        <div class="map-modal__content">
            <div class="map-modal__header">
                <div>
                    <div class="map-modal__badge">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="2.5"></circle>
                        </svg>
                        Наш адрес
                    </div>

                    <h3 id="map-modal-title" class="map-modal__title">
                        Имидж Строй
                    </h3>

                    <p class="map-modal__address">
                        <?php echo esc_html( $address ); ?>
                    </p>
                </div>

                <button
                    type="button"
                    class="map-modal__close js-map-close"
                    aria-label="Закрыть"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 6l12 12M18 6 6 18"></path>
                    </svg>
                </button>
            </div>

            <div class="map-modal__map">
               <iframe
    src="<?php echo esc_url( $map_widget_url ); ?>"
    width="100%"
    height="320"
    frameborder="0"
    loading="lazy"
    title="Имидж Строй на карте"
    allowfullscreen
></iframe>
            </div>

            <div class="map-modal__actions">
                <a
                    href="<?php echo esc_url( $map_link ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="map-modal__button map-modal__button--primary"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 3h7v7"></path>
                        <path d="M10 14 21 3"></path>
                        <path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"></path>
                    </svg>
                    Открыть в 2ГИС
                </a>

                <a
                    href="<?php echo esc_url( $route_link ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="map-modal__button map-modal__button--secondary"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                    </svg>
                    Построить маршрут
                </a>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
