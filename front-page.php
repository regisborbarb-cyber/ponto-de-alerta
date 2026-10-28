<?php
get_header();

/**
 * PONTO DE ALERTA
 * Página inicial do portal de notícias.
 */

// 1. Busca quatro notícias para o carrossel principal.
$manchetes = new WP_Query(array(
    'post_type'           => 'post',
    'posts_per_page'      => 4,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true
));

// Guarda os IDs das quatro notícias em destaque para evitar repetições.
$ids_exibidos = array_map('intval', wp_list_pluck($manchetes->posts, 'ID'));

// 2. Busca duas notícias secundárias diferentes das manchetes.
$secundarias = new WP_Query(array(
    'post_type'           => 'post',
    'posts_per_page'      => 2,
    'post_status'         => 'publish',
    'post__not_in'        => $ids_exibidos,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true
));

// Adiciona os IDs das notícias secundárias à lista.
$ids_secundarias = wp_list_pluck($secundarias->posts, 'ID');

$ids_exibidos = array_merge(
    $ids_exibidos,
    array_map('intval', $ids_secundarias)
);


// 3. Busca outras notícias sem repetir as anteriores.
$recentes = new WP_Query(array(
    'post_type'           => 'post',
    'posts_per_page'      => 5,
    'post_status'         => 'publish',
    'post__not_in'        => $ids_exibidos,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true
));


// 4. Busca notícias da categoria Região.
$noticias_regiao = new WP_Query(array(
    'post_type'           => 'post',
    'posts_per_page'      => 4,
    'category_name'       => 'regiao',
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC'
));


// 5. Busca notícias para a faixa de últimas notícias.
$ultimas = new WP_Query(array(
    'post_type'           => 'post',
    'posts_per_page'      => 5,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true
));
?>

