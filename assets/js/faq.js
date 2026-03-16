(function () {
    function initFAQ(container) {
        var toggles = container.querySelectorAll('[data-mw-faq-toggle]');

        toggles.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var item = btn.closest('[data-mw-faq-item]');
                var answer = item.querySelector('[data-mw-faq-answer]');
                var isOpen = item.classList.contains('is-open');

                if (isOpen) {
                    item.classList.remove('is-open');
                    btn.setAttribute('aria-expanded', 'false');
                    answer.classList.remove('is-visible');
                } else {
                    item.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                    answer.classList.add('is-visible');
                }
            });
        });
    }

    // Frontend
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.mw-faq').forEach(function (el) {
            initFAQ(el);
        });
    });

    // Elementor editor
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
        window.elementorFrontend.hooks.addAction(
            'frontend/element_ready/mongoose-faq.default',
            function ($element) {
                var el = $element[0] || $element;
                var faq = el.querySelector('.mw-faq');
                if (faq) {
                    initFAQ(faq);
                }
            }
        );
    }
})();
