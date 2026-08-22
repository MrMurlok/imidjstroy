<?php
/**
 * Global editable site settings for Imidjstroy.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function imidjstroy_site_settings_defaults() {
    return [
        'company_name'          => 'ИмиджСтрой',
        'company_tagline'       => 'Стройматериалы',
        'phone_1'               => '+7 (964) 449-22-29',
        'phone_2'               => '+7 (423) 267-77-15',
        'public_email'          => 'mail@имидж-строй.рф',
        'form_recipient_email'  => (string) get_option( 'admin_email' ),
        'address'               => 'г. Владивосток, ул. Иртышская, 17А, стр. 4',
        'city_short'            => 'Владивосток',
        'hours_topbar'          => '09:00 - 22:00 / БЕЗ ВЫХОДНЫХ',
        'hours_feature'         => 'Работаем ежедневно с 11:00 до 19:00',
        'hours_contacts'        => "Пн–Пт: 11:00–19:00\nСб, Вс — выходной",
        'hours_footer'          => 'Ежедневно 09:00 до 22:00. Без выходных.',
        'telegram_url'          => 'https://t.me/imidjstroy',
        'max_url'               => 'https://max.ru/username',
        'map_open_url'          => 'https://go.2gis.com/IlQJm',
        'map_route_url'         => 'https://2gis.ru/vladivostok/directions/points/%7C131.918791%2C43.162781%3B70000001079958540',
        'map_org_id'            => '70000001079958540',
        'map_lat'               => '43.162781',
        'map_lon'               => '131.918791',
        'footer_description'    => 'Мы предлагаем широкий ассортимент качественных строительных материалов по конкурентным ценам.',
    ];
}

function imidjstroy_get_site_settings() {
    $saved = get_option( 'imidjstroy_site_settings', [] );
    return wp_parse_args( is_array( $saved ) ? $saved : [], imidjstroy_site_settings_defaults() );
}

function imidjstroy_get_site_setting( $key ) {
    $settings = imidjstroy_get_site_settings();
    return isset( $settings[ $key ] ) ? $settings[ $key ] : '';
}

function imidjstroy_phone_href( $phone ) {
    $phone = (string) $phone;
    $plus  = 0 === strpos( ltrim( $phone ), '+' ) ? '+' : '';
    $digits = preg_replace( '/\D+/', '', $phone );
    return 'tel:' . $plus . $digits;
}

function imidjstroy_map_widget_url() {
    $lat = (float) imidjstroy_get_site_setting( 'map_lat' );
    $lon = (float) imidjstroy_get_site_setting( 'map_lon' );
    $org = (string) imidjstroy_get_site_setting( 'map_org_id' );

    $options = [
        'pos' => [
            'lat'  => $lat,
            'lon'  => $lon,
            'zoom' => 17,
        ],
        'opt' => [
            'city' => 'vladivostok',
        ],
        'org' => $org,
    ];

    return 'https://widgets.2gis.com/widget?type=firmsonmap&options=' . rawurlencode( wp_json_encode( $options, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
}

function imidjstroy_sanitize_site_settings( $input ) {
    $defaults = imidjstroy_site_settings_defaults();
    $input    = is_array( $input ) ? $input : [];
    $output   = [];

    $text_fields = [
        'company_name', 'company_tagline', 'phone_1', 'phone_2', 'address', 'city_short',
        'hours_topbar', 'hours_feature', 'hours_footer', 'map_org_id', 'map_lat', 'map_lon',
    ];

    foreach ( $text_fields as $key ) {
        $output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : $defaults[ $key ];
    }

    $output['hours_contacts']     = isset( $input['hours_contacts'] ) ? sanitize_textarea_field( $input['hours_contacts'] ) : $defaults['hours_contacts'];
    $output['footer_description'] = isset( $input['footer_description'] ) ? sanitize_textarea_field( $input['footer_description'] ) : $defaults['footer_description'];

    foreach ( [ 'public_email', 'form_recipient_email' ] as $key ) {
        $value = isset( $input[ $key ] ) ? sanitize_email( $input[ $key ] ) : '';
        $output[ $key ] = $value ? $value : $defaults[ $key ];
    }

    foreach ( [ 'telegram_url', 'max_url', 'map_open_url', 'map_route_url' ] as $key ) {
        $output[ $key ] = isset( $input[ $key ] ) ? esc_url_raw( $input[ $key ] ) : $defaults[ $key ];
    }

    return $output;
}

function imidjstroy_register_site_settings() {
    register_setting(
        'imidjstroy_site_settings_group',
        'imidjstroy_site_settings',
        [
            'type'              => 'array',
            'sanitize_callback' => 'imidjstroy_sanitize_site_settings',
            'default'           => imidjstroy_site_settings_defaults(),
        ]
    );
}
add_action( 'admin_init', 'imidjstroy_register_site_settings' );

function imidjstroy_add_site_settings_menu() {
    add_menu_page(
        'Имидж Строй — настройки сайта',
        'Имидж Строй',
        'manage_options',
        'imidjstroy-settings',
        'imidjstroy_render_site_settings_page',
        'dashicons-admin-home',
        58
    );
}
add_action( 'admin_menu', 'imidjstroy_add_site_settings_menu' );

function imidjstroy_settings_field( $settings, $key, $label, $type = 'text', $description = '' ) {
    $value = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
    ?>
    <div class="imidjstroy-admin-field">
        <label for="imidjstroy-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
        <?php if ( 'textarea' === $type ) : ?>
            <textarea id="imidjstroy-<?php echo esc_attr( $key ); ?>" name="imidjstroy_site_settings[<?php echo esc_attr( $key ); ?>]" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
        <?php else : ?>
            <input id="imidjstroy-<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $type ); ?>" name="imidjstroy_site_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
        <?php endif; ?>
        <?php if ( $description ) : ?>
            <p><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function imidjstroy_render_site_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $settings = imidjstroy_get_site_settings();
    ?>
    <div class="wrap imidjstroy-admin-settings">
        <h1>Имидж Строй — настройки сайта</h1>
        <p class="imidjstroy-admin-lead">Глобальные данные меняются здесь один раз и автоматически обновляются в шапке, подвале, на главной и странице контактов.</p>

        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'imidjstroy_site_settings_group' ); ?>

            <div class="imidjstroy-admin-grid">
                <section class="imidjstroy-admin-card">
                    <h2>Компания</h2>
                    <?php imidjstroy_settings_field( $settings, 'company_name', 'Название компании' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'company_tagline', 'Подпись под логотипом' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'footer_description', 'Описание в подвале', 'textarea' ); ?>
                </section>

                <section class="imidjstroy-admin-card">
                    <h2>Контакты</h2>
                    <?php imidjstroy_settings_field( $settings, 'phone_1', 'Телефон 1' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'phone_2', 'Телефон 2' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'public_email', 'Публичный email', 'email' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'form_recipient_email', 'Email для заявок', 'email', 'На этот адрес будут отправляться формы с сайта после настройки SMTP.' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'address', 'Адрес' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'city_short', 'Город — короткая подпись' ); ?>
                </section>

                <section class="imidjstroy-admin-card">
                    <h2>Часы работы</h2>
                    <?php imidjstroy_settings_field( $settings, 'hours_topbar', 'Верхняя панель' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'hours_feature', 'Карточка преимущества на главной' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'hours_contacts', 'Страница контактов', 'textarea', 'Можно вводить несколько строк.' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'hours_footer', 'Подвал' ); ?>
                </section>

                <section class="imidjstroy-admin-card">
                    <h2>Социальные сети</h2>
                    <?php imidjstroy_settings_field( $settings, 'telegram_url', 'Telegram', 'url' ); ?>
                    <?php imidjstroy_settings_field( $settings, 'max_url', 'MAX', 'url' ); ?>
                </section>

                <section class="imidjstroy-admin-card imidjstroy-admin-card--wide">
                    <h2>2ГИС</h2>
                    <div class="imidjstroy-admin-columns">
                        <?php imidjstroy_settings_field( $settings, 'map_open_url', 'Ссылка «Открыть в 2ГИС»', 'url' ); ?>
                        <?php imidjstroy_settings_field( $settings, 'map_route_url', 'Ссылка «Построить маршрут»', 'url' ); ?>
                        <?php imidjstroy_settings_field( $settings, 'map_org_id', 'ID организации 2ГИС', 'text', 'Для встроенной карты.' ); ?>
                        <?php imidjstroy_settings_field( $settings, 'map_lat', 'Широта' ); ?>
                        <?php imidjstroy_settings_field( $settings, 'map_lon', 'Долгота' ); ?>
                    </div>
                </section>
            </div>

            <?php submit_button( 'Сохранить настройки', 'primary large' ); ?>
        </form>
    </div>

    <style>
        .imidjstroy-admin-settings{max-width:1180px}.imidjstroy-admin-lead{max-width:800px;color:#646970;font-size:14px}.imidjstroy-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin:24px 0}.imidjstroy-admin-card{padding:22px;border:1px solid #dcdcde;border-radius:12px;background:#fff;box-shadow:0 4px 16px rgba(0,0,0,.03)}.imidjstroy-admin-card--wide{grid-column:1/-1}.imidjstroy-admin-card h2{margin:0 0 18px;font-size:18px}.imidjstroy-admin-field{margin-bottom:16px}.imidjstroy-admin-field:last-child{margin-bottom:0}.imidjstroy-admin-field label{display:block;margin-bottom:6px;font-weight:600}.imidjstroy-admin-field input,.imidjstroy-admin-field textarea{width:100%;max-width:none}.imidjstroy-admin-field p{margin:5px 0 0;color:#646970;font-size:12px}.imidjstroy-admin-columns{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0 18px}@media(max-width:782px){.imidjstroy-admin-grid,.imidjstroy-admin-columns{grid-template-columns:1fr}.imidjstroy-admin-card--wide{grid-column:auto}}
    </style>
    <?php
}
