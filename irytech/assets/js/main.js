(function () {
	'use strict';

	/* ---------- Scroll progress + header state ---------- */
	var header = document.getElementById( 'site-header' );
	var progress = document.getElementById( 'scroll-progress' );

	function onScroll() {
		var scrollTop = window.scrollY || document.documentElement.scrollTop;
		var docHeight = document.documentElement.scrollHeight - window.innerHeight;
		var pct = docHeight > 0 ? ( scrollTop / docHeight ) * 100 : 0;

		if ( progress ) {
			progress.style.width = pct + '%';
		}
		if ( header ) {
			header.classList.toggle( 'is-scrolled', scrollTop > 20 );
		}
	}
	document.addEventListener( 'scroll', onScroll, { passive: true } );
	onScroll();

	/* ---------- Mobile nav ---------- */
	var navToggle = document.getElementById( 'nav-toggle' );
	var siteNav = document.getElementById( 'site-nav' );

	if ( navToggle && siteNav ) {
		navToggle.addEventListener( 'click', function () {
			var isOpen = siteNav.classList.toggle( 'is-open' );
			navToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		siteNav.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				siteNav.classList.remove( 'is-open' );
				navToggle.setAttribute( 'aria-expanded', 'false' );
			} );
		} );
	}

	/* ---------- Active nav link on scroll ---------- */
	var navLinks = siteNav ? siteNav.querySelectorAll( '.site-nav__link' ) : [];
	var sections = Array.prototype.slice.call( navLinks )
		.map( function ( link ) { return document.querySelector( link.getAttribute( 'href' ) ); } )
		.filter( Boolean );

	if ( sections.length && 'IntersectionObserver' in window ) {
		var navObserver = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						var id = '#' + entry.target.id;
						navLinks.forEach( function ( link ) {
							link.classList.toggle( 'is-active', link.getAttribute( 'href' ) === id );
						} );
					}
				} );
			},
			{ rootMargin: '-40% 0px -50% 0px' }
		);
		sections.forEach( function ( section ) { navObserver.observe( section ); } );
	}

	/* ---------- Reveal on scroll ---------- */
	var revealEls = document.querySelectorAll( '[data-reveal]' );

	if ( 'IntersectionObserver' in window ) {
		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-visible' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
		);

		revealEls.forEach( function ( el ) {
			var delay = el.getAttribute( 'data-reveal-delay' );
			if ( delay ) {
				el.style.setProperty( '--reveal-delay', delay );
			}
			observer.observe( el );
		} );
	} else {
		revealEls.forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	}

	/* ---------- Contact form (reCAPTCHA v3 + AJAX) ---------- */
	var CONTACT_ENDPOINT = 'contact.php';
	var form = document.getElementById( 'contact-form' );
	var feedback = document.getElementById( 'contact-form-feedback' );

	function getRecaptchaToken() {
		var siteKey = window.IRYTECH_RECAPTCHA_SITE_KEY;
		var isPlaceholder = ! siteKey || siteKey.indexOf( 'RECAPTCHA_V3_SITE_KEY' ) !== -1;

		if ( isPlaceholder || typeof grecaptcha === 'undefined' ) {
			return Promise.resolve( '' );
		}

		return new Promise( function ( resolve ) {
			// Filet de sécurité : si reCAPTCHA ne répond jamais (clé invalide, réseau bloqué),
			// on n'empêche pas l'envoi du message indéfiniment.
			var settled = false;
			var timeout = setTimeout( function () {
				if ( ! settled ) { settled = true; resolve( '' ); }
			}, 6000 );

			grecaptcha.ready( function () {
				grecaptcha.execute( siteKey, { action: 'contact' } )
					.then( function ( token ) {
						if ( ! settled ) { settled = true; clearTimeout( timeout ); resolve( token ); }
					} )
					.catch( function () {
						if ( ! settled ) { settled = true; clearTimeout( timeout ); resolve( '' ); }
					} );
			} );
		} );
	}

	if ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			var submitBtn = form.querySelector( '.contact-form__submit' );
			submitBtn.classList.add( 'is-loading' );
			feedback.textContent = '';
			feedback.className = 'contact-form__feedback';

			getRecaptchaToken()
				.then( function ( token ) {
					var formData = new FormData( form );
					formData.append( 'recaptcha_token', token );

					return fetch( CONTACT_ENDPOINT, {
						method: 'POST',
						body: formData,
					} );
				} )
				.then( function ( res ) { return res.json(); } )
				.then( function ( data ) {
					submitBtn.classList.remove( 'is-loading' );
					if ( data.success ) {
						feedback.textContent = data.message;
						feedback.classList.add( 'is-success' );
						form.reset();
					} else {
						feedback.textContent = data.message;
						feedback.classList.add( 'is-error' );
					}
				} )
				.catch( function () {
					submitBtn.classList.remove( 'is-loading' );
					feedback.textContent = 'Une erreur réseau est survenue. Merci de réessayer.';
					feedback.classList.add( 'is-error' );
				} );
		} );
	}
} )();
