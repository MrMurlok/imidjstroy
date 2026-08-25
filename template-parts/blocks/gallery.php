<?php
defined( 'ABSPATH' ) || exit;
$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [ 'title'=>'Галерея', 'imageIds'=>[], 'columns'=>4 ] );
$ids = array_filter( array_map( 'absint', (array) $args['imageIds'] ) );
if ( empty( $ids ) ) { return; }
$cols = max( 2, min( 4, absint( $args['columns'] ) ) );
?>
<section class="home-gallery"><div class="container"><div class="home-gallery__heading"><h2><?php echo esc_html( $args['title'] ); ?></h2></div><div class="home-gallery__grid" style="--gallery-columns:<?php echo esc_attr( $cols ); ?>">
<?php foreach ( $ids as $id ) : ?><figure class="home-gallery__item"><?php echo wp_get_attachment_image( $id, 'large', false, [ 'loading'=>'lazy' ] ); ?></figure><?php endforeach; ?>
</div></div></section>
