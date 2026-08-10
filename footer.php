<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$address = 'г. Владивосток, ул. Иртышская, 17А, стр. 4';
$map_link = 'https://2gis.ru/vladivostok/search/%D1%83%D0%BB.%20%D0%98%D1%80%D1%82%D1%8B%D1%88%D1%81%D0%BA%D0%B0%D1%8F%2C%2017%D0%90%2C%20%D1%81%D1%82%D1%80.%204/geo/3519072864048717/131.918675%2C43.162749?m=131.918715%2C43.162479%2F20';
?>

</main>

<footer class="site-footer">
    <div class="container site-footer__main">
        <div class="site-footer__grid">

            <div class="site-footer__column">
                <div class="site-footer__brand">
                    <div class="site-footer__brand-mark">С</div>
                    <span class="site-footer__brand-name">ИмиджСтрой</span>
                </div>

                <p class="site-footer__description">
                    Мы предлагаем широкий ассортимент качественных строительных материалов по конкурентным ценам.
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
                            <a href="tel:+79644492229">+7 (964) 449-22-29</a>
                            <a href="tel:+74232677715">+7 (423) 267-77-15</a>
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

                        <span>Ежедневно 09:00 до 22:00. Без выходных.</span>
                    </li>

                    <li>
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="m3 7 9 6 9-6"></path>
                            </svg>
                        </span>

                        <a href="mailto:mail@имидж-строй.рф">mail@имидж-строй.рф</a>
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
                    src="https://yandex.ru/map-widget/v1/?ll=131.918675%2C43.162749&z=17&pt=131.918675,43.162749,pm2gnm"
                    width="100%"
                    height="320"
                    frameborder="0"
                    loading="lazy"
                    title="Имидж Строй на карте"
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
                    href="<?php echo esc_url( $map_link ); ?>"
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