<div class="pa-home">

    <!-- FAIXA DE ÚLTIMA HORA -->
    <section class="pa-breaking">

        <div class="pa-breaking-label">
            ÚLTIMA HORA <span>»</span>
        </div>

        <div class="pa-breaking-news" aria-label="Últimas notícias">
            <div class="pa-breaking-track">
                <?php if ($ultimas->have_posts()) : ?>
                    <div class="pa-breaking-group">
                        <?php while ($ultimas->have_posts()) : $ultimas->the_post(); ?>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <?php endwhile; ?>
                    </div>
                    <?php $ultimas->rewind_posts(); ?>
                    <div class="pa-breaking-group" aria-hidden="true">
                        <?php while ($ultimas->have_posts()) : $ultimas->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" tabindex="-1"><?php the_title(); ?></a>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <div class="pa-breaking-group">
                        <span>Acompanhe as notícias de Água Clara e região.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </section>
    <?php wp_reset_postdata(); ?>

    <?php if (function_exists('pa_exibir_anuncio')) { pa_exibir_anuncio('topo'); } ?>

    <!-- ÁREA PRINCIPAL -->
    <section class="pa-top-news">

        <!-- CARROSSEL COM QUATRO MANCHETES -->
        <div class="pa-main-headline">

            <?php if ($manchetes->have_posts()) : ?>
                <div class="pa-hero-carousel" data-pa-carousel aria-roledescription="carrossel" aria-label="Principais notícias">
                    <div class="pa-hero-slides">
                        <?php
                        $slide_index = 0;
                        while ($manchetes->have_posts()) :
                            $manchetes->the_post();
                            $categorias = get_the_category();
                            $categoria_nome = !empty($categorias) ? $categorias[0]->name : 'Notícias';
                        ?>
                            <article class="pa-hero-card pa-hero-slide<?php echo $slide_index === 0 ? ' is-active' : ''; ?>"
                                     data-pa-slide="<?php echo esc_attr($slide_index); ?>"
                                     aria-hidden="<?php echo $slide_index === 0 ? 'false' : 'true'; ?>">
                                <a class="pa-hero-image" href="<?php the_permalink(); ?>" tabindex="<?php echo $slide_index === 0 ? '0' : '-1'; ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <span class="pa-hero-image-background" aria-hidden="true">
                                            <?php the_post_thumbnail('large', array('loading' => 'lazy', 'alt' => '')); ?>
                                        </span>
                                        <?php the_post_thumbnail('large', array(
                                            'loading' => $slide_index === 0 ? 'eager' : 'lazy',
                                            'class'   => 'attachment-large size-large pa-hero-image-foreground',
                                        )); ?>
                                    <?php else : ?>
                                        <div class="pa-no-image">PONTO DE ALERTA</div>
                                    <?php endif; ?>
                                </a>
                                <div class="pa-hero-content">
                                    <span class="pa-category"><?php echo esc_html($categoria_nome); ?></span>
                                    <h1 class="pa-hero-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
                                    <div class="pa-meta">
                                        <?php echo esc_html(get_the_date('d/m/Y')); ?>
                                        <span>•</span>
                                        <?php echo esc_html(get_the_author()); ?>
                                    </div>
                                </div>
                            </article>
                        <?php
                            $slide_index++;
                        endwhile;
                        ?>
                    </div>

                    <?php if ($slide_index > 1) : ?>
                        <button class="pa-carousel-arrow pa-carousel-prev" type="button" aria-label="Notícia anterior" data-pa-prev>‹</button>
                        <button class="pa-carousel-arrow pa-carousel-next" type="button" aria-label="Próxima notícia" data-pa-next>›</button>
                        <div class="pa-carousel-dots" role="group" aria-label="Escolher notícia">
                            <?php for ($dot = 0; $dot < $slide_index; $dot++) : ?>
                                <button type="button" class="pa-carousel-dot<?php echo $dot === 0 ? ' is-active' : ''; ?>"
                                        data-pa-dot="<?php echo esc_attr($dot); ?>"
                                        aria-label="<?php echo esc_attr('Mostrar notícia ' . ($dot + 1)); ?>"
                                        aria-pressed="<?php echo $dot === 0 ? 'true' : 'false'; ?>"></button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="pa-empty">
                    <h1>Bem-vindo ao Ponto de Alerta!</h1>
                    <p>Em breve, as principais notícias estarão aqui.</p>
                </div>
            <?php endif; ?>

        </div>

        <!-- NOTÍCIAS SECUNDÁRIAS -->
        <div class="pa-secondary-news">

            <?php if ($secundarias->have_posts()) : ?>

                <?php while ($secundarias->have_posts()) :
                    $secundarias->the_post();
                ?>

                    <article class="pa-secondary-card">

                        <a class="pa-secondary-image"
                           href="<?php the_permalink(); ?>">

                            <?php if (has_post_thumbnail()) : ?>

                                <?php the_post_thumbnail('medium_large'); ?>

                            <?php else : ?>

                                <div class="pa-no-image">
                                    PONTO DE ALERTA
                                </div>

                            <?php endif; ?>

                        </a>

                        <div class="pa-secondary-content">

                            <?php
                            $categorias = get_the_category();

                            if (!empty($categorias)) :
                            ?>

                                <span class="pa-category">
                                    <?php echo esc_html($categorias[0]->name); ?>
                                </span>

                            <?php endif; ?>

                            <h2>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="pa-meta">
                                <?php echo esc_html(get_the_date('d/m/Y')); ?>
                            </div>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else : ?>

                <p>Publique mais notícias para preencher esta área.</p>

            <?php endif; ?>

        </div>

        <!-- COLUNA DE NOTÍCIAS RECENTES -->
        <aside class="pa-most-read">

            <h2 class="pa-section-heading">
                <span>+</span> MAIS NOTÍCIAS
            </h2>

            <?php if ($recentes->have_posts()) : ?>

                <?php $posicao = 1; ?>

                <?php while ($recentes->have_posts()) :
                    $recentes->the_post();
                ?>

                    <article class="pa-list-item">

                        <span class="pa-list-number">
                            <?php echo esc_html($posicao); ?>
                        </span>

                        <div class="pa-list-content">

                            <h3>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <span class="pa-list-date">
                                <?php echo esc_html(get_the_date('d/m/Y')); ?>
                            </span>

                        </div>

                        <?php if (has_post_thumbnail()) : ?>

                            <a class="pa-list-thumb"
                               href="<?php the_permalink(); ?>">

                                <?php the_post_thumbnail('thumbnail'); ?>

                            </a>

                        <?php endif; ?>

                    </article>

                    <?php $posicao++; ?>

                <?php endwhile; ?>

            <?php else : ?>

                <p>As próximas notícias aparecerão aqui.</p>

            <?php endif; ?>

        </aside>

    </section>

    <!-- NOTÍCIAS MAIS LIDAS -->
    <?php
    $noticias_mais_lidas = new WP_Query(array(
        'post_type'           => 'post',
        'posts_per_page'      => 4,
        'post_status'         => 'publish',
        'meta_key'            => 'pa_visualizacoes',
        'orderby'             => 'meta_value_num',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true
    ));
    ?>

    <section class="pa-most-read-section">

        <div class="pa-section-title">
            <h2>🔥 MAIS <span>LIDAS</span></h2>
        </div>

        <?php if ($noticias_mais_lidas->have_posts()) : ?>

            <div class="pa-most-read-grid">

                <?php
                $posicao_mais_lidas = 1;

                while ($noticias_mais_lidas->have_posts()) :
                    $noticias_mais_lidas->the_post();

                    $visualizacoes = (int) get_post_meta(
                        get_the_ID(),
                        'pa_visualizacoes',
                        true
                    );
                ?>

                    <article class="pa-most-read-card">

                        <a class="pa-most-read-image" href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large'); ?>
                            <?php else : ?>
                                <div class="pa-no-image">PONTO DE ALERTA</div>
                            <?php endif; ?>

                            <span class="pa-most-read-position">
                                <?php echo esc_html($posicao_mais_lidas); ?>
                            </span>
                        </a>

                        <div class="pa-most-read-content">
                            <span class="pa-most-read-label">
                                🔥 <?php echo esc_html($posicao_mais_lidas); ?>º MAIS LIDA
                            </span>

                            <h3>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <div class="pa-most-read-meta">
                                <span><?php echo esc_html(get_the_date('d/m/Y')); ?></span>
                                <span class="pa-most-read-views">
                                    👁 <?php echo esc_html(number_format_i18n($visualizacoes)); ?> visualizações
                                </span>
                            </div>
                        </div>

                    </article>

                <?php
                    $posicao_mais_lidas++;
                endwhile;
                ?>

            </div>

        <?php else : ?>

            <p class="pa-most-read-empty">
                As notícias mais lidas aparecerão aqui conforme receberem visualizações.
            </p>

        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </section>

    <!-- DESTAQUES DA REGIÃO -->
    <section class="pa-region-section">

        <div class="pa-section-title">

            <h2>
                DESTAQUES <span>DA REGIÃO</span>
            </h2>

            <a href="<?php echo esc_url(
                get_category_link(
                    get_cat_ID('Região')
                )
            ); ?>">
                VER TODAS →
            </a>

        </div>

        <div class="pa-region-grid pa-region-grid-count-<?php echo esc_attr(min(4, max(1, (int) $noticias_regiao->post_count))); ?>">

            <?php if ($noticias_regiao->have_posts()) : ?>

                <?php while ($noticias_regiao->have_posts()) :
                    $noticias_regiao->the_post();
                ?>

                    <article class="pa-region-card">

                        <a class="pa-region-image"
                           href="<?php the_permalink(); ?>">

                            <?php if (has_post_thumbnail()) : ?>

                                <?php the_post_thumbnail('medium_large'); ?>

                            <?php else : ?>

                                <div class="pa-no-image">
                                    PONTO DE ALERTA
                                </div>

                            <?php endif; ?>

                        </a>

                        <div class="pa-region-content">

                            <span class="pa-category">
                                REGIÃO
                            </span>

                            <h3>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <p>
                                <?php
                                echo esc_html(
                                    wp_trim_words(
                                        get_the_excerpt(),
                                        16
                                    )
                                );
                                ?>
                            </p>

                            <span class="pa-meta">
                                <?php echo esc_html(get_the_date('d/m/Y')); ?>
                            </span>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else : ?>

                <div class="pa-region-empty">
                    <h3>Notícias da nossa região</h3>

                    <p>
                        As notícias aparecerão aqui quando você publicar
                        matérias e classificá-las na categoria Região.
                    </p>
                </div>

            <?php endif; ?>

        </div>

    </section>
    <!-- NOTÍCIAS POLICIAIS -->
    <section class="pa-police-section">

        <div class="pa-section-title">

            <h2>
                GIRO <span>POLICIAL</span>
            </h2>

            <?php
            $categoria_policia = get_category_by_slug('policia');

            if ($categoria_policia) :
            ?>

                <a href="<?php echo esc_url(
                    get_category_link($categoria_policia->term_id)
                ); ?>">
                    VER TODAS →
                </a>

            <?php endif; ?>

        </div>

        <?php
        $noticias_policiais = new WP_Query(array(
            'post_type'           => 'post',
            'posts_per_page'      => 4,
            'post_status'         => 'publish',
            'category_name'       => 'policia',
            'orderby'             => 'date',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true
        ));
        ?>

        <?php if ($noticias_policiais->have_posts()) : ?>

            <div class="pa-police-grid pa-police-grid-count-<?php echo esc_attr(min(4, max(1, (int) $noticias_policiais->post_count))); ?>">

                <?php
                $posicao_policia = 0;

                while ($noticias_policiais->have_posts()) :
                    $noticias_policiais->the_post();
                ?>

                    <article class="<?php
                        echo $posicao_policia === 0
                            ? 'pa-police-featured'
                            : 'pa-police-card';
                    ?>">

                        <a class="pa-police-image"
                           href="<?php the_permalink(); ?>">

                            <?php if (has_post_thumbnail()) : ?>

                                <?php the_post_thumbnail('large'); ?>

                            <?php else : ?>

                                <div class="pa-no-image">
                                    GIRO POLICIAL
                                </div>

                            <?php endif; ?>

                        </a>

                        <div class="pa-police-content">

                            <span class="pa-category">
                                POLÍCIA
                            </span>

                            <h3>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <p class="pa-police-excerpt">
                                <?php
                                $resumo_policia = get_the_excerpt();

                                if (empty(trim($resumo_policia))) {
                                    $resumo_policia = get_the_content();
                                }

                                echo esc_html(
                                    wp_trim_words(
                                        wp_strip_all_tags($resumo_policia),
                                        20
                                    )
                                );
                                ?>
                            </p>

                            <span class="pa-meta">
                                <?php echo esc_html(get_the_date('d/m/Y')); ?>
                            </span>

                        </div>

                    </article>

                    <?php $posicao_policia++; ?>

                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <div class="pa-region-empty">
                <h3>Giro Policial</h3>

                <p>
                    As notícias policiais aparecerão aqui quando
                    você publicar matérias na categoria Polícia.
                </p>
            </div>

        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </section>
</div>

<?php
wp_reset_postdata();
get_footer();
?>