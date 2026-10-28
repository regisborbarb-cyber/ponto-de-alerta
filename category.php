<?php
/**
 * Página de categoria em formato de lista — Ponto de Alerta.
 * Cada notícia aparece em uma linha compacta, sem manchete gigante.
 */
get_header();
$category_title = single_cat_title('', false);
$category_description = category_description();
global $wp_query;
$total_category_posts = (int) $wp_query->found_posts;
?>
<main class="pa-category-page pa-category-list-page">
  <div class="pa-category-container">
    <header class="pa-category-header">
      <div class="pa-category-header-copy">
        <span class="pa-category-eyebrow">PONTO DE ALERTA • CATEGORIA</span>
        <h1><?php echo esc_html($category_title); ?></h1>
        <?php if (trim(wp_strip_all_tags($category_description)) !== '') : ?>
          <div class="pa-category-description"><?php echo wp_kses_post($category_description); ?></div>
        <?php else : ?>
          <p>Confira as últimas notícias de <?php echo esc_html($category_title); ?> em Água Clara e região.</p>
        <?php endif; ?>
      </div>
      <div class="pa-category-count">
        <strong><?php echo esc_html(number_format_i18n($total_category_posts)); ?></strong>
        <span><?php echo $total_category_posts === 1 ? 'notícia publicada' : 'notícias publicadas'; ?></span>
      </div>
    </header>

    <?php if (function_exists('pa_exibir_anuncio')) { pa_exibir_anuncio('topo'); } ?>

    <div class="pa-category-layout pa-category-list-layout">
      <section class="pa-category-main" aria-label="Notícias da categoria <?php echo esc_attr($category_title); ?>">
        <div class="pa-category-list-heading"><h2>Últimas notícias</h2><span></span></div>
        <?php if (have_posts()) : ?>
          <div class="pa-category-list">
            <?php while (have_posts()) : the_post(); ?>
              <article <?php post_class('pa-category-list-item'); ?>>
                <a class="pa-category-list-thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                  <?php else : ?>
                    <span class="pa-category-image-placeholder">PONTO DE ALERTA</span>
                  <?php endif; ?>
                </a>
                <div class="pa-category-list-content">
                  <div class="pa-category-meta"><time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time><span aria-hidden="true">•</span><span><?php echo esc_html(get_the_author()); ?></span></div>
                  <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                  <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
                  <a class="pa-category-list-link" href="<?php the_permalink(); ?>">Leia a notícia →</a>
                </div>
              </article>
            <?php endwhile; ?>
          </div>
          <nav class="pa-category-pagination" aria-label="Paginação das notícias">
            <?php the_posts_pagination(array('mid_size' => 1, 'prev_text' => '← Anteriores', 'next_text' => 'Próximas →')); ?>
          </nav>
        <?php else : ?>
          <div class="pa-category-empty"><h2>Nenhuma notícia nesta categoria ainda</h2><p>Assim que novas notícias forem publicadas nesta seção, elas aparecerão aqui.</p></div>
        <?php endif; ?>
      </section>

      <aside class="pa-category-sidebar">
        <section class="pa-category-sidebar-block">
          <h2 class="pa-category-sidebar-heading"><span>Mais</span> lidas</h2>
          <?php
          $popular_query = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 5, 'meta_key' => 'pa_visualizacoes', 'orderby' => 'meta_value_num', 'order' => 'DESC', 'ignore_sticky_posts' => true, 'no_found_rows' => true));
          if ($popular_query->have_posts()) : $rank = 1;
            while ($popular_query->have_posts()) : $popular_query->the_post(); ?>
              <article class="pa-category-popular-item"><span class="pa-category-popular-rank"><?php echo esc_html(sprintf('%02d', $rank)); ?></span><div class="pa-category-popular-copy"><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time></div></article>
            <?php $rank++; endwhile;
          else : ?>
            <p class="pa-category-sidebar-empty">As notícias mais lidas aparecerão aqui conforme os acessos forem registrados.</p>
          <?php endif; wp_reset_postdata(); ?>
        </section>
        <?php if (function_exists('pa_exibir_anuncio')) { pa_exibir_anuncio('lateral'); } ?>
        <section class="pa-category-sidebar-note"><span>INFORMAÇÃO LOCAL</span><h2>Água Clara e região em destaque.</h2><p>Acompanhe as atualizações do Ponto de Alerta.</p></section>
      </aside>
    </div>
  </div>
</main>
<?php get_footer(); ?>
