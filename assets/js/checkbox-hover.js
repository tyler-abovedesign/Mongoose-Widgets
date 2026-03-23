( function () {
    'use strict';

    var reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

    /**
     * Show the widget. Removes display:none from the Elementor container so
     * no layout space is consumed, then fades the inner wrapper in.
     *
     * @param {Element} wrap       .mw-ch-wrapper
     * @param {Element} outerEl   nearest .elementor-widget ancestor (or wrap)
     */
    function showWrap( wrap, outerEl ) {
        clearTimeout( wrap._mwChHideTimer );
        outerEl.style.display = '';
        // Force reflow so the browser registers opacity:0 before transitioning.
        // eslint-disable-next-line no-unused-expressions
        wrap.offsetHeight;
        wrap.classList.add( 'mw-ch-visible' );
    }

    /**
     * Hide the widget. Fades the inner wrapper out, then collapses the
     * Elementor container with display:none once the transition ends.
     *
     * @param {Element} wrap
     * @param {Element} outerEl
     */
    function hideWrap( wrap, outerEl ) {
        wrap.classList.remove( 'mw-ch-visible' );

        if ( reducedMotion ) {
            outerEl.style.display = 'none';
            return;
        }

        wrap._mwChHideTimer = setTimeout( function () {
            if ( ! wrap.classList.contains( 'mw-ch-visible' ) ) {
                outerEl.style.display = 'none';
            }
        }, 350 ); // slightly longer than the 300 ms CSS duration
    }

    /**
     * Wire up a single .mw-ch-wrapper element.
     *
     * @param {Element} wrap
     */
    function initWidget( wrap ) {
        if ( wrap.dataset.mwChInit ) return;
        wrap.dataset.mwChInit = '1';

        var selector = ( wrap.dataset.trigger || '' ).trim();

        // No selector → always visible; nothing to set up.
        if ( ! selector ) {
            wrap.classList.add( 'mw-ch-visible' );
            return;
        }

        var trigger = document.querySelector( selector );
        if ( ! trigger ) return;

        // Target the nearest Elementor widget container so its padding/margin
        // is also removed from the layout when hidden.
        var outerEl = wrap.closest( '.elementor-widget' ) || wrap;

        // Start fully hidden.
        outerEl.style.display = 'none';

        trigger.addEventListener( 'mouseenter', function () {
            showWrap( wrap, outerEl );
        } );

        // Small grace period so the cursor can travel from the trigger onto
        // the widget without it collapsing in between.
        function scheduleHide() {
            clearTimeout( wrap._mwChHideTimer );
            wrap._mwChHideTimer = setTimeout( function () {
                hideWrap( wrap, outerEl );
            }, 80 );
        }

        trigger.addEventListener( 'mouseleave', scheduleHide );

        wrap.addEventListener( 'mouseenter', function () {
            clearTimeout( wrap._mwChHideTimer );
        } );
        wrap.addEventListener( 'mouseleave', scheduleHide );
    }

    /** Initialise every widget found in the current document. */
    function initAll() {
        document.querySelectorAll( '.mw-ch-wrapper' ).forEach( initWidget );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', initAll );
    } else {
        initAll();
    }

    if ( window.elementorFrontend ) {
        window.elementorFrontend.hooks.addAction(
            'frontend/element_ready/mongoose-checkbox-hover.default',
            function ( $el ) {
                var el = ( $el instanceof Element ) ? $el : $el[ 0 ];
                if ( ! el ) return;
                var wrap = el.querySelector( '.mw-ch-wrapper' );
                if ( wrap ) initWidget( wrap );
            }
        );
    }
} )();
