<?php get_header(); ?>

<div class="container">

    <?php if (have_posts()) : ?>

        <div class="news-grid">

            <?php while (have_posts()) : the_post(); ?>

                <article <?php post_class('news-card'); ?>>

                    <a href="<?php the_permalink(); ?>">

                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php endif; ?>

                    </a>

                    <?php
                    $categories = get_the_category();

                    if (!empty($categories)) :
                    ?>
                        <span class="news-category">
                            <?php echo esc_html($categories[0]->name); ?>
                        </span>
                    <?php endif; ?>

                    <h2>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <p>
                        <?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?>
                    </p>

                    <small>
                        <?php echo esc_html(get_the_date('d/m/Y')); ?>
                    </small>

                </article>

            <?php endwhile; ?>

        </div>

        <div class="pagination">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => '← Anterior',
                'next_text' => 'Próxima →'
            ));
            ?>
        </div>

    <?php else : ?>

        <p>Nenhuma notícia publicada ainda.</p>

    <?php endif; ?>

</div>

<?php get_footer(); ?>