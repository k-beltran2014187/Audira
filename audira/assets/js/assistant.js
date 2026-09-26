/**
 * Audira — AI support assistant chat bubble.
 * Talks only to this site's REST endpoint; the Gemini key stays on the server.
 */
( function () {
	'use strict';

	var cfg = window.audiraAssistant;
	if ( ! cfg ) {
		return;
	}

	var STORE = 'audira-chat-v1';
	var suggestions = [
		'Which one is best for me?',
		'Which has the best sound?',
		'Which is the most discreet?',
		'Which has the longest battery?',
	];
	var history = [];
	var busy = false;

	try {
		history = JSON.parse( sessionStorage.getItem( STORE ) || '[]' );
	} catch ( e ) {
		history = [];
	}

	function save() {
		try {
			sessionStorage.setItem( STORE, JSON.stringify( history.slice( -20 ) ) );
		} catch ( e ) {}
	}

	function el( tag, cls, text ) {
		var node = document.createElement( tag );
		if ( cls ) {
			node.className = cls;
		}
		if ( text ) {
			node.textContent = text;
		}
		return node;
	}

	// Minimal, safe markdown: escape first, then links, bold and lists.
	function render( text ) {
		var html = text
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' );

		html = html.replace( /\[([^\]]+)\]\((https:\/\/[^)\s]+)\)/g, function ( m, label, url ) {
			var host = '';
			try {
				host = new URL( url.replace( /&amp;/g, '&' ) ).hostname;
			} catch ( e ) {
				return label;
			}
			var ok = /(^|\.)(amazon\.com|amzn\.to)$/.test( host ) || host === window.location.hostname;
			if ( ! ok ) {
				return label;
			}
			return '<a href="' + url + '" target="_blank" rel="sponsored nofollow noopener">' + label + '</a>';
		} );
		html = html.replace( /\*\*([^*]+)\*\*/g, '<strong>$1</strong>' );

		var out = [];
		var list = false;
		html.split( /\n/ ).forEach( function ( line ) {
			var item = line.match( /^\s*(?:[-*•]|\d+\.)\s+(.*)$/ );
			if ( item ) {
				if ( ! list ) {
					out.push( '<ul>' );
					list = true;
				}
				out.push( '<li>' + item[ 1 ] + '</li>' );
				return;
			}
			if ( list ) {
				out.push( '</ul>' );
				list = false;
			}
			if ( line.trim() ) {
				out.push( '<p>' + line + '</p>' );
			}
		} );
		if ( list ) {
			out.push( '</ul>' );
		}
		return out.join( '' );
	}

	// ----- Build the widget -----
	var root = el( 'div', 'aud-chat' );
	var toggle = el( 'button', 'aud-chat__toggle' );
	toggle.type = 'button';
	toggle.setAttribute( 'aria-expanded', 'false' );
	toggle.setAttribute( 'aria-controls', 'aud-chat-panel' );
	toggle.innerHTML = '<span class="aud-chat__toggle-icon" aria-hidden="true"></span><span class="aud-chat__toggle-label">Ask our assistant</span>';

	var panel = el( 'section', 'aud-chat__panel' );
	panel.id = 'aud-chat-panel';
	panel.setAttribute( 'role', 'dialog' );
	panel.setAttribute( 'aria-label', cfg.name );
	panel.hidden = true;

	var head = el( 'header', 'aud-chat__head' );
	var logo = el( 'img', 'aud-chat__logo' );
	logo.src = cfg.logo;
	logo.alt = '';
	var titles = el( 'div', 'aud-chat__titles' );
	titles.appendChild( el( 'p', 'aud-chat__name', cfg.name ) );
	titles.appendChild( el( 'p', 'aud-chat__status', 'Online · AI-powered' ) );
	var close = el( 'button', 'aud-chat__close' );
	close.type = 'button';
	close.setAttribute( 'aria-label', 'Close chat' );
	head.appendChild( logo );
	head.appendChild( titles );
	head.appendChild( close );

	var log = el( 'div', 'aud-chat__log' );
	log.setAttribute( 'aria-live', 'polite' );

	var chips = el( 'div', 'aud-chat__chips' );
	suggestions.forEach( function ( q ) {
		var b = el( 'button', 'aud-chat__chip', q );
		b.type = 'button';
		b.addEventListener( 'click', function () {
			send( q );
		} );
		chips.appendChild( b );
	} );

	var form = el( 'form', 'aud-chat__form' );
	var input = el( 'textarea', 'aud-chat__input' );
	input.rows = 1;
	input.maxLength = 600;
	input.placeholder = 'Type your question…';
	input.setAttribute( 'aria-label', 'Your question' );
	var submit = el( 'button', 'aud-chat__send' );
	submit.type = 'submit';
	submit.setAttribute( 'aria-label', 'Send' );
	form.appendChild( input );
	form.appendChild( submit );

	var note = el( 'p', 'aud-chat__note', 'AI answers can be wrong and are not medical advice. Please don’t share personal health details.' );

	panel.appendChild( head );
	panel.appendChild( log );
	panel.appendChild( chips );
	panel.appendChild( form );
	panel.appendChild( note );
	root.appendChild( panel );
	root.appendChild( toggle );
	document.body.appendChild( root );

	function bubble( role, text ) {
		var b = el( 'div', 'aud-chat__msg aud-chat__msg--' + role );
		if ( 'model' === role ) {
			b.innerHTML = render( text );
		} else {
			b.textContent = text;
		}
		log.appendChild( b );
		log.scrollTop = log.scrollHeight;
		return b;
	}

	function paint() {
		log.innerHTML = '';
		bubble( 'model', cfg.welcome );
		history.forEach( function ( m ) {
			bubble( m.role, m.text );
		} );
		chips.hidden = history.length > 0;
	}

	function open( state ) {
		panel.hidden = ! state;
		root.classList.toggle( 'is-open', state );
		toggle.setAttribute( 'aria-expanded', state ? 'true' : 'false' );
		if ( state ) {
			paint();
			input.focus();
		} else {
			toggle.focus();
		}
	}

	function send( text ) {
		text = ( text || '' ).trim();
		if ( ! text || busy ) {
			return;
		}
		busy = true;
		chips.hidden = true;
		history.push( { role: 'user', text: text } );
		save();
		bubble( 'user', text );
		input.value = '';
		var typing = bubble( 'model', '' );
		typing.classList.add( 'is-typing' );
		typing.innerHTML = '<span></span><span></span><span></span>';

		fetch( cfg.endpoint, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			credentials: 'same-origin',
			body: JSON.stringify( { messages: history } ),
		} )
			.then( function ( r ) {
				return r.json();
			} )
			.then( function ( data ) {
				var reply = data && data.reply ? data.reply : ( data && data.error ) || 'Sorry, something went wrong.';
				typing.classList.remove( 'is-typing' );
				typing.innerHTML = render( reply );
				if ( data && data.reply ) {
					history.push( { role: 'model', text: reply } );
				} else {
					history.pop(); // Let the visitor retry the same question.
				}
				save();
			} )
			.catch( function () {
				typing.classList.remove( 'is-typing' );
				typing.textContent = 'Connection problem. Please try again.';
				history.pop();
				save();
			} )
			.then( function () {
				busy = false;
				log.scrollTop = log.scrollHeight;
			} );
	}

	toggle.addEventListener( 'click', function () {
		open( panel.hidden );
	} );
	close.addEventListener( 'click', function () {
		open( false );
	} );
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && ! panel.hidden ) {
			open( false );
		}
	} );
	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();
		send( input.value );
	} );
	input.addEventListener( 'keydown', function ( e ) {
		if ( 'Enter' === e.key && ! e.shiftKey ) {
			e.preventDefault();
			send( input.value );
		}
	} );
} )();
