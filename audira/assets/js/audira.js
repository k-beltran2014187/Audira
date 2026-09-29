/**
 * Audira — small progressive enhancements. The page works without it.
 *
 * Motion philosophy: one orchestrated entrance for the hero, one quiet
 * reveal per section (not per card), and a handful of interactive
 * micro-transitions. Nothing repeats the same fade-up on every element.
 */
( function () {
	'use strict';

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var header = document.querySelector( '.aud-header' );

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

	if ( reduceMotion ) {
		return;
	}

	/**
	 * One orchestrated hero entrance, played once on load. Each part is
	 * timed off the previous one rather than sharing a single duration.
	 */
	var hero = document.querySelector( '.aud-hero' );
	if ( hero ) {
		var sequence = [
			[ '.aud-eyebrow--dot', 0 ],
			[ '.aud-hero__title', 90 ],
			[ '.aud-hero__lead', 260 ],
			[ '.aud-hero__actions', 380 ],
			[ '.aud-hero__points', 460 ],
			[ '.aud-hero__media', 160 ],
			[ '.aud-float--top', 620 ],
			[ '.aud-float--bottom', 720 ],
		];
		sequence.forEach( function ( pair ) {
			var el = hero.querySelector( pair[ 0 ] );
			if ( el ) {
				el.classList.add( 'aud-hero-in' );
				el.style.setProperty( '--aud-hero-delay', pair[ 1 ] + 'ms' );
			}
		} );
		window.requestAnimationFrame( function () {
			window.requestAnimationFrame( function () {
				hero.classList.add( 'is-playing' );
			} );
		} );
	}

	// Quiet reveal, once per section — grid children stagger via CSS, not JS.
	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	var sectionSelectors = [
		'.aud-trust__grid',
		'.aud-pick-grid',
		'.aud-more-cards',
		'.aud-style-grid',
		'.aud-compare',
		'.aud-otc-grid',
		'.aud-otc__warning',
		'.aud-method__grid',
		'.aud-steps',
		'.aud-faq__grid',
		'.aud-quotes',
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
		{ rootMargin: '0px 0px -10% 0px', threshold: 0.1 }
	);

	document.querySelectorAll( sectionSelectors.join( ',' ) ).forEach( function ( el ) {
		el.classList.add( 'aud-reveal' );
		observer.observe( el );
	} );
} )();
