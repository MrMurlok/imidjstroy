<?php
/** Template Name: Блог */
/** WordPress posts page — /blog/ */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$posts_page_id = (int) get_option( 'page_for_posts' );
$blog_title    = $posts_page_id ? get_the_title( $posts_page_id ) : 'Блог';

// Do not depend on the global query here. The project uses a dedicated
// /blog/ page and we explicitly request published WordPress posts.
$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

$blog_query = new WP_Query( [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 9,
    'paged'               => $paged,
    'ignore_sticky_posts' => false,
] );
?>

<main class="blog-page">
    <section class="blog-main">
        <div class="container">
            <header class="blog-main__header">
                <h1><?php echo esc_html( $blog_title ?: 'Блог' ); ?></h1>
                <p>Полезные статьи о строительных материалах и ремонте</p>
            </header>

            <?php if ( $blog_query->have_posts() ) : ?>
                <div class="blog-grid">
                    <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                        <?php get_template_part( 'template-parts/blog/post', 'card' ); ?>
                    <?php endwhile; ?>
                </div>

                <?php if ( $blog_query->max_num_pages > 1 ) : ?>
                    <nav class="blog-pagination" aria-label="Навигация по страницам блога">
                        <?php
                        echo wp_kses_post(
                            paginate_links( [
                                'current'   => $paged,
                                'total'     => (int) $blog_query->max_num_pages,
                                'prev_text' => '← Назад',
                                'next_text' => 'Далее →',
                                'type'      => 'list',
                            ] )
                        );
                        ?>
                    </nav>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="blog-empty">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path></svg>
                    <h2>Пока нет опубликованных статей</h2>
                    <p>Новые материалы появятся здесь после публикации в WordPress.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
