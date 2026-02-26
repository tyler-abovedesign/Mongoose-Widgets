/**
 * Mongoose Widgets — Events Listing (frontend behaviour)
 *
 * Vanilla JS, no jQuery dependency.
 * Handles type filter buttons and AJAX card loading.
 */
(function () {
    'use strict';

    function initWidget(wrap) {
        if (wrap.dataset.mwElInit) {
            return;
        }
        wrap.dataset.mwElInit = '1';

        var config = window.mongooseEventsListing || {};
        var ajaxUrl = config.ajaxUrl || '';
        var nonce = config.nonce || '';
        var perPage = config.perPage || 12;
        var showPast = config.showPast || '0';

        if (!ajaxUrl) {
            return;
        }

        var activeType = 'all';

        var cardsContainer = wrap.querySelector('.mw-el-cards');
        var filtersContainer = wrap.querySelector('.mw-el-filters');

        if (!cardsContainer) {
            return;
        }

        // ── Type filter buttons ──────────────────────────────────
        if (filtersContainer) {
            filtersContainer.addEventListener('click', function (e) {
                var btn = e.target.closest('.mw-el-filter-btn');
                if (!btn) return;

                // Update active state.
                var allBtns = filtersContainer.querySelectorAll('.mw-el-filter-btn');
                for (var i = 0; i < allBtns.length; i++) {
                    allBtns[i].classList.remove('mw-el-filter-btn--active');
                }
                btn.classList.add('mw-el-filter-btn--active');

                activeType = btn.dataset.type || 'all';

                fetchEvents();
            });
        }

        function fetchEvents() {
            cardsContainer.classList.add('mw-el-cards--loading');

            var data = new FormData();
            data.append('action', 'mongoose_filter_events');
            data.append('nonce', nonce);
            data.append('event_type', activeType);
            data.append('per_page', perPage);
            data.append('show_past', showPast);

            fetch(ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: data,
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (result) {
                    if (result.success && result.data) {
                        cardsContainer.innerHTML = result.data.html;
                    }
                })
                .catch(function () {
                    cardsContainer.innerHTML = '<p class="mw-el-no-results">Failed to load events.</p>';
                })
                .finally(function () {
                    cardsContainer.classList.remove('mw-el-cards--loading');
                });
        }
    }

    function initAll(scope) {
        var root = scope || document;
        var wraps = root.querySelectorAll('.mw-el-wrap');
        for (var i = 0; i < wraps.length; i++) {
            initWidget(wraps[i]);
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
            'frontend/element_ready/mongoose-events-listing.default',
            function ($element) {
                var el = $element[0] || $element;
                initAll(el);
            }
        );
    } else {
        document.addEventListener('elementor/frontend/init', function () {
            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction(
                    'frontend/element_ready/mongoose-events-listing.default',
                    function ($element) {
                        var el = $element[0] || $element;
                        initAll(el);
                    }
                );
            }
        });
    }
})();
