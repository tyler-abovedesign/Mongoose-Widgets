( function () {
    'use strict';

    var reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

    /**
     * Show the wrapper: remove display:none, force a reflow so the browser
     * registers the starting opacity, then add the visible class to trigger
     * the CSS transition.
     *
     * @param {Element} wrap
     */
    function showWrap( wrap ) {
        clearTimeout( wrap._mwChHideTimer );
        wrap.style.display = '';
        // Force reflow so the opacity transition fires from 0 → 1.
        // eslint-disable-next-line no-unused-expressions
        wrap.offsetHeight;
        wrap.classList.add( 'mw-ch-visible' );
    }

    /**
     * Hide the wrapper: remove the visible class so opacity fades to 0, then
     * set display:none once the transition finishes (eliminating layout space).
     * Falls back to immediate hide when reduced-motion is preferred.
     *
     * @param {Element} wrap
     */
    function hideWrap( wrap ) {
        wrap.classList.remove( 'mw-ch-visible' );

        if ( reducedMotion ) {
            wrap.style.display = 'none';
            return;
        }

        // Wait for the opacity transition to finish before collapsing.
        wrap._mwChHideTimer = setTimeout( function () {
            if ( ! wrap.classList.contains( 'mw-ch-visible' ) ) {
                wrap.style.display = 'none';
            }
        }, 350 ); // slightly longer than the 300 ms CSS duration
    }

    /**
     * Wire up a single .mw-ch-wrapper element.
     * Reads data-trigger to find the hover target. If no selector is set the
     * widget is always visible and no JS interaction is needed.
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

        // Start fully hidden (no layout space consumed).
        wrap.style.display = 'none';

        trigger.addEventListener( 'mouseenter', function () {
            showWrap( wrap );
        } );

        // Small grace period so the cursor can travel from the trigger onto
        // the widget without it collapsing in between.
        function scheduleHide() {
            clearTimeout( wrap._mwChHideTimer );
            wrap._mwChHideTimer = setTimeout( function () {
                hideWrap( wrap );
            }, 80 );
        }

        trigger.addEventListener( 'mouseleave', scheduleHide );

        // Keep visible while cursor is over the widget itself.
        wrap.addEventListener( 'mouseenter', function () {
            clearTimeout( wrap._mwChHideTimer );
        } );
        wrap.addEventListener( 'mouseleave', scheduleHide );
    }

    /** Initialise every widget found in the current document. */
    function initAll() {
        document.querySelectorAll( '.mw-ch-wrapper' ).forEach( initWidget );
    }

    // Standard DOM-ready initialisation.
    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', initAll );
    } else {
        initAll();
    }

    // Elementor editor / frontend preview integration.
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
