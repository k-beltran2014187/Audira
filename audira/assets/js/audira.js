/**
 * Audira — small progressive enhancements. The page works without it.
 */
( function () {
	'use strict';

	var header = document.querySelector( '.aud-header' );
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// Header gets a solid background and shadow once the page scrolls.
	if ( header ) {
		var onScroll = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
		};
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	// Only one hearing-aid style card open at a time; bring it into view.
	var styleDetails = document.querySelectorAll( '.aud-style__details' );
	styleDetails.forEach( function ( details ) {
		details.addEventListener( 'toggle', function () {
			if ( ! details.open ) {
				return;
			}
			styleDetails.forEach( function ( other ) {
				if ( other !== details ) {
					other.open = false;
				}
			} );
			var card = details.closest( '.aud-style' );
			if ( card ) {
				window.requestAnimationFrame( function () {
					card.scrollIntoView( { behavior: reduceMotion ? 'auto' : 'smooth', block: 'nearest' } );
				} );
			}
		} );
	} );

	// Gentle reveal on scroll.
	if ( reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	var selectors = [
		'.aud-section-head',
		'.aud-trust__item',
		'.aud-pick',
		'.aud-style',
		'.aud-compare',
		'.aud-otc-card',
		'.aud-criterion',
		'.aud-scorecard',
		'.aud-step',
		'.aud-faq-item',
		'.aud-cta__panel',
	];

	var observer = new IntersectionObserver(
		function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-in' );
					observer.unobserve( entry.target );
				}
			} );
		},
		{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
	);

	document.querySelectorAll( selectors.join( ',' ) ).forEach( function ( el ) {
		// Stagger siblings inside the same grid.
		var index = Array.prototype.indexOf.call( el.parentNode.children, el );
		el.style.setProperty( '--aud-delay', Math.min( index, 5 ) * 0.07 + 's' );
		el.classList.add( 'aud-reveal' );
		observer.observe( el );
	} );
} )();
