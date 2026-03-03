/**
 * Mongoose Widgets — Events Listing (frontend behaviour)
 *
 * Vanilla JS, no jQuery dependency.
 * Handles type filter buttons, month sidebar, archive toggle, and AJAX card loading.
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
        var activeMonth = '';
        var isArchive = false;

        var cardsContainer = wrap.querySelector('.mw-el-cards');
        var filtersContainer = wrap.querySelector('.mw-el-filters');
        var monthsList = wrap.querySelector('.mw-el-months');

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
                activeMonth = '';

                // Exit archive mode when switching type.
                isArchive = false;
                clearActiveMonth();
                clearArchive();

                fetchEvents();
            });
        }

        // ── Month sidebar (delegated) ────────────────────────────
        if (monthsList) {
            monthsList.addEventListener('click', function (e) {
                // Archive button.
                var archiveBtn = e.target.closest('.mw-el-archive-btn');
                if (archiveBtn) {
                    isArchive = !isArchive;
                    activeMonth = '';
                    clearActiveMonth();

                    if (isArchive) {
                        archiveBtn.classList.add('mw-el-archive-btn--active');
                    } else {
                        archiveBtn.classList.remove('mw-el-archive-btn--active');
                    }

                    fetchEvents();
                    return;
                }

                // Month button.
                var btn = e.target.closest('.mw-el-month-btn');
                if (!btn) return;

                var clickedMonth = btn.dataset.month || '';

                // Toggle: clicking active month deselects it.
                if (activeMonth === clickedMonth) {
                    activeMonth = '';
                    btn.classList.remove('mw-el-month-btn--active');
                } else {
                    activeMonth = clickedMonth;
                    clearActiveMonth();
                    btn.classList.add('mw-el-month-btn--active');
                }

                fetchEvents();
            });
        }

        function clearActiveMonth() {
            if (!monthsList) return;
            var activeBtns = monthsList.querySelectorAll('.mw-el-month-btn--active');
            for (var i = 0; i < activeBtns.length; i++) {
                activeBtns[i].classList.remove('mw-el-month-btn--active');
            }
        }

        function clearArchive() {
            if (!monthsList) return;
            var btn = monthsList.querySelector('.mw-el-archive-btn--active');
            if (btn) btn.classList.remove('mw-el-archive-btn--active');
        }

        function fetchEvents() {
            cardsContainer.classList.add('mw-el-cards--loading');

            var data = new FormData();
            data.append('action', 'mongoose_filter_events');
            data.append('nonce', nonce);
            data.append('event_type', activeType);
            data.append('month', activeMonth);
            data.append('per_page', perPage);
            data.append('show_past', showPast);
            data.append('archive', isArchive ? '1' : '0');

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
                        rebuildMonths(result.data.months);
                    }
                })
                .catch(function () {
                    cardsContainer.innerHTML = '<p class="mw-el-no-results">Failed to load events.</p>';
                })
                .finally(function () {
                    cardsContainer.classList.remove('mw-el-cards--loading');
                });
        }

        function rebuildMonths(months) {
            if (!monthsList) return;

            var html = '';

            if (!months || !months.length) {
                html += '<li class="mw-el-months__empty">' + (isArchive ? 'No past months' : 'No upcoming months') + '</li>';
            } else {
                for (var i = 0; i < months.length; i++) {
                    var m = months[i];
                    var activeClass = m.value === activeMonth ? ' mw-el-month-btn--active' : '';
                    html += '<li><button type="button" class="mw-el-month-btn' + activeClass + '" data-month="' + m.value + '">' + m.label + '</button></li>';
                }
            }

            // Always append archive button.
            var archiveActive = isArchive ? ' mw-el-archive-btn--active' : '';
            html += '<li class="mw-el-months__archive"><button type="button" class="mw-el-archive-btn' + archiveActive + '"><i class="eicon-history"></i> Archive</button></li>';

            monthsList.innerHTML = html;
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
