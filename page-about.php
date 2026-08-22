<?php
/**
 * About page — /about/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$advantages = [
    'Широкий ассортимент стройматериалов',
    'Конкурентные цены',
    'Быстрая доставка по городу',
    'Профессиональные консультации',
    'Гарантия качества',
    'Удобные способы оплаты',
];

$stats = [
    [ 'value' => '10+', 'label' => 'лет на рынке', 'icon' => 'building' ],
    [ 'value' => '5000+', 'label' => 'клиентов', 'icon' => 'users' ],
    [ 'value' => '100%', 'label' => 'качество', 'icon' => 'award' ],
    [ 'value' => '1500+', 'label' => 'товаров', 'icon' => 'trend' ],
];
?>

<main class="about-page">
    <?php while ( have_posts() ) : the_post(); ?>
        <section class="about-hero">
            <div class="container">
                <h1><?php the_title(); ?></h1>
                <p>Надежный поставщик стройматериалов</p>
            </div>
        </section>

        <section class="about-stats">
            <div class="container">
                <div class="about-stats__grid">
                    <?php foreach ( $stats as $stat ) : ?>
                        <article class="about-stat-card">
                            <span class="about-stat-card__icon" aria-hidden="true">
                                <?php if ( 'building' === $stat['icon'] ) : ?>
                                    <svg viewBox="0 0 24 24"><path d="M3 21h18"></path><path d="M6 21V4h9v17"></path><path d="M15 9h3v12"></path><path d="M9 8h2M9 12h2M9 16h2"></path></svg>
                                <?php elseif ( 'users' === $stat['icon'] ) : ?>
                                    <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <?php elseif ( 'award' === $stat['icon'] ) : ?>
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"></circle><path d="m8.2 13.4-1.2 7.1 5-3 5 3-1.2-7.1"></path></svg>
                                <?php else : ?>
                                    <svg viewBox="0 0 24 24"><path d="M3 17l6-6 4 4 8-8"></path><path d="M14 7h7v7"></path></svg>
                                <?php endif; ?>
                            </span>
                            <strong><?php echo esc_html( $stat['value'] ); ?></strong>
                            <span><?php echo esc_html( $stat['label'] ); ?></span>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="about-main">
            <div class="container">
                <div class="about-main__grid">
                    <div class="about-history">
                        <h2>Наша история</h2>
                        <div class="about-history__content">
                            <?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
                                <?php the_content(); ?>
                            <?php else : ?>
                                <p>ИмиджСтрой — это надежный поставщик строительных материалов во Владивостоке. Мы работаем с 2014 года и за это время заслужили доверие тысяч клиентов.</p>
                                <p>Наша команда профессионалов поможет подобрать материалы для любого проекта — от небольшого ремонта до крупного строительства.</p>
                                <p>Мы предлагаем только сертифицированную продукцию от проверенных производителей по конкурентным ценам.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="about-advantages">
                        <h2>Наши преимущества</h2>
                        <ol>
                            <?php foreach ( $advantages as $index => $advantage ) : ?>
                                <li>
                                    <span><?php echo esc_html( $index + 1 ); ?></span>
                                    <strong><?php echo esc_html( $advantage ); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
