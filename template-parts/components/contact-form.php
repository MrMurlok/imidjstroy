<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [ 'title' => 'Оставить заявку', 'lead' => 'Оставьте заявку и мы свяжемся с вами в ближайшее время' ] );

$status = isset( $_GET['contact_status'] )
    ? sanitize_key( wp_unslash( $_GET['contact_status'] ) )
    : '';

$site_phone_1 = imidjstroy_get_site_setting( 'phone_1' );
$site_phone_2 = imidjstroy_get_site_setting( 'phone_2' );
$site_address = imidjstroy_get_site_setting( 'address' );
?>

<section class="home-contact">
    <div class="container">
        <div class="home-contact__grid">

            <div class="home-contact__info">
                <h2 class="home-contact__title"><?php echo esc_html( $args['title'] ); ?></h2>

                <p class="home-contact__lead">
                    <?php echo esc_html( $args['lead'] ); ?>
                </p>

                <ul class="home-contact__contacts">
                    <li>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path>
                        </svg>

                        <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_1 ) ); ?>">
                            <?php echo esc_html( $site_phone_1 ); ?>
                        </a>
                    </li>

                    <li>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path>
                        </svg>

                        <a href="<?php echo esc_attr( imidjstroy_phone_href( $site_phone_2 ) ); ?>">
                            <?php echo esc_html( $site_phone_2 ); ?>
                        </a>
                    </li>

                    <li>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="2.5"></circle>
                        </svg>

                        <span>
                            <?php echo esc_html( $site_address ); ?>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="home-contact__form-wrap">

                <?php if ( 'success' === $status ) : ?>
                    <div class="home-contact__notice home-contact__notice--success" role="status">
                        Заявка отправлена! Мы свяжемся с вами в ближайшее время.
                    </div>
                <?php elseif ( 'error' === $status ) : ?>
                    <div class="home-contact__notice home-contact__notice--error" role="alert">
                        Ошибка отправки. Попробуйте позже.
                    </div>
                <?php elseif ( 'required' === $status ) : ?>
                    <div class="home-contact__notice home-contact__notice--error" role="alert">
                        Заполните обязательные поля.
                    </div>
                <?php endif; ?>

                <form
                    action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                    method="post"
                    class="home-contact__form js-contact-form"
                >
                    <input type="hidden" name="action" value="imidjstroy_contact_form">

                    <?php wp_nonce_field( 'imidjstroy_contact_form', 'imidjstroy_contact_nonce' ); ?>

                    <div class="home-contact__honeypot" aria-hidden="true">
                        <label>
                            Ваш сайт
                            <input
                                type="text"
                                name="website"
                                tabindex="-1"
                                autocomplete="off"
                            >
                        </label>
                    </div>

                    <div class="home-contact__row">

                        <div class="home-contact__field">
                            <label for="contact-name">ФИО *</label>

                            <input
                                id="contact-name"
                                type="text"
                                name="name"
                                placeholder="ФИО"
                                autocomplete="name"
                                required
                            >
                        </div>

                        <div class="home-contact__field">
                            <label for="contact-phone">Телефон *</label>

                            <input
                                id="contact-phone"
                                type="tel"
                                name="phone"
                                placeholder="+7"
                                autocomplete="tel"
                                required
                            >
                        </div>

                    </div>

                    <div class="home-contact__field">
                        <label for="contact-message">Сообщение</label>

                        <textarea
                            id="contact-message"
                            name="message"
                            placeholder="Сообщение"
                            rows="4"
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="home-contact__submit js-contact-submit"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                            <path d="M22 2 11 13"></path>
                        </svg>

                        <span class="js-contact-submit-text">
                            Отправить
                        </span>
                    </button>

                </form>
            </div>

        </div>
    </div>
</section>
