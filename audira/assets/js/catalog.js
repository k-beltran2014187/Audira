/**
 * Audira — catalog filters, Hearing Aid Finder and product gallery.
 * Everything runs in the visitor's browser; no answers are sent anywhere.
 */
( function () {
	'use strict';

	/* ---------------- Product gallery ---------------- */
	document.querySelectorAll( '[data-gallery]' ).forEach( function ( g ) {
		var main = g.querySelector( '[data-main]' );
		g.querySelectorAll( '.aud-gallery__thumbs button' ).forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				main.src = b.getAttribute( 'data-src' );
				g.querySelectorAll( '.aud-gallery__thumbs button' ).forEach( function ( o ) {
					o.classList.toggle( 'is-active', o === b );
				} );
			} );
		} );
	} );

	/* ---------------- Catalog filters & sort ---------------- */
	var grid = document.querySelector( '[data-grid]' );
	if ( grid ) {
		var empty = document.querySelector( '.aud-empty' );
		var filterButtons = document.querySelectorAll( '.aud-filters button' );

		filterButtons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var f = btn.getAttribute( 'data-filter' );
				filterButtons.forEach( function ( b ) {
					b.classList.toggle( 'is-active', b === btn );
				} );
				var shown = 0;
				grid.querySelectorAll( '.aud-card' ).forEach( function ( card ) {
					var ok = true;
					if ( 'all' !== f ) {
						var parts = f.split( ':' );
						if ( 'style' === parts[ 0 ] ) {
							ok = card.dataset.style === parts[ 1 ];
						} else if ( 'feature' === parts[ 0 ] ) {
							ok = ( ' ' + card.dataset.features + ' ' ).indexOf( ' ' + parts[ 1 ] + ' ' ) > -1;
						} else if ( 'tier' === parts[ 0 ] ) {
							ok = card.dataset.tier === parts[ 1 ];
						}
					}
					card.hidden = ! ok;
					shown += ok ? 1 : 0;
				} );
				empty.hidden = shown > 0;
			} );
		} );

		var sort = document.querySelector( '[data-sort]' );
		if ( sort ) {
			sort.addEventListener( 'change', function () {
				var cards = Array.prototype.slice.call( grid.querySelectorAll( '.aud-card' ) );
				cards.sort( function ( a, b ) {
					if ( 'score' === sort.value ) {
						return parseFloat( b.dataset.score || 0 ) - parseFloat( a.dataset.score || 0 );
					}
					return parseInt( a.dataset.rank, 10 ) - parseInt( b.dataset.rank, 10 );
				} );
				cards.forEach( function ( c ) {
					grid.appendChild( c );
				} );
			} );
		}
	}

	/* ---------------- Hearing Aid Finder ---------------- */
	var form = document.querySelector( '.aud-finder__form' );
	var dataEl = document.getElementById( 'aud-products-data' );
	if ( ! form || ! dataEl ) {
		return;
	}
	var products = JSON.parse( dataEl.textContent || '[]' );
	var results = document.querySelector( '.aud-finder__results' );

	// "Pick up to 2" priorities.
	form.querySelectorAll( '[data-max]' ).forEach( function ( group ) {
		var max = parseInt( group.getAttribute( 'data-max' ), 10 );
		group.addEventListener( 'change', function ( e ) {
			var checked = group.querySelectorAll( 'input:checked' );
			if ( checked.length > max ) {
				e.target.checked = false;
			}
		} );
	} );

	function esc( s ) {
		return String( s ).replace( /[&<>"']/g, function ( c ) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ];
		} );
	}

	function has( p, f ) {
		return p.features.indexOf( f ) > -1;
	}

	function values( name ) {
		return Array.prototype.map.call( form.querySelectorAll( 'input[name="' + name + '"]:checked' ), function ( i ) {
			return i.value;
		} );
	}

	function score( p, a ) {
		var s = p.score * 4;
		var why = [];
		var add = function ( n, reason ) {
			s += n;
			if ( reason && n > 0 && why.indexOf( reason ) < 0 ) {
				why.push( reason );
			}
		};

		// Hearing level.
		if ( 'mild' === a.loss || 'moderate' === a.loss ) {
			add( p.loss.indexOf( a.loss ) > -1 ? 12 : -10, 'Suited to ' + a.loss + ' hearing loss' );
		} else if ( 'severe' === a.loss ) {
			add( p.loss.indexOf( 'moderate' ) > -1 ? 6 : 0 );
			add( has( p, 'audiology' ) ? 18 : 0, 'Includes support from hearing professionals' );
		}

		// Conditions.
		if ( a.cond.indexOf( 'tinnitus' ) > -1 ) {
			add( has( p, 'tinnitus' ) ? 18 : 0, 'Built-in tinnitus relief' );
		}
		if ( a.cond.indexOf( 'dexterity' ) > -1 || '75' === a.age ) {
			add( has( p, 'rechargeable' ) ? 8 : -4, 'Rechargeable — no tiny batteries to change' );
			add( 'iic' === p.style ? -16 : 0 );
			add( 'bte' === p.style ? 6 : 0, 'Larger, easier-to-handle design' );
		}
		if ( a.cond.indexOf( 'glasses' ) > -1 ) {
			add( 'iic' === p.style || 'ite' === p.style ? 6 : 0, 'Nothing behind the ear — easy with glasses' );
		}
		if ( a.cond.indexOf( 'phone' ) > -1 ) {
			add( has( p, 'bluetooth' ) ? 12 : 0, 'Streams phone calls to your ears' );
		}

		// Priorities.
		a.prio.forEach( function ( pr ) {
			if ( 'sound' === pr ) {
				add( p.score * 3 + ( has( p, 'noise' ) ? 8 : 0 ), 'Top-rated sound and speech clarity' );
			}
			if ( 'discreet' === pr ) {
				add( has( p, 'discreet' ) || 'iic' === p.style ? 20 : ( 'bte' === p.style ? -8 : 0 ), 'Very discreet to wear' );
			}
			if ( 'battery' === pr ) {
				add( ( has( p, 'long_battery' ) ? 18 : 0 ) + ( has( p, 'rechargeable' ) ? 4 : 0 ), 'Long battery life' );
			}
			if ( 'streaming' === pr ) {
				add( has( p, 'bluetooth' ) ? 18 : -6, 'Bluetooth for calls, TV and music' );
			}
			if ( 'support' === pr ) {
				add( has( p, 'audiology' ) ? 24 : 0, 'Expert support after you buy' );
			}
			if ( 'price' === pr ) {
				add( 'budget' === p.tier ? 18 : ( 'premium' === p.tier ? -12 : 0 ), 'Great price for what you get' );
			}
		} );

		// Budget.
		var tierFit = { budget: { budget: 14, mid: -8, premium: -22 }, mid: { budget: 6, mid: 14, premium: -10 }, premium: { budget: 0, mid: 6, premium: 14 }, any: { budget: 0, mid: 0, premium: 6 } };
		add( ( tierFit[ a.budget ] || tierFit.any )[ p.tier ] || 0, 'Fits your budget' );

		return { p: p, s: s, why: why.slice( 0, 3 ) };
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();
		var a = {
			age: values( 'age' )[ 0 ] || '60-74',
			loss: values( 'loss' )[ 0 ] || 'unsure',
			cond: values( 'cond' ),
			prio: values( 'prio' ),
			budget: values( 'budget' )[ 0 ] || 'any',
		};
		var html = '';

		if ( 'u18' === a.age ) {
			results.innerHTML = '<div class="aud-alert"><strong>OTC hearing aids are for adults 18 and older.</strong> For a child or teen, please see a pediatric audiologist — they can test hearing and fit the right device.</div>';
			results.hidden = false;
			results.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			return;
		}
		if ( a.cond.indexOf( 'redflag' ) > -1 ) {
			html += '<div class="aud-alert"><strong>Please see a doctor first.</strong> Sudden hearing changes, hearing loss in one ear, ear pain or dizziness need a medical check before using any hearing aid.</div>';
		}
		if ( 'severe' === a.loss ) {
			html += '<div class="aud-alert aud-alert--info"><strong>OTC hearing aids are designed for mild to moderate loss.</strong> For severe difficulty, a prescription hearing aid fitted by an audiologist is usually the better choice. If you still want to try OTC, these are the closest matches.</div>';
		}
		if ( 'unsure' === a.loss ) {
			html += '<div class="aud-alert aud-alert--info"><strong>Not sure how much hearing you’ve lost?</strong> A free online hearing test or a visit to a hearing clinic will help. Meanwhile, here are versatile picks for mild to moderate loss.</div>';
		}

		var ranked = products.map( function ( p ) {
			return score( p, a );
		} ).sort( function ( x, y ) {
			return y.s - x.s;
		} ).slice( 0, 3 );

		var labels = [ 'Best match', 'Great alternative', 'Also worth a look' ];
		html += '<h3 class="aud-finder__rtitle">Your top matches</h3><div class="aud-matches">';
		ranked.forEach( function ( r, i ) {
			var why = r.why.length ? r.why : [ r.p.bestFor ];
			html += '<article class="aud-match' + ( 0 === i ? ' aud-match--best' : '' ) + '">' +
				'<a class="aud-match__img' + ( r.p.photo ? ' aud-match__img--photo' : '' ) + '" href="' + esc( r.p.link ) + '"><img src="' + esc( r.p.image ) + '" alt=""></a>' +
				'<div class="aud-match__body"><span class="aud-badge">' + labels[ i ] + '</span>' +
				'<h4><a href="' + esc( r.p.link ) + '">' + esc( r.p.title ) + '</a></h4>' +
				'<ul class="aud-checklist">' + why.map( function ( w ) {
					return '<li>' + esc( w ) + '</li>';
				} ).join( '' ) + '</ul>' +
				'<div class="aud-card__actions"><a class="aud-cta aud-cta--ghost" href="' + esc( r.p.link ) + '">View details</a>' +
				'<a class="aud-cta" href="' + esc( r.p.buy ) + '" target="_blank" rel="sponsored nofollow noopener">Check price</a></div></div></article>';
		} );
		html += '</div><p class="aud-finder__fine">Suggestions are general guidance based on your answers, not a medical recommendation. <button type="button" class="aud-linkbtn" data-restart>Start over</button></p>';

		results.innerHTML = html;
		results.hidden = false;
		form.hidden = true;
		results.querySelector( '[data-restart]' ).addEventListener( 'click', function () {
			results.hidden = true;
			form.hidden = false;
			form.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		} );
		results.scrollIntoView( { behavior: 'smooth', block: 'start' } );
	} );
} )();
