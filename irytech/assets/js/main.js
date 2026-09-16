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
} )();
