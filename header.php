<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header pa-reference-header">
    <div class="pa-masthead">
        <div class="pa-masthead-inner">
            <div class="pa-brand-lockup">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="pa-brand-logo" aria-label="Ponto de Alerta — página inicial">
                    <svg class="pa-brand-siren" viewBox="0 0 100 100" aria-hidden="true" focusable="false">
                        <path d="M15 70 C2 58 12 39 28 34 L34 30 L31 43 C19 49 17 59 27 66 Z" fill="#168a45"/>
                        <path d="M18 77 C30 88 54 88 68 76 L77 72 L72 84 C56 97 31 95 17 84 Z" fill="#0f6833"/>
                        <path d="M31 70 L35 31 C36 17 45 8 57 8 C69 8 77 17 78 31 L82 70 Z" fill="#0066a6" stroke="#8fd0f2" stroke-width="3"/>
                        <path d="M42 29 C43 18 49 13 56 13 L56 69 L34 69 Z" fill="#8fd0f2" opacity=".85"/>
                        <ellipse cx="57" cy="42" rx="18" ry="17" fill="#168a45"/>
                        <ellipse cx="57" cy="42" rx="10" ry="10" fill="#83d4a0"/>
                        <path d="M27 70 H85 L91 79 H22 Z" fill="#004b78" stroke="#8ab9d1" stroke-width="2"/>
                        <path d="M31 78 H84 L79 84 H35 Z" fill="#fff"/>
                    </svg>
                    <span class="pa-brand-wordmark"><span class="pa-brand-top">PONTO DE</span><strong>ALERTA</strong></span>
                </a>
            </div>

            <div class="pa-masthead-tagline"><span></span><p>Notícias de Água Clara<br>e Região</p></div>

            <?php if (function_exists('pa_exibir_anuncio')) { pa_exibir_anuncio('header-square'); } ?>

            <div class="pa-header-tools">
                <div class="pa-social-icons" aria-label="Redes sociais">
                    <span title="Facebook" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V8c0-.9.3-1.5 1.6-1.5h1.7V3.7c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2H7.3V13h2.8v8z" fill="currentColor"/></svg></span>
                    <span title="Instagram" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2.5"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2.5"/><circle cx="17.6" cy="6.6" r="1.3" fill="currentColor"/></svg></span>
                    <span title="YouTube" aria-label="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23 7.1a3 3 0 0 0-2.1-2.2C19 4.4 12 4.4 12 4.4s-7 0-8.9.5A3 3 0 0 0 1 7.1 31 31 0 0 0 .5 12a31 31 0 0 0 .5 4.9 3 3 0 0 0 2.1 2.2c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.2 31 31 0 0 0 .5-4.9 31 31 0 0 0-.5-4.9ZM9.6 15.6V8.4l6.2 3.6z" fill="currentColor"/></svg></span>
                    <span title="WhatsApp" aria-label="WhatsApp"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11.7 11.7 0 0 0 2.1 17.6L.5 23.5l6-1.6a11.7 11.7 0 0 0 14-18.4ZM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-3.5.9.9-3.4-.2-.4A9 9 0 1 1 12 21Zm5-6.7c-.3-.2-1.7-.9-2-.9s-.5-.2-.7.2-.8.9-1 1.1-.4.2-.7.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.5.1-.6l.5-.6c.2-.2.2-.4.3-.6s0-.4 0-.6-.7-1.6-1-2.2c-.2-.5-.5-.4-.7-.4H7.5c-.2 0-.6.1-.9.4s-1.2 1.2-1.2 2.8 1.2 3.2 1.4 3.4 2.4 3.7 5.8 5.1c.8.3 1.4.5 1.9.6.8.3 1.5.2 2.1.1.7-.1 1.7-.7 1.9-1.4s.2-1.3.2-1.4-.3-.3-.6-.4Z" fill="currentColor"/></svg></span>
                </div>
                <div class="pa-header-search"><?php get_search_form(); ?></div>
            </div>
        </div>
    </div>

    <nav id="pa-primary-navigation" class="main-navigation pa-reference-navigation" aria-label="Menu de categorias">
        <div class="pa-navigation-inner">
            <?php if (has_nav_menu('menu-principal')) : ?>
                <button class="pa-menu-toggle" type="button" aria-label="Abrir menu de categorias" aria-expanded="false" aria-controls="pa-category-menu">
                    <span class="pa-menu-toggle-icon" aria-hidden="true"><span></span><span></span><span></span></span>
                    <span>Categorias</span>
                </button>
            <?php endif; ?>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'menu-principal',
                'container'      => false,
                'fallback_cb'    => false,
                'menu_class'     => 'menu-principal',
                'menu_id'        => 'pa-category-menu',
            ));
            ?>
        </div>
    </nav>
</header>

<main class="site-main">
