<?php
/** Custom My Account shell. */
defined( 'ABSPATH' ) || exit;
?>
<section class="imidjstroy-account">
    <header class="imidjstroy-account__header">
        <h1 class="imidjstroy-account__title">Личный кабинет</h1>
        <p class="imidjstroy-account__subtitle">Управляйте профилем и просматривайте историю заказов.</p>
    </header>

    <div class="imidjstroy-account__layout">
        <?php do_action( 'woocommerce_account_navigation' ); ?>
        <main class="imidjstroy-account__content">
            <?php do_action( 'woocommerce_account_content' ); ?>
        </main>
    </div>
</section>
