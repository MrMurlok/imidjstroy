<?php
/** Custom reset password form. */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form', $args );
?>
<section class="imidjstroy-lost-password">
    <div class="imidjstroy-lost-password__card">
        <h1>Новый пароль</h1>
        <p>Введите новый пароль дважды.</p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password">
            <p class="form-row form-row-wide">
                <label for="password_1">Новый пароль <span class="required">*</span></label>
                <span class="imidjstroy-password-field">
                    <input type="password" name="password_1" id="password_1" autocomplete="new-password" required>
                    <button type="button" class="imidjstroy-password-toggle" data-password-toggle="password_1" aria-label="Показать пароль"><svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path></svg></button>
                </span>
            </p>
            <p class="form-row form-row-wide">
                <label for="password_2">Повторите пароль <span class="required">*</span></label>
                <span class="imidjstroy-password-field">
                    <input type="password" name="password_2" id="password_2" autocomplete="new-password" required>
                    <button type="button" class="imidjstroy-password-toggle" data-password-toggle="password_2" aria-label="Показать пароль"><svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path></svg></button>
                </span>
            </p>

            <input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>">
            <input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>">
            <?php do_action( 'woocommerce_resetpassword_form' ); ?>
            <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

            <div class="imidjstroy-account__actions">
                <button type="submit" class="woocommerce-Button button" value="Сохранить пароль">Сохранить пароль</button>
            </div>
        </form>
    </div>
</section>
<?php do_action( 'woocommerce_after_reset_password_form', $args ); ?>
