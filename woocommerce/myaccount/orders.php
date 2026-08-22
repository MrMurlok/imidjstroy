<?php
/** Custom account orders list. */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );
?>
<section class="imidjstroy-account-section">
    <header class="imidjstroy-account-section__head">
        <h2>Заказы</h2>
        <p>История ваших заказов и их текущий статус.</p>
    </header>

    <?php if ( $has_orders ) : ?>
        <div class="imidjstroy-orders">
            <?php foreach ( $customer_orders->orders as $customer_order ) :
                $order = wc_get_order( $customer_order );
                if ( ! $order ) {
                    continue;
                }
                $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                $status     = $order->get_status();
                $actions    = wc_get_account_orders_actions( $order );
                $view_url   = isset( $actions['view']['url'] ) ? $actions['view']['url'] : $order->get_view_order_url();
                ?>
                <article class="imidjstroy-order-card">
                    <div class="imidjstroy-order-card__main">
                        <strong>Заказ №<?php echo esc_html( $order->get_order_number() ); ?></strong>
                        <span><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'd.m.Y' ) ); ?></span>
                    </div>
                    <div class="imidjstroy-order-card__metric">
                        <strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
                        <span>Товаров: <?php echo esc_html( $item_count ); ?></span>
                    </div>
                    <div>
                        <span class="imidjstroy-order-status imidjstroy-order-status--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( wc_get_order_status_name( $status ) ); ?></span>
                    </div>
                    <div class="imidjstroy-order-card__action">
                        <a class="imidjstroy-account-button imidjstroy-account-button--outline" href="<?php echo esc_url( $view_url ); ?>">Подробнее</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
            <nav class="imidjstroy-account-pagination" aria-label="Страницы заказов">
                <div>
                    <?php if ( 1 !== $current_page ) : ?>
                        <a class="imidjstroy-account-button imidjstroy-account-button--outline" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">← Назад</a>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                        <a class="imidjstroy-account-button imidjstroy-account-button--outline" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">Далее →</a>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>
    <?php else : ?>
        <div class="imidjstroy-account-empty">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8Z"></path><path d="M16 3v5h5"></path><path d="M8 13h8"></path><path d="M8 17h5"></path></svg>
            <h3>У вас пока нет заказов</h3>
            <p>Добавьте товары из каталога и оформите первый заказ.</p>
            <a class="imidjstroy-account-button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Перейти в каталог</a>
        </div>
    <?php endif; ?>
</section>
<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
