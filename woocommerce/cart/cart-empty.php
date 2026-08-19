<?php
/**
 * Imidjstroy Empty Cart.
 *
 * @package Imidjstroy
 */

defined( 'ABSPATH' ) || exit;

?>

<section class="imidjstroy-cart imidjstroy-cart--empty">
    <div class="container imidjstroy-cart__container">

        <header class="imidjstroy-cart__header">
            <h1 class="imidjstroy-cart__title">Корзина</h1>
            <p class="imidjstroy-cart__subtitle">Товаров в корзине: 0</p>
        </header>

        <div class="imidjstroy-empty-cart">
            <div class="imidjstroy-empty-cart__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <circle cx="9" cy="20" r="1"></circle>
                    <circle cx="19" cy="20" r="1"></circle>
                    <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"></path>
                </svg>
            </div>

            <h2>Корзина пуста</h2>
            <p>Добавьте товары в корзину, чтобы оформить заказ.</p>

            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="imidjstroy-empty-cart__button">
                Перейти в каталог
            </a>
        </div>

    </div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
