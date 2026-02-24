/**
 * Mongoose Widgets — Video on Hover (frontend behaviour)
 *
 * Vanilla JS, no jQuery dependency.
 * The video's first frame serves as the static preview.
 */
(function () {
    'use strict';

    function initContainer(container) {
        if (container.dataset.mwVohInit) {
            return;
        }
        container.dataset.mwVohInit = '1';

        var video = container.querySelector('.mw-voh-video');
        if (!video) {
            return;
        }

        container.addEventListener('mouseenter', function () {
            container.classList.add('mw-voh-active');
            video.currentTime = 0;
            video.play();
        });

        container.addEventListener('mouseleave', function () {
            container.classList.remove('mw-voh-active');
            video.pause();
            video.currentTime = 0;
        });
    }

    function initAll(scope) {
        var root = scope || document;
        var containers = root.querySelectorAll('.mw-voh-container');
        for (var i = 0; i < containers.length; i++) {
            initContainer(containers[i]);
        }
    }

    // Frontend init.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAll();
        });
    } else {
        initAll();
    }

    // Elementor editor integration.
    if (typeof window.elementorFrontend !== 'undefined') {
        window.elementorFrontend.hooks.addAction(
            'frontend/element_ready/mongoose-video-on-hover.default',
            function ($element) {
                var el = $element[0] || $element;
                initAll(el);
            }
        );
    } else {
        document.addEventListener('elementor/frontend/init', function () {
            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction(
                    'frontend/element_ready/mongoose-video-on-hover.default',
                    function ($element) {
                        var el = $element[0] || $element;
                        initAll(el);
                    }
                );
            }
        });
    }
})();
