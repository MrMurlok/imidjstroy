<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );

$cart_count = 0;

if ( function_exists( 'WC' ) && WC()->cart ) {
    $cart_count = WC()->cart->get_cart_contents_count();
}

$is_logged_in = is_user_logged_in();
?>

<div class="site-shell">

    <div class="topbar">
        <div class="container topbar__inner">

            <div class="topbar__left">

                <button
                    type="button"
                    class="topbar__item topbar__address js-map-open"
                >
                    <span class="topbar__icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="2.5"></circle>
                        </svg>
                    </span>

                    <span>
                        г. Владивосток, ул. Иртышская, 17А, стр. 4
                    </span>
                </button>

                <div class="topbar__item">
                    <span class="topbar__icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </span>

                    <span>
                        09:00 - 22:00 / БЕЗ ВЫХОДНЫХ
                    </span>
                </div>

            </div>

            <div class="topbar__right">

                <div class="topbar__socials">
                    <a
                        href="https://t.me/imidjstroy"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Telegram"
                        class="topbar__social"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21.5 3.5 3.8 10.3c-1.2.5-1.2 1.2-.2 1.5l4.5 1.4 1.7 5.2c.2.6.1.9.7.9.5 0 .7-.2 1-.5l2.2-2.1 4.6 3.4c.9.5 1.5.2 1.7-.8l3-14.1c.3-1.3-.5-1.9-1.5-1.5Z"></path>
                        </svg>
                    </a>

<a
    href="https://max.ru/username"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="MAX"
    class="topbar__social topbar__social--max"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 720 720"
        aria-hidden="true"
    >
        <path
            fill="currentColor"
            d="M350.4 9.6C141.8 20.5 4.1 184.1 12.8 390.4c3.8 90.3 40.1 168 48.7 253.7 2.2 22.2-4.2 49.6 21.4 59.3 31.5 11.9 79.8-8.1 106.2-26.4 9-6.1 17.6-13.2 24.2-22 27.3 18.1 53.2 35.6 85.7 43.4 143.1 34.3 299.9-44.2 369.6-170.3C799.6 291.2 622.5-4.6 350.4 9.6m-81 494.4c-11.3 8.8-22.2 20.8-34.7 27.7-18.1 9.7-23.7-.4-30.5-16.4-21.4-50.9-24-137.6-11.5-190.9 16.8-72.5 72.9-136.3 150-143.1 78-6.9 150.4 32.7 183.1 104.2 72.4 159.1-112.9 316.2-256.4 218.6Z"
        />
    </svg>
</a>
                    <span class="topbar__phone-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path>
                        </svg>
                    </span>
                </div>

                <div class="topbar__phones topbar__phones--mobile">
                    <a href="tel:+74232677715">
                        +7 (423) 267-77-15
                    </a>

                    <a href="tel:+79644492229">
                        +7 (964) 449-22-29
                    </a>
                </div>

                <div class="topbar__phones topbar__phones--desktop">
                    <span>+7 (423) 267-77-15</span>
                    <span class="topbar__separator">|</span>
                    <span>+7 (964) 449-22-29</span>
                </div>

                <a
                    href="mailto:mail@имидж-строй.рф"
                    class="topbar__item topbar__email"
                >
                    <span class="topbar__icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="m3 7 9 6 9-6"></path>
                        </svg>
                    </span>

                    <span>mail@имидж-строй.рф</span>
                </a>

            </div>

        </div>
    </div>


    <header class="site-header">

        <div class="container site-header__inner">

            <a
                href="<?php echo esc_url( home_url( '/' ) ); ?>"
                class="site-logo"
            >
                <span class="site-logo__mark">
                    С
                </span>

                <span class="site-logo__text">
                    <span class="site-logo__title">
                        ИмиджСтрой
                    </span>

                    <span class="site-logo__subtitle">
                        Стройматериалы
                    </span>
                </span>
            </a>


            <nav
                class="desktop-nav"
                aria-label="Главное меню"
            >
                <?php
                $current_url = $_SERVER['REQUEST_URI'] ?? '/';

                $nav_links = [
                    [
                        'label' => 'Главная',
                        'url'   => home_url( '/' ),
                        'path'  => '/',
                    ],
                    [
                        'label' => 'Каталог',
                        'url'   => home_url( '/shop/' ),
                        'path'  => '/shop/',
                    ],
                    [
                        'label' => 'О компании',
                        'url'   => home_url( '/about/' ),
                        'path'  => '/about/',
                    ],
                    [
                        'label' => 'Блог',
                        'url'   => home_url( '/blog/' ),
                        'path'  => '/blog/',
                    ],
                    [
                        'label' => 'Контакты',
                        'url'   => home_url( '/contacts/' ),
                        'path'  => '/contacts/',
                    ],
                ];

                foreach ( $nav_links as $link ) :

                    if ( '/' === $link['path'] ) {
                        $active = '/' === $current_url;
                    } else {
                        $active = str_starts_with(
                            $current_url,
                            $link['path']
                        );
                    }
                    ?>

                    <a
                        href="<?php echo esc_url( $link['url'] ); ?>"
                        class="desktop-nav__link <?php echo $active ? 'is-active' : ''; ?>"
                    >
                        <?php echo esc_html( $link['label'] ); ?>
                    </a>

                <?php endforeach; ?>
            </nav>


            <div class="site-header__actions">

                <a
                    href="<?php echo esc_url( $cart_url ); ?>"
                    class="header-icon-button header-cart"
                    aria-label="Корзина"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="19" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"></path>
                    </svg>

                    <?php if ( $cart_count > 0 ) : ?>
                        <span class="header-cart__count">
                            <?php echo esc_html( $cart_count ); ?>
                        </span>
                    <?php endif; ?>
                </a>


                <a
                    href="<?php echo esc_url( $account_url ); ?>"
                    class="account-button"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                    </svg>

                    <span>
                        <?php echo $is_logged_in ? 'Кабинет' : 'Войти'; ?>
                    </span>
                </a>


                <button
                    type="button"
                    class="header-icon-button mobile-menu-button js-mobile-menu-toggle"
                    aria-label="Открыть меню"
                    aria-expanded="false"
                >
                    <span class="mobile-menu-button__open">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 7h16M4 12h16M4 17h16"></path>
                        </svg>
                    </span>

                    <span class="mobile-menu-button__close">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6 6l12 12M18 6 6 18"></path>
                        </svg>
                    </span>
                </button>

            </div>

        </div>


        <nav class="mobile-nav js-mobile-nav">

            <?php foreach ( $nav_links as $link ) :

                if ( '/' === $link['path'] ) {
                    $active = '/' === $current_url;
                } else {
                    $active = str_starts_with(
                        $current_url,
                        $link['path']
                    );
                }
                ?>

                <a
                    href="<?php echo esc_url( $link['url'] ); ?>"
                    class="mobile-nav__link <?php echo $active ? 'is-active' : ''; ?>"
                >
                    <?php echo esc_html( $link['label'] ); ?>
                </a>

            <?php endforeach; ?>

            <div class="mobile-nav__account-wrap">
                <a
                    href="<?php echo esc_url( $account_url ); ?>"
                    class="account-button account-button--mobile"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                    </svg>

                    <?php echo $is_logged_in ? 'Кабинет' : 'Войти'; ?>
                </a>
            </div>

        </nav>

    </header>


    <main class="site-main">
