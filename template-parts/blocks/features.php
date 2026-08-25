<?php
defined( 'ABSPATH' ) || exit;
$defaults = [
 [ 'title'=>'Быстрая доставка', 'description'=>'Доставка по Владивостоку и области' ],
 [ 'title'=>'Гарантия качества', 'description'=>'Только сертифицированные товары' ],
 [ 'title'=>'Удобное время', 'description'=>imidjstroy_get_site_setting( 'hours_feature' ) ],
 [ 'title'=>'Поддержка', 'description'=>'Консультации по выбору материалов' ],
];
$saved_items = ! empty( $args['items'] ) && is_array( $args['items'] ) ? array_values( $args['items'] ) : [];
$items       = [];

// This section is intentionally fixed to four cards. Only their text is editable.
foreach ( $defaults as $index => $fallback ) {
    $saved = isset( $saved_items[ $index ] ) && is_array( $saved_items[ $index ] ) ? $saved_items[ $index ] : [];

    $items[] = [
        'title'       => array_key_exists( 'title', $saved ) ? (string) $saved['title'] : $fallback['title'],
        'description' => array_key_exists( 'description', $saved ) ? (string) $saved['description'] : $fallback['description'],
    ];
}

$icons = [
 '<svg viewBox="0 0 24 24"><path d="M10 17h4V5H2v12h3"></path><path d="M14 9h4l4 4v4h-3"></path><circle cx="7.5" cy="17.5" r="2.5"></circle><circle cx="16.5" cy="17.5" r="2.5"></circle></svg>',
 '<svg viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V5l8-3 8 3v8Z"></path><path d="m9 12 2 2 4-4"></path></svg>',
 '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>',
 '<svg viewBox="0 0 24 24"><path d="M4 13a8 8 0 0 1 16 0"></path><path d="M18 19h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-1v7Z"></path><path d="M6 19H5a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1v7Z"></path></svg>'
];
?>
<section class="home-features"><div class="container"><div class="home-features__grid">
<?php foreach ( $items as $index => $item ) : ?>
<article class="home-feature-card"><div class="home-feature-card__icon" aria-hidden="true"><?php echo $icons[ $index % 4 ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><div class="home-feature-card__content"><h3 class="home-feature-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3><p class="home-feature-card__description"><?php echo esc_html( $item['description'] ?? '' ); ?></p></div></article>
<?php endforeach; ?>
</div></div></section>
