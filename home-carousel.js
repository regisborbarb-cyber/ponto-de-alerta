(function () {
    'use strict';

    function initCarousel(root) {
        var slides = Array.prototype.slice.call(root.querySelectorAll('[data-pa-slide]'));
        var prev = root.querySelector('[data-pa-prev]');
        var next = root.querySelector('[data-pa-next]');
        var dots = Array.prototype.slice.call(root.querySelectorAll('[data-pa-dot]'));
        if (slides.length < 2) return;

        var current = 0;
        var timer = null;
        var paused = false;
        var interval = 5000;
        var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function show(index) {
            current = (index + slides.length) % slides.length;
            slides.forEach(function (slide, i) {
                var active = i === current;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', active ? 'false' : 'true');
                var link = slide.querySelector('.pa-hero-image');
                if (link) link.setAttribute('tabindex', active ? '0' : '-1');
            });
            dots.forEach(function (dot, i) {
                var active = i === current;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        }

        function stop() {
            if (timer) window.clearInterval(timer);
            timer = null;
        }

        function start() {
            stop();
            if (!paused && !reduceMotion) {
                timer = window.setInterval(function () { show(current + 1); }, interval);
            }
        }

        function go(index) {
            show(index);
            start();
        }

        if (prev) prev.addEventListener('click', function () { go(current - 1); });
        if (next) next.addEventListener('click', function () { go(current + 1); });
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                go(parseInt(dot.getAttribute('data-pa-dot'), 10) || 0);
            });
        });

        root.addEventListener('mouseenter', function () {
            paused = true;
            stop();
        });
        root.addEventListener('mouseleave', function () {
            paused = false;
            start();
        });
        root.addEventListener('focusin', function () {
            paused = true;
            stop();
        });
        root.addEventListener('focusout', function (event) {
            if (!root.contains(event.relatedTarget)) {
                paused = false;
                start();
            }
        });
        root.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft') { event.preventDefault(); go(current - 1); }
            if (event.key === 'ArrowRight') { event.preventDefault(); go(current + 1); }
        });

        show(0);
        start();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-pa-carousel]').forEach(initCarousel);
    });
}());
