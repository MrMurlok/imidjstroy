<?php
defined( 'ABSPATH' ) || exit;
$args = wp_parse_args( isset( $args ) && is_array( $args ) ? $args : [], [ 'title'=>'Новости', 'count'=>3, 'linkText'=>'Все новости' ] );
$count = max( 1, min( 6, absint( $args['count'] ) ) );
$q = new WP_Query( [ 'post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>$count, 'ignore_sticky_posts'=>true ] );
if ( ! $q->have_posts() ) { wp_reset_postdata(); return; }
$blog_page = get_page_by_path( 'blog' );
$blog_url = $blog_page ? get_permalink( $blog_page ) : home_url( '/blog/' );
?>
<section class="home-news"><div class="container">
<div class="home-news__heading"><h2><?php echo esc_html( $args['title'] ); ?></h2><a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( $args['linkText'] ); ?><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path></svg></a></div>
<div class="blog-grid home-news__grid"><?php while ( $q->have_posts() ) : $q->the_post(); get_template_part( 'template-parts/blog/post', 'card' ); endwhile; ?></div>
</div></section>
<?php wp_reset_postdata(); ?>
