( function () {
    'use strict';

    /**
     * Wire up a single .mw-ch-wrapper element.
     * Reads data-trigger to find the hover target. If no selector is set
     * the widget stays permanently visible (no JS interaction needed).
     *
     * @param {Element} wrap
     */
    function initWidget( wrap ) {
        if ( wrap.dataset.mwChInit ) return;
        wrap.dataset.mwChInit = '1';

        var selector = ( wrap.dataset.trigger || '' ).trim();

        // No selector → always visible; remove the hidden state and bail.
        if ( ! selector ) {
            wrap.classList.add( 'mw-ch-visible' );
            return;
        }

        var trigger = document.querySelector( selector );
        if ( ! trigger ) return;

        // Show when pointer enters the trigger element.
        trigger.addEventListener( 'mouseenter', function () {
            wrap.classList.add( 'mw-ch-visible' );
        } );

        // Hide when pointer leaves both the trigger and the widget itself.
        // We use a short delay so the user can move the cursor from the
        // trigger onto the widget without it disappearing.
        var hideTimer;

        function scheduleHide() {
            hideTimer = setTimeout( function () {
                wrap.classList.remove( 'mw-ch-visible' );
            }, 80 );
        }

        function cancelHide() {
            clearTimeout( hideTimer );
        }

        trigger.addEventListener( 'mouseleave', scheduleHide );

        // Keep widget visible while the cursor is over it.
        wrap.addEventListener( 'mouseenter', cancelHide );
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
