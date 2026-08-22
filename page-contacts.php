<?php
/**
 * Contacts page — /contacts/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$captcha_a = wp_rand( 1, 10 );
$captcha_b = wp_rand( 1, 10 );
$status    = isset( $_GET['contact_status'] ) ? sanitize_key( wp_unslash( $_GET['contact_status'] ) ) : '';

$site_phone_1    = imidjstroy_get_site_setting( 'phone_1' );
$site_phone_2    = imidjstroy_get_site_setting( 'phone_2' );
$site_address    = imidjstroy_get_site_setting( 'address' );
$site_hours      = imidjstroy_get_site_setting( 'hours_contacts' );
$site_map_widget = imidjstroy_map_widget_url();

$status_messages = [
    'success'  => [ 'type' => 'success', 'text' => 'Заявка отправлена. Мы свяжемся с вами в ближайшее время.' ],
    'required' => [ 'type' => 'error', 'text' => 'Заполните имя и телефон.' ],
    'captcha'  => [ 'type' => 'error', 'text' => 'Неверный ответ проверки. Попробуйте ещё раз.' ],
    'rate'     => [ 'type' => 'error', 'text' => 'Слишком много отправок. Попробуйте ещё раз через несколько минут.' ],
    'error'    => [ 'type' => 'error', 'text' => 'Не удалось отправить заявку. Попробуйте ещё раз.' ],
];
?>

<main class="contacts-page">
    <?php while ( have_posts() ) : the_post(); ?>
        <section class="contacts-main">
            <div class="container">
                <header class="contacts-main__header">
                    <h1><?php the_title(); ?></h1>
                    <p>Свяжитесь с нами удобным для вас способом</p>
                </header>

                <?php if ( isset( $status_messages[ $status ] ) ) : ?>
                    <div class="contact-status contact-status--<?php echo esc_attr( $status_messages[ $status ]['type'] ); ?>" role="status">
                        <?php echo esc_html( $status_messages[ $status ]['text'] ); ?>
                    </div>
                <?php endif; ?>

                <div class="contacts-main__grid">
                    <div class="contacts-info">
                        <div class="contacts-info__top">
                            <article class="contact-info-card">
                                <span class="contact-info-card__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"></path></svg>
                                </span>
                                <h2>Телефон</h2>
                                <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_1 ) ); ?>"><?php echo esc_html( $site_phone_1 ); ?></a>
                                <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_2 ) ); ?>"><?php echo esc_html( $site_phone_2 ); ?></a>
                            </article>

                            <article class="contact-info-card">
                                <span class="contact-info-card__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
                                </span>
                                <h2>Часы работы</h2>
                                <p><?php echo nl2br( esc_html( $site_hours ) ); ?></p>
                            </article>
                        </div>

                        <article class="contact-info-card contact-info-card--wide js-map-open" tabindex="0" role="button" aria-label="Открыть карту">
                            <span class="contact-info-card__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle></svg>
                            </span>
                            <h2>Адрес</h2>
                            <p><?php echo esc_html( $site_address ); ?></p>
                        </article>

                        <div class="contacts-map">
                            <iframe
                                src="<?php echo esc_url( $site_map_widget ); ?>"
                                width="100%"
                                height="100%"
                                frameborder="0"
                                loading="lazy"
                                title="Имидж Строй на карте 2ГИС"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </div>

                    <section class="contacts-form-card" id="contact-form">
                        <h2>Оставить заявку</h2>

                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="contacts-form">
                            <input type="hidden" name="action" value="imidjstroy_contacts_form">
                            <?php wp_nonce_field( 'imidjstroy_contacts_form', 'imidjstroy_contacts_nonce' ); ?>

                            <div class="contacts-form__honeypot" aria-hidden="true">
                                <label>Ваш сайт <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                            </div>

                            <div class="contacts-form__grid">
                                <p>
                                    <label for="contact-name">ФИО <span>*</span></label>
                                    <input id="contact-name" name="name" type="text" maxlength="100" placeholder="ФИО" required>
                                </p>
                                <p>
                                    <label for="contact-phone">Телефон <span>*</span></label>
                                    <input id="contact-phone" name="phone" type="tel" maxlength="20" placeholder="+7" required>
                                </p>
                            </div>

                            <p>
                                <label for="contact-message">Сообщение</label>
                                <textarea id="contact-message" name="message" maxlength="1000" placeholder="Сообщение"></textarea>
                            </p>

                            <div class="contacts-captcha">
                                <label for="contact-captcha">Проверка <span>*</span></label>
                                <div class="contacts-captcha__row">
                                    <strong class="contacts-captcha__question" data-captcha-question><?php echo esc_html( $captcha_a . ' + ' . $captcha_b ); ?></strong>
                                    <input type="hidden" name="captcha_a" value="<?php echo esc_attr( $captcha_a ); ?>" data-captcha-a>
                                    <input type="hidden" name="captcha_b" value="<?php echo esc_attr( $captcha_b ); ?>" data-captcha-b>
                                    <input id="contact-captcha" name="captcha" type="number" inputmode="numeric" min="0" max="20" placeholder="Ответ" required>
                                    <button type="button" class="contacts-captcha__refresh" data-captcha-refresh aria-label="Обновить проверку">
                                        <svg viewBox="0 0 24 24"><path d="M20 7v5h-5"></path><path d="M4 17v-5h5"></path><path d="M6.1 9A7 7 0 0 1 18.4 6.4L20 12"></path><path d="M4 12l1.6 5.6A7 7 0 0 0 17.9 15"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="contacts-form__submit">
                                <svg viewBox="0 0 24 24"><path d="m22 2-7 20-4-9-9-4Z"></path><path d="M22 2 11 13"></path></svg>
                                Отправить
                            </button>
                        </form>
                    </section>
                </div>

                <?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
                    <div class="contacts-page__editor-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
