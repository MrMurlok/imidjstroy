<?php
/** Custom account profile form. */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );

$user_id = get_current_user_id();
$phone   = get_user_meta( $user_id, 'billing_phone', true );
$address = get_user_meta( $user_id, 'billing_address_1', true );
?>
<section class="imidjstroy-account-section">
    <header class="imidjstroy-account-section__head">
        <h2>Профиль</h2>
        <p>Эти данные будут автоматически подставляться при оформлении заказа.</p>
    </header>

    <form class="woocommerce-EditAccountForm edit-account imidjstroy-account-form" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>
        <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

        <div class="imidjstroy-account-form__grid">
            <p class="form-row">
                <label for="account_first_name">Имя <span class="required">*</span></label>
                <input type="text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" required>
            </p>

            <p class="form-row">
                <label for="account_last_name">Фамилия <span class="required">*</span></label>
                <input type="text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" required>
            </p>

            <p class="form-row imidjstroy-account-form__wide">
                <label for="account_email_display">Email</label>
                <input type="email" id="account_email_display" value="<?php echo esc_attr( $user->user_email ); ?>" disabled>
                <input type="hidden" name="account_email" value="<?php echo esc_attr( $user->user_email ); ?>">
                <input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name ? $user->display_name : $user->user_login ); ?>">
            </p>

            <p class="form-row imidjstroy-account-form__wide">
                <label for="billing_phone">Телефон</label>
                <input type="tel" name="billing_phone" id="billing_phone" autocomplete="tel" placeholder="+7 (999) 123-45-67" value="<?php echo esc_attr( $phone ); ?>">
            </p>

            <p class="form-row imidjstroy-account-form__wide">
                <label for="billing_address_1">Адрес доставки</label>
                <input type="text" name="billing_address_1" id="billing_address_1" autocomplete="street-address" placeholder="г. Владивосток, улица, дом, квартира/офис" value="<?php echo esc_attr( $address ); ?>">
            </p>
        </div>

        <fieldset class="imidjstroy-account-form__passwords">
            <legend>Изменить пароль <span style="font-weight:400;color:hsl(var(--muted-foreground));font-size:.78rem;">(необязательно)</span></legend>

            <p class="form-row form-row-wide">
                <label for="password_current">Текущий пароль</label>
                <span class="imidjstroy-password-field">
                    <input type="password" name="password_current" id="password_current" autocomplete="current-password">
                    <button type="button" class="imidjstroy-password-toggle" data-password-toggle="password_current" aria-label="Показать пароль">
                        <svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path></svg>
                    </button>
                </span>
            </p>

            <div class="imidjstroy-account-form__grid">
                <p class="form-row">
                    <label for="password_1">Новый пароль</label>
                    <span class="imidjstroy-password-field">
                        <input type="password" name="password_1" id="password_1" autocomplete="new-password">
                        <button type="button" class="imidjstroy-password-toggle" data-password-toggle="password_1" aria-label="Показать пароль"><svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path></svg></button>
                    </span>
                </p>
                <p class="form-row">
                    <label for="password_2">Повторите новый пароль</label>
                    <span class="imidjstroy-password-field">
                        <input type="password" name="password_2" id="password_2" autocomplete="new-password">
                        <button type="button" class="imidjstroy-password-toggle" data-password-toggle="password_2" aria-label="Показать пароль"><svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path></svg></button>
                    </span>
                </p>
            </div>
        </fieldset>

        <?php do_action( 'woocommerce_edit_account_form' ); ?>
        <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
        <div class="imidjstroy-account__actions">
            <button type="submit" class="woocommerce-Button button" name="save_account_details" value="Сохранить">Сохранить изменения</button>
            <input type="hidden" name="action" value="save_account_details">
        </div>
        <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
    </form>
</section>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
