<?php
defined( 'ABSPATH' ) || exit;
$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [ 'title'=>'Нужна консультация?', 'text'=>'Поможем подобрать материалы под вашу задачу.', 'buttonText'=>'Связаться с нами', 'buttonUrl'=>home_url('/contacts/') ] );
?>
<section class="home-cta"><div class="container"><div class="home-cta__box"><div><h2><?php echo esc_html( $args['title'] ); ?></h2><p><?php echo esc_html( $args['text'] ); ?></p></div><a href="<?php echo esc_url( $args['buttonUrl'] ); ?>" class="home-cta__button"><?php echo esc_html( $args['buttonText'] ); ?></a></div></div></section>
