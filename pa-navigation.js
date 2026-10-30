(function () {
    'use strict';

    function initializeNavigation() {
        var navigation = document.getElementById('pa-primary-navigation');
        var toggle = navigation && navigation.querySelector('.pa-menu-toggle');
        var menu = document.getElementById('pa-category-menu');
        var navigationInner = navigation && navigation.querySelector('.pa-navigation-inner');

        if (!navigation || !toggle || !menu || !navigationInner) {
            return;
        }

        navigation.classList.add('has-toggle');

        function clearMenuPosition() {
            navigation.classList.remove('is-flipped');
            navigation.classList.remove('is-height-limited');
            navigation.style.removeProperty('--pa-menu-top');
            navigation.style.removeProperty('--pa-menu-left');
            navigation.style.removeProperty('--pa-menu-width');
            navigation.style.removeProperty('--pa-menu-height');
        }

        function positionMenu() {
            if (toggle.getAttribute('aria-expanded') !== 'true') {
                return;
            }

            clearMenuPosition();

            var menuRect = menu.getBoundingClientRect();
            if (menuRect.bottom <= window.innerHeight) {
                return;
            }

            var availableBelow = window.innerHeight - menuRect.top;
            if (availableBelow >= 200) {
                navigation.style.setProperty('--pa-menu-height', availableBelow + 'px');
                navigation.classList.add('is-height-limited');
                return;
            }

            var toggleRect = toggle.getBoundingClientRect();
            var innerRect = navigationInner.getBoundingClientRect();
            var adminBar = document.getElementById('wpadminbar');
            var top = adminBar ? adminBar.getBoundingClientRect().bottom : 0;
            var bottom = Math.max(top, Math.min(toggleRect.top - 4, window.innerHeight));

            navigation.style.setProperty('--pa-menu-top', top + 'px');
            navigation.style.setProperty('--pa-menu-left', innerRect.left + 'px');
            navigation.style.setProperty('--pa-menu-width', innerRect.width + 'px');
            navigation.style.setProperty('--pa-menu-height', Math.max(0, bottom - top) + 'px');
            navigation.classList.add('is-flipped');
        }

        function setExpanded(expanded) {
            navigation.classList.toggle('is-open', expanded);
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            toggle.setAttribute(
                'aria-label',
                expanded ? 'Fechar menu de categorias' : 'Abrir menu de categorias'
            );

            if (expanded) {
                positionMenu();
            } else {
                clearMenuPosition();
            }
        }

        toggle.addEventListener('click', function () {
            setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
        });

        navigation.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
                setExpanded(false);
                toggle.focus();
            }
        });

        menu.addEventListener('click', function (event) {
            if (event.target.closest('a')) {
                setExpanded(false);
            }
        });

        document.addEventListener('click', function (event) {
            if (!navigation.contains(event.target) && toggle.getAttribute('aria-expanded') === 'true') {
                setExpanded(false);
            }
        });

        window.addEventListener('resize', positionMenu);
        window.addEventListener('orientationchange', positionMenu);
        window.addEventListener('scroll', positionMenu);

        if (window.matchMedia) {
            var desktopQuery = window.matchMedia('(min-width: 901px)');
            var resetOnDesktop = function (event) {
                if (event.matches) {
                    setExpanded(false);
                }
            };

            if (desktopQuery.addEventListener) {
                desktopQuery.addEventListener('change', resetOnDesktop);
            } else if (desktopQuery.addListener) {
                desktopQuery.addListener(resetOnDesktop);
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeNavigation);
    } else {
        initializeNavigation();
    }
}());
