<?php
/**
 * Imidjstroy WooCommerce login / registration.
 *
 * @package Imidjstroy
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>
<section class="imidjstroy-auth">
    <header class="imidjstroy-auth__header">
        <h1 class="imidjstroy-auth__title">Личный кабинет</h1>
        <p class="imidjstroy-auth__subtitle">Войдите в аккаунт или зарегистрируйтесь для просмотра заказов и сохранения данных.</p>
    </header>

    <div class="imidjstroy-auth__grid<?php echo $registration_enabled ? '' : ' imidjstroy-auth__grid--single'; ?>" id="customer_login">
        <section class="imidjstroy-auth-card">
            <span class="imidjstroy-auth-card__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><path d="m10 17 5-5-5-5"></path><path d="M15 12H3"></path></svg>
            </span>
            <h2>Вход</h2>
            <p class="imidjstroy-auth-card__lead">Войдите в существующий аккаунт.</p>

            <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <p class="form-row form-row-wide">
                    <label for="username">Email или логин <span class="required">*</span></label>
                    <input type="text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required>
                </p>

                <p class="form-row form-row-wide">
                    <label for="password">Пароль <span class="required">*</span></label>
                    <span class="imidjstroy-password-field">
                        <input type="password" name="password" id="password" autocomplete="current-password" required>
                        <button type="button" class="imidjstroy-password-toggle" data-password-toggle="password" aria-label="Показать пароль">
                            <svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path><path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18 18 0 0 1-2.1 3.1"></path><path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10 10 0 0 0 4.1-.9"></path></svg>
                        </button>
                    </span>
                </p>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <div class="imidjstroy-auth__meta">
                    <label class="imidjstroy-auth__remember">
                        <input name="rememberme" type="checkbox" value="forever">
                        <span>Запомнить меня</span>
                    </label>
                    <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>">Забыли пароль?</a>
                </div>

                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <div class="imidjstroy-auth__actions">
                    <button type="submit" class="woocommerce-button button" name="login" value="Войти">Войти</button>
                </div>

                <?php do_action( 'woocommerce_login_form_end' ); ?>
            </form>
        </section>

        <?php if ( $registration_enabled ) : ?>
            <section class="imidjstroy-auth-card">
                <span class="imidjstroy-auth-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M15 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8" cy="7" r="4"></circle><path d="M19 8v6"></path><path d="M22 11h-6"></path></svg>
                </span>
                <h2>Регистрация</h2>
                <p class="imidjstroy-auth-card__lead">Создайте аккаунт для удобного оформления заказов.</p>

                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <p class="form-row form-row-first">
                        <label for="reg_first_name">Имя <span class="required">*</span></label>
                        <input type="text" name="reg_first_name" id="reg_first_name" autocomplete="given-name" value="<?php echo ! empty( $_POST['reg_first_name'] ) ? esc_attr( wp_unslash( $_POST['reg_first_name'] ) ) : ''; ?>" required>
                    </p>
                    <p class="form-row form-row-last">
                        <label for="reg_last_name">Фамилия <span class="required">*</span></label>
                        <input type="text" name="reg_last_name" id="reg_last_name" autocomplete="family-name" value="<?php echo ! empty( $_POST['reg_last_name'] ) ? esc_attr( wp_unslash( $_POST['reg_last_name'] ) ) : ''; ?>" required>
                    </p>
                    <div class="clear"></div>

                    <p class="form-row form-row-wide">
                        <label for="reg_email">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="reg_email" autocomplete="email" value="<?php echo ! empty( $_POST['email'] ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required>
                    </p>

                    <p class="form-row form-row-wide">
                        <label for="reg_billing_phone">Телефон</label>
                        <input type="tel" name="reg_billing_phone" id="reg_billing_phone" autocomplete="tel" placeholder="+7 (999) 123-45-67" value="<?php echo ! empty( $_POST['reg_billing_phone'] ) ? esc_attr( wp_unslash( $_POST['reg_billing_phone'] ) ) : ''; ?>">
                    </p>

                    <p class="form-row form-row-wide">
                        <label for="reg_password">Пароль <span class="required">*</span></label>
                        <span class="imidjstroy-password-field">
                            <input type="password" name="password" id="reg_password" autocomplete="new-password" minlength="6" required>
                            <button type="button" class="imidjstroy-password-toggle" data-password-toggle="reg_password" aria-label="Показать пароль">
                                <svg class="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24"><path d="m3 3 18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path><path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18 18 0 0 1-2.1 3.1"></path><path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10 10 0 0 0 4.1-.9"></path></svg>
                            </button>
                        </span>
                    </p>

                    <?php do_action( 'woocommerce_register_form' ); ?>
                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

                    <div class="imidjstroy-auth__actions">
                        <button type="submit" class="woocommerce-Button woocommerce-button button" name="register" value="Зарегистрироваться">Зарегистрироваться</button>
                    </div>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>
                </form>
            </section>
        <?php endif; ?>
    </div>
</section>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
