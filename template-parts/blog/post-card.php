<?php
/** Blog card used on the posts page. */
defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'blog-card' ); ?>>
    <a class="blog-card__link" href="<?php the_permalink(); ?>">
        <div class="blog-card__media">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy' ] ); ?>
            <?php else : ?>
                <span class="blog-card__placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path></svg>
                </span>
            <?php endif; ?>
        </div>

        <div class="blog-card__body">
            <div class="blog-card__date">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
                <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></time>
            </div>
            <h2><?php the_title(); ?></h2>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
            <span class="blog-card__more">Читать далее <svg viewBox="0 0 24 24"><path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path></svg></span>
        </div>
    </a>
</article>
