<?php
/**
 * Modelo de notícia individual — Ponto de Alerta
 */
get_header();
?>

<div class="pa-single-layout">
    <article class="pa-single-article">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <nav class="pa-breadcrumbs" aria-label="Você está aqui">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Início</a>
                <span aria-hidden="true">›</span>
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                    $category = $categories[0];
                ?>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                    <span aria-hidden="true">›</span>
                <?php endif; ?>
                <span class="pa-breadcrumb-current"><?php the_title(); ?></span>
            </nav>

            <header class="pa-single-header">
                <?php if ( ! empty( $categories ) ) : ?>
                    <a class="pa-single-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                <?php endif; ?>

                <h1><?php the_title(); ?></h1>

                <?php if ( has_excerpt() ) : ?>
                    <p class="pa-single-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>

                <div class="pa-single-meta">
                    <span>Por <?php echo esc_html( get_the_author() ); ?></span>
                    <span aria-hidden="true">•</span>
                    <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
                        <?php echo esc_html( get_the_date( 'd/m/Y \à\s H:i' ) ); ?>
                    </time>
                    <?php if ( function_exists( 'pa_obter_visualizacoes' ) ) : ?>
                        <span aria-hidden="true">•</span>
                        <span><?php echo esc_html( number_format_i18n( pa_obter_visualizacoes( get_the_ID() ) ) ); ?> visualizações</span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="pa-single-story-body">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php $pa_full_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
                    <figure class="pa-single-featured-image">
                        <button class="pa-image-zoom-trigger" type="button"
                            data-full-image="<?php echo esc_url( $pa_full_image ? $pa_full_image[0] : get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>"
                            data-image-alt="<?php echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ?: get_the_title() ); ?>"
                            aria-label="Ampliar imagem: <?php echo esc_attr( get_the_title() ); ?>">
                            <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'eager' ) ); ?>
                            <span class="pa-image-zoom-hint" aria-hidden="true">⌕ Ampliar imagem</span>
                        </button>
                        <?php $caption = get_the_post_thumbnail_caption(); ?>
                        <?php if ( $caption ) : ?>
                            <figcaption><?php echo esc_html( $caption ); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <div class="pa-single-content">
                    <?php
                    the_content();
                    wp_link_pages( array(
                        'before' => '<div class="pa-page-links"><strong>Páginas:</strong> ',
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
            </div>

            <div class="pa-single-share" aria-label="Compartilhar notícia">
                <strong>Compartilhe:</strong>
                <a class="pa-share-whatsapp" href="<?php echo esc_url( 'https://api.whatsapp.com/send?text=' . rawurlencode( get_the_title() . ' — ' . get_permalink() ) ); ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                <a class="pa-share-facebook" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( get_permalink() ) ); ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
            </div>

            <?php
            $post_tags = get_the_tags();
            if ( $post_tags ) :
            ?>
                <div class="pa-single-tags">
                    <strong>Assuntos:</strong>
                    <?php foreach ( $post_tags as $tag ) : ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="pa-single-author">
                <strong><?php echo esc_html( get_the_author() ); ?></strong>
                <p>Reportagem do Ponto de Alerta — Notícias de Água Clara e Região.</p>
            </div>

            <?php if ( function_exists( 'pa_exibir_anuncio' ) ) { pa_exibir_anuncio( 'noticia' ); } ?>

        <?php endwhile; else : ?>
            <div class="pa-single-not-found">
                <h1>Notícia não encontrada</h1>
                <p>Essa notícia pode ter sido removida ou o endereço está incorreto.</p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar para a página inicial</a>
            </div>
        <?php endif; ?>
    </article>

    <aside class="pa-single-sidebar">
        <section class="pa-related-news">
            <h2 class="pa-section-heading">Leia <span>também</span></h2>
            <?php
            $current_id = get_queried_object_id();
            $related_categories = wp_get_post_categories( $current_id );
            $related_args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => 4,
                'post__not_in'        => array( $current_id ),
                'ignore_sticky_posts' => true,
            );
            if ( ! empty( $related_categories ) ) {
                $related_args['category__in'] = $related_categories;
            }
            $related_query = new WP_Query( $related_args );
            if ( $related_query->have_posts() ) :
                while ( $related_query->have_posts() ) : $related_query->the_post();
            ?>
                <article class="pa-related-card">
                    <a class="pa-related-thumb" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php else : ?>
                            <span class="pa-related-no-image">PONTO DE ALERTA</span>
                        <?php endif; ?>
                    </a>
                    <div>
                        <?php
                        $related_cats = get_the_category();
                        if ( ! empty( $related_cats ) ) :
                        ?>
                            <span class="pa-related-category"><?php echo esc_html( $related_cats[0]->name ); ?></span>
                        <?php endif; ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time>
                    </div>
                </article>
            <?php
                endwhile;
            else :
            ?>
                <p class="pa-related-empty">Outras notícias aparecerão aqui quando forem publicadas.</p>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        </section>
        <?php if ( function_exists( 'pa_exibir_anuncio' ) ) { pa_exibir_anuncio( 'lateral' ); } ?>
    </aside>
</div>

<div class="pa-image-lightbox" id="pa-image-lightbox" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Imagem ampliada">
    <button type="button" class="pa-lightbox-close" aria-label="Fechar imagem ampliada">×</button>
    <img class="pa-lightbox-image" src="" alt="">
    <p class="pa-lightbox-instruction">Clique fora da imagem ou pressione Esc para fechar</p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lightbox = document.getElementById('pa-image-lightbox');
    if (!lightbox) return;
    const lightboxImage = lightbox.querySelector('.pa-lightbox-image');
    const closeButton = lightbox.querySelector('.pa-lightbox-close');
    let lastTrigger = null;

    function closeLightbox() {
        lightbox.classList.remove('is-open');
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('pa-lightbox-open');
        lightboxImage.src = '';
        if (lastTrigger) lastTrigger.focus();
    }

    document.querySelectorAll('.pa-image-zoom-trigger').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            lastTrigger = trigger;
            lightboxImage.src = trigger.dataset.fullImage;
            lightboxImage.alt = trigger.dataset.imageAlt || '';
            lightbox.classList.add('is-open');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.classList.add('pa-lightbox-open');
            closeButton.focus();
        });
    });

    closeButton.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function (event) {
        if (event.target === lightbox) closeLightbox();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && lightbox.classList.contains('is-open')) closeLightbox();
    });
});
</script>

<?php get_footer(); ?>
