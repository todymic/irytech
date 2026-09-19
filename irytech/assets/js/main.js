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

	/* ---------- Contact form (validation + reCAPTCHA v3 + AJAX) ---------- */
	var CONTACT_ENDPOINT = 'contact.php';
	var form = document.getElementById( 'contact-form' );
	var feedback = document.getElementById( 'contact-form-feedback' );
	var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	function setFieldError( input, message ) {
		var row = input.closest( '.contact-form__row' );
		var errorEl = document.getElementById( input.id + '-error' );
		if ( message ) {
			if ( row ) row.classList.add( 'has-error' );
			if ( errorEl ) errorEl.textContent = message;
		} else {
			if ( row ) row.classList.remove( 'has-error' );
			if ( errorEl ) errorEl.textContent = '';
		}
	}

	function validateContactForm() {
		if ( ! form ) return true;

		var nameEl = form.elements.name;
		var emailEl = form.elements.email;
		var messageEl = form.elements.message;
		var firstInvalid = null;
		var isValid = true;

		var name = nameEl.value.trim();
		if ( ! name ) {
			setFieldError( nameEl, 'Merci d’indiquer votre nom.' );
			isValid = false;
			firstInvalid = firstInvalid || nameEl;
		} else {
			setFieldError( nameEl, '' );
		}

		var email = emailEl.value.trim();
		if ( ! email ) {
			setFieldError( emailEl, 'Merci d’indiquer votre e-mail.' );
			isValid = false;
			firstInvalid = firstInvalid || emailEl;
		} else if ( ! EMAIL_PATTERN.test( email ) ) {
			setFieldError( emailEl, 'Cette adresse e-mail n’est pas valide.' );
			isValid = false;
			firstInvalid = firstInvalid || emailEl;
		} else {
			setFieldError( emailEl, '' );
		}

		var message = messageEl.value.trim();
		if ( ! message ) {
			setFieldError( messageEl, 'Merci d’écrire un message.' );
			isValid = false;
			firstInvalid = firstInvalid || messageEl;
		} else {
			setFieldError( messageEl, '' );
		}

		if ( firstInvalid ) {
			firstInvalid.focus();
		}

		return isValid;
	}

	if ( form ) {
		[ 'name', 'email', 'message' ].forEach( function ( fieldName ) {
			var el = form.elements[ fieldName ];
			el.addEventListener( 'input', function () { setFieldError( el, '' ); } );
		} );
	}

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

			feedback.textContent = '';
			feedback.className = 'contact-form__feedback';

			if ( ! validateContactForm() ) {
				feedback.textContent = 'Merci de corriger les champs indiqués ci-dessus avant d’envoyer votre message.';
				feedback.classList.add( 'is-error' );
				return;
			}

			var submitBtn = form.querySelector( '.contact-form__submit' );
			submitBtn.classList.add( 'is-loading' );

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
