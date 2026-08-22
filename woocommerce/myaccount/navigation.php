<?php
/** Custom My Account navigation. */
defined( 'ABSPATH' ) || exit;

$user = wp_get_current_user();
$name = trim( $user->first_name . ' ' . $user->last_name );
if ( '' === $name ) {
    $name = $user->display_name ? $user->display_name : $user->user_login;
}
$initials = '';
foreach ( preg_split( '/\s+/u', $name ) as $part ) {
    if ( $part ) {
        $initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
    }
    if ( mb_strlen( $initials ) >= 2 ) {
        break;
    }
}

$icons = [
    'edit-account' => '<svg viewBox="0 0 24 24"><path d="M20 21a8 8 0 0 0-16 0"></path><circle cx="12" cy="7" r="4"></circle></svg>',
    'orders' => '<svg viewBox="0 0 24 24"><path d="M16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8Z"></path><path d="M16 3v5h5"></path><path d="M8 13h8"></path><path d="M8 17h5"></path></svg>',
    'customer-logout' => '<svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5"></path><path d="M15 12H3"></path><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path></svg>',
];
do_action( 'woocommerce_before_account_navigation' );
?>
<nav class="woocommerce-MyAccount-navigation imidjstroy-account__nav" aria-label="Личный кабинет">
    <div class="imidjstroy-account__user">
        <span class="imidjstroy-account__avatar" aria-hidden="true">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/profile.webp' ); ?>" alt="">
        </span>
        <div>
            <strong><?php echo esc_html( $name ); ?></strong>
            <span><?php echo esc_html( $user->user_email ); ?></span>
        </div>
    </div>

    <ul>
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
            <li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
                    <?php echo isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span><?php echo esc_html( $label ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php do_action( 'woocommerce_after_account_navigation' ); ?>
