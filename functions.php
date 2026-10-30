<?php

function portal_noticias_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    register_nav_menus(array(
        'menu-principal' => 'Menu Principal'
    ));
}
add_action('after_setup_theme', 'portal_noticias_setup');


function portal_noticias_enqueue_assets() {
    $style_css_path = get_stylesheet_directory() . '/style.css';
    wp_enqueue_style(
        'portal-noticias-style',
        get_stylesheet_uri(),
        array(),
        file_exists($style_css_path) ? (string) filemtime($style_css_path) : '1.0'
    );

    $navigation_js = get_stylesheet_directory() . '/pa-navigation.js';
    wp_enqueue_script(
        'pa-navigation',
        get_stylesheet_directory_uri() . '/pa-navigation.js',
        array(),
        file_exists($navigation_js) ? (string) filemtime($navigation_js) : '1.0',
        true
    );

    // Recursos exclusivos do carrossel da página inicial.
    if (is_front_page()) {
        $carousel_css = get_stylesheet_directory() . '/home-carousel.css';
        $carousel_js  = get_stylesheet_directory() . '/home-carousel.js';

        wp_enqueue_style(
            'pa-home-carousel',
            get_stylesheet_directory_uri() . '/home-carousel.css',
            array('portal-noticias-style'),
            file_exists($carousel_css) ? (string) filemtime($carousel_css) : '1.0'
        );

        wp_enqueue_script(
            'pa-home-carousel',
            get_stylesheet_directory_uri() . '/home-carousel.js',
            array(),
            file_exists($carousel_js) ? (string) filemtime($carousel_js) : '1.0',
            true
        );

        $dynamic_top_ad_js = get_stylesheet_directory() . '/pa-dynamic-top-ad.js';
        wp_enqueue_script(
            'pa-dynamic-top-ad',
            get_stylesheet_directory_uri() . '/pa-dynamic-top-ad.js',
            array(),
            file_exists($dynamic_top_ad_js) ? (string) filemtime($dynamic_top_ad_js) : '1.0',
            true
        );
    }

    // Estilos dos espaços publicitários do portal.
    $ads_css_path = get_stylesheet_directory() . '/anuncios-ponto-alerta.css';
    if (file_exists($ads_css_path)) {
        wp_enqueue_style(
            'pa-anuncios',
            get_stylesheet_directory_uri() . '/anuncios-ponto-alerta.css',
            array('portal-noticias-style'),
            (string) filemtime($ads_css_path)
        );
    }

    // Carrega os estilos específicos das páginas individuais de notícias.
    if (is_singular('post')) {
        $single_css_path = get_stylesheet_directory() . '/single-news.css';
        $single_css_uri  = get_stylesheet_directory_uri() . '/single-news.css';

        if (file_exists($single_css_path)) {
            wp_enqueue_style(
                'portal-noticias-single-news',
                $single_css_uri,
                array('portal-noticias-style'),
                (string) filemtime($single_css_path)
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'portal_noticias_enqueue_assets');


/**
 * PONTO DE ALERTA
 * Contador de visualizações das notícias.
 *
 * Conta no máximo uma visualização por navegador, por notícia,
 * a cada 12 horas. Usa cookie em vez de sessão PHP.
 */
function pa_registrar_visualizacao() {

    if (is_admin() || !is_singular('post') || is_preview()) {
        return;
    }

    if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
        return;
    }

    $post_id = get_queried_object_id();

    if (!$post_id || get_post_status($post_id) !== 'publish') {
        return;
    }

    $cookie_name = 'pa_visto_' . $post_id;

    // Se este navegador já contou esta notícia nas últimas 12 horas,
    // não incrementa novamente.
    if (isset($_COOKIE[$cookie_name])) {
        return;
    }

    // Não tenta gravar cookie se os cabeçalhos já foram enviados.
    if (headers_sent()) {
        return;
    }

    $visualizacoes = (int) get_post_meta($post_id, 'pa_visualizacoes', true);

    update_post_meta($post_id, 'pa_visualizacoes', $visualizacoes + 1);

    // Cookie válido por 12 horas. A notícia poderá contar novamente depois disso.
    $expiracao = time() + (12 * HOUR_IN_SECONDS);
    $cookie_path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
    $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';

    setcookie(
        $cookie_name,
        '1',
        $expiracao,
        $cookie_path,
        $cookie_domain,
        is_ssl(),
        true
    );

    // Disponibiliza o cookie também para verificações nesta mesma requisição.
    $_COOKIE[$cookie_name] = '1';
}
add_action('template_redirect', 'pa_registrar_visualizacao', 1);


/**
 * Retorna a quantidade de visualizações de uma notícia.
 */
function pa_obter_visualizacoes($post_id = 0) {

    $post_id = $post_id ? absint($post_id) : get_the_ID();

    if (!$post_id) {
        return 0;
    }

    return (int) get_post_meta($post_id, 'pa_visualizacoes', true);
}
/**
 * PONTO DE ALERTA
 * Espaços publicitários do portal.
 */

function pa_exibir_anuncio($local) {

    $locais_validos = array(
        'topo',
        'noticia',
        'lateral',
        'after-main',
        'after-most-read',
        'after-region',
        'after-police',
        'header-square',
        'sidebar-bottom'
    );

    if (!in_array($local, $locais_validos, true)) {
        return;
    }

    $nova_posicao = in_array($local, array(
        'after-main',
        'after-most-read',
        'after-region',
        'after-police',
        'header-square',
        'sidebar-bottom'
    ), true);
    $formato_horizontal = in_array($local, array(
        'after-main',
        'after-most-read',
        'after-region',
        'after-police'
    ), true);
    $classes = array(
        'pa-anuncio',
        'pa-anuncio-' . $local
    );
    if ($nova_posicao) {
        $classes[] = 'pa-ad-slot';
        $classes[] = 'pa-ad-' . $local;
    }
    if ($formato_horizontal) {
        $classes[] = 'pa-ad-horizontal';
    }
    $class_name = implode(' ', $classes);

    ?>
    <?php if ($nova_posicao) : ?>
        <aside id="pa-ad-<?php echo esc_attr($local); ?>" class="<?php echo esc_attr($class_name); ?>" aria-label="Publicidade">
    <?php else : ?>
        <div class="<?php echo esc_attr($class_name); ?>">
    <?php endif; ?>

        <span class="pa-anuncio-label">
            PUBLICIDADE
        </span>

        <div class="pa-anuncio-conteudo">

            <?php if ($local === 'topo' && function_exists('the_ad_placement')) : ?>
                <?php the_ad_placement('banner-principal'); ?>
            <?php else : ?>
                <strong><?php echo $nova_posicao ? 'Seu anúncio pode aparecer aqui' : 'Seu negócio pode aparecer aqui!'; ?></strong>

                <?php if (!$nova_posicao) : ?>
                    <p>
                        Anuncie no Ponto de Alerta e divulgue sua
                        empresa para os leitores de Água Clara e região.
                    </p>
                <?php endif; ?>
            <?php endif; ?>

        </div>

    <?php if ($nova_posicao) : ?>
        </aside>
    <?php else : ?>
        </div>
    <?php endif; ?>
    <?php
}