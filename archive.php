<?php
/**
 * Arquivo de categorias e demais listagens — Ponto de Alerta.
 * O WordPress utiliza este modelo para categorias quando não há category.php.
 */
get_header();

$titulo_arquivo = is_category() ? single_cat_title('', false) : get_the_archive_title();
$descricao_arquivo = get_the_archive_description();
?>

<div class="pa-archive-wrap">
    <header class="pa-archive-header">
        <span class="pa-archive-kicker">NOTÍCIAS</span>
        <h1><?php echo esc_html(wp_strip_all_tags($titulo_arquivo)); ?></h1>
        <?php if (!empty($descricao_arquivo)) : ?>
            <div class="pa-archive-description"><?php echo wp_kses_post($descricao_arquivo); ?></div>
        <?php else : ?>
            <p>Acompanhe as notícias e atualizações do Ponto de Alerta.</p>
        <?php endif; ?>
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

        <nav class="pa-archive-pagination" aria-label="Paginação das notícias">
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
            <h2>Ainda não há notícias nesta seção</h2>
            <p>Volte em breve para acompanhar as atualizações.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
