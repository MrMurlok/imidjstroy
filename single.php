<?php
/** Single blog post. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
$blog_page_id = (int) get_option( 'page_for_posts' );
$blog_url     = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' );
?>

<main class="blog-single-page">
    <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'blog-single' ); ?>>
            <div class="container blog-single__container">
                <a class="blog-single__back" href="<?php echo esc_url( $blog_url ); ?>">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                    Назад к блогу
                </a>

                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="blog-single__image">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </figure>
                <?php endif; ?>

                <div class="blog-single__date">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
                    <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
                </div>

                <h1><?php the_title(); ?></h1>

                <div class="blog-single__content">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                </div>

                <div class="blog-single__footer">
                    <a href="<?php echo esc_url( $blog_url ); ?>">← Все статьи</a>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
