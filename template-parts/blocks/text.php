<?php
defined( 'ABSPATH' ) || exit;
$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [ 'eyebrow'=>'', 'title'=>'Заголовок блока', 'content'=>'Добавьте текст в редакторе главной страницы.' ] );
?>
<section class="home-text-block"><div class="container"><div class="home-text-block__inner"><?php if ( $args['eyebrow'] ) : ?><span class="home-text-block__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span><?php endif; ?><h2><?php echo esc_html( $args['title'] ); ?></h2><div class="home-text-block__content"><?php echo wp_kses_post( wpautop( $args['content'] ) ); ?></div></div></div></section>
