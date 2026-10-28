<?php
/**
 * Resultados de busca — Ponto de Alerta
 */
get_header();

$termo_busca = get_search_query();
?>

<div class="pa-archive-wrap">
    <header class="pa-archive-header">
        <span class="pa-archive-kicker">PONTO DE ALERTA</span>
        <h1>Resultados da busca</h1>
        <?php if ($termo_busca !== '') : ?>
            <p>Você pesquisou por: <strong><?php echo esc_html($termo_busca); ?></strong></p>
        <?php endif; ?>

        <div class="pa-archive-search">
            <?php get_search_form(); ?>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <div class="pa-archive-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('pa-archive-card'); ?>>
                    <a class="pa-archive-thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                        <?php else : ?>
                            <span class="pa-archive-no-image">PONTO DE ALERTA</span>
                        <?php endif; ?>
                    </a>

                    <div class="pa-archive-card-content">
                        <?php
                        $cats = get_the_category();
                        if (!empty($cats)) :
                        ?>
                            <a class="pa-archive-category" href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>">
                                <?php echo esc_html($cats[0]->name); ?>
                            </a>
                        <?php endif; ?>

                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                        <div class="pa-archive-meta">
                            <?php echo esc_html(get_the_date('d/m/Y')); ?>
                            <span aria-hidden="true">•</span>
                            <?php echo esc_html(get_the_author()); ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <nav class="pa-archive-pagination" aria-label="Paginação dos resultados">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 1,
                'prev_text' => '← Anteriores',
                'next_text' => 'Próximas →',
            ));
            ?>
        </nav>
    <?php else : ?>
        <div class="pa-archive-empty">
            <h2>Nenhuma notícia encontrada</h2>
            <p>Não encontramos notícias com esses termos. Tente outras palavras ou pesquise por uma categoria.</p>
            <div class="pa-archive-search"><?php get_search_form(); ?></div>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
