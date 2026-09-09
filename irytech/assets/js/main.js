(function () {
	'use strict';

	var CONTACT_ENDPOINT = 'contact.php';

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

	/* ---------- Portfolio gallery modal (front / back-office) ---------- */
	var galleryDataEl = document.getElementById( 'irytech-portfolio-data' );
	var projects = galleryDataEl ? JSON.parse( galleryDataEl.textContent ) : [];

	var modal = document.getElementById( 'gallery-modal' );
	var modalTitle = document.getElementById( 'gallery-modal-title' );
	var modalTrack = document.getElementById( 'gallery-modal-track' );
	var modalTabsWrap = modal ? modal.querySelector( '.gallery-modal__tabs' ) : null;
	var modalTabs = modal ? modal.querySelectorAll( '.gallery-modal__tab' ) : [];
	var activeTab = 'front';
	var activeProject = null;

	function renderTrack() {
		if ( ! activeProject || ! modalTrack ) return;

		var slides = ( activeProject.gallery && activeProject.gallery[ activeTab ] ) || [];
		modalTrack.innerHTML = '';

		if ( ! slides.length ) {
			modalTrack.innerHTML = '<p class="gallery-slide__placeholder">Aucun visuel disponible pour le moment.</p>';
			return;
		}

		slides.forEach( function ( slide ) {
			var wrap = document.createElement( 'div' );
			wrap.className = 'gallery-slide';

			var frame = document.createElement( 'div' );
			frame.className = 'gallery-slide__frame';

			if ( slide.image ) {
				var img = document.createElement( 'img' );
				img.src = 'assets/img/portfolio/' + slide.image;
				img.alt = slide.label || activeProject.name;
				frame.appendChild( img );
			} else {
				var placeholder = document.createElement( 'span' );
				placeholder.className = 'gallery-slide__placeholder';
				placeholder.textContent = 'Capture à venir';
				frame.appendChild( placeholder );
			}

			var label = document.createElement( 'div' );
			label.className = 'gallery-slide__label';
			label.textContent = slide.label || '';

			wrap.appendChild( frame );
			wrap.appendChild( label );
			modalTrack.appendChild( wrap );
		} );
	}

	function openGallery( slug ) {
		activeProject = projects.filter( function ( p ) { return p.slug === slug; } )[0];
		if ( ! activeProject || ! modal ) return;

		activeTab = 'front';
		modalTabs.forEach( function ( tab ) {
			tab.classList.toggle( 'is-active', tab.getAttribute( 'data-tab' ) === 'front' );
		} );
		if ( modalTabsWrap ) modalTabsWrap.setAttribute( 'data-active', 'front' );

		modalTitle.textContent = activeProject.name + ' — ' + activeProject.tagline;
		renderTrack();

		modal.classList.add( 'is-open' );
		modal.setAttribute( 'aria-hidden', 'false' );
		document.body.style.overflow = 'hidden';
	}

	function closeGallery() {
		if ( ! modal ) return;
		modal.classList.remove( 'is-open' );
		modal.setAttribute( 'aria-hidden', 'true' );
		document.body.style.overflow = '';
	}

	document.querySelectorAll( '.js-open-gallery' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			openGallery( btn.getAttribute( 'data-project' ) );
		} );
	} );

	document.querySelectorAll( '.js-close-gallery' ).forEach( function ( el ) {
		el.addEventListener( 'click', closeGallery );
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' ) closeGallery();
	} );

	modalTabs.forEach( function ( tab ) {
		tab.addEventListener( 'click', function () {
			activeTab = tab.getAttribute( 'data-tab' );
			modalTabs.forEach( function ( t ) {
				t.classList.toggle( 'is-active', t === tab );
			} );
			if ( modalTabsWrap ) modalTabsWrap.setAttribute( 'data-active', activeTab );
			renderTrack();
		} );
	} );

	/* ---------- Contact form (AJAX) ---------- */
	var form = document.getElementById( 'contact-form' );
	var feedback = document.getElementById( 'contact-form-feedback' );

	if ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			var submitBtn = form.querySelector( '.contact-form__submit' );

			if ( typeof grecaptcha !== 'undefined' && ! grecaptcha.getResponse() ) {
				feedback.textContent = "Merci de valider le reCAPTCHA avant d'envoyer votre message.";
				feedback.className = 'contact-form__feedback is-error';
				return;
			}

			var formData = new FormData( form );

			submitBtn.classList.add( 'is-loading' );
			feedback.textContent = '';
			feedback.className = 'contact-form__feedback';

			fetch( CONTACT_ENDPOINT, {
				method: 'POST',
				body: formData,
			} )
				.then( function ( res ) { return res.json(); } )
				.then( function ( data ) {
					submitBtn.classList.remove( 'is-loading' );
					if ( typeof grecaptcha !== 'undefined' ) {
						grecaptcha.reset();
					}
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
					feedback.textContent = "Une erreur réseau est survenue. Merci de réessayer.";
					feedback.classList.add( 'is-error' );
				} );
		} );
	}
} )();
