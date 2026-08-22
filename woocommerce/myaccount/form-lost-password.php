<?php
/** Custom lost password form. */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>
<section class="imidjstroy-lost-password">
    <div class="imidjstroy-lost-password__card">
        <h1>Сброс пароля</h1>
        <p>Введите email или логин. Мы отправим ссылку для создания нового пароля.</p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password">
            <p class="form-row form-row-wide">
                <label for="user_login">Email или логин <span class="required">*</span></label>
                <input type="text" name="user_login" id="user_login" autocomplete="username" required>
            </p>

            <?php do_action( 'woocommerce_lostpassword_form' ); ?>
            <input type="hidden" name="wc_reset_password" value="true">
            <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

            <div class="imidjstroy-account__actions">
                <button type="submit" class="woocommerce-Button button" value="Отправить ссылку">Отправить ссылку</button>
                <a class="imidjstroy-account-button imidjstroy-account-button--outline" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Вернуться ко входу</a>
            </div>
        </form>
    </div>
</section>
<?php do_action( 'woocommerce_after_lost_password_form' ); ?>
