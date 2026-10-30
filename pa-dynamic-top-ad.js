(function () {
    'use strict';

    if (window.paDynamicTopAdInitialized) {
        return;
    }
    window.paDynamicTopAdInitialized = true;

    var slot = document.querySelector('.pa-anuncio-topo');
    var contentSelector = '.pa-anuncio-conteudo';
    var activeClass = 'pa-anuncio-conteudo--dynamic-bg';
    var backgroundProperty = '--pa-anuncio-topo-bg';
    var updateQueued = false;

    if (!slot) {
        return;
    }

    function clearBackground(content) {
        content.classList.remove(activeClass);
        content.style.removeProperty(backgroundProperty);
    }

    function getImageSource(image) {
        return image.currentSrc ||
            image.getAttribute('data-src') ||
            image.getAttribute('data-lazy-src') ||
            image.getAttribute('data-original') ||
            image.getAttribute('src');
    }

    function getVisibleLoadedImage(content) {
        var images = content.querySelectorAll('img');

        for (var index = 0; index < images.length; index += 1) {
            var image = images[index];
            var rect = image.getBoundingClientRect();
            var style = window.getComputedStyle(image);

            if (
                image.naturalWidth > 0 &&
                rect.width > 0 &&
                rect.height > 0 &&
                style.display !== 'none' &&
                style.visibility !== 'hidden'
            ) {
                return image;
            }
        }

        return null;
    }

    function escapeCssUrl(url) {
        return 'url("' + url.replace(/["\\\n\r\f]/g, function (character) {
            return '\\' + character;
        }) + '")';
    }

    function updateBackground() {
        updateQueued = false;
        var content = slot.querySelector(contentSelector);

        if (!content) {
            return;
        }

        var image = getVisibleLoadedImage(content);
        var source = image && getImageSource(image);

        if (!source) {
            clearBackground(content);
            return;
        }

        content.style.setProperty(backgroundProperty, escapeCssUrl(source));
        content.classList.add(activeClass);
    }

    function scheduleUpdate() {
        if (updateQueued) {
            return;
        }

        updateQueued = true;
        window.requestAnimationFrame(updateBackground);
    }

    slot.addEventListener('load', scheduleUpdate, true);
    slot.addEventListener('error', scheduleUpdate, true);
    window.addEventListener('resize', scheduleUpdate, { passive: true });
    window.addEventListener('orientationchange', scheduleUpdate, { passive: true });

    var observer = new MutationObserver(scheduleUpdate);
    observer.observe(slot, {
        attributes: true,
        attributeFilter: ['src', 'srcset', 'sizes', 'data-src', 'data-lazy-src', 'data-original'],
        childList: true,
        subtree: true
    });

    scheduleUpdate();
}());
