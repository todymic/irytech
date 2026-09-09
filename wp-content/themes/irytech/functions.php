<?php
/**
 * Fonctions et définitions du thème Irytech.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IRYTECH_VERSION', '0.1.0' );
define( 'IRYTECH_DIR', get_template_directory() );
define( 'IRYTECH_URI', get_template_directory_uri() );

// Clés de test officielles Google (valident toujours en local) — à remplacer par vos vraies clés avant la mise en production.
// https://developers.google.com/recaptcha/docs/faq#id-like-to-run-automated-tests-with-recaptcha-v2-what-should-i-do
define( 'IRYTECH_RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' );
define( 'IRYTECH_RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe' );

function irytech_setup() {
	load_theme_textdomain( 'irytech', IRYTECH_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'irytech' ),
		)
	);
}
add_action( 'after_setup_theme', 'irytech_setup' );

// Le plugin "Themesflat Addons for Elementor" remplace le header/footer du thème actif
// par ses propres templates Elementor globaux. On garde notre propre header/footer.
add_filter( 'tf_header_enabled', '__return_false' );
add_filter( 'tf_footer_enabled', '__return_false' );

function irytech_enqueue_assets() {
	wp_enqueue_style(
		'irytech-fonts',
		'https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'irytech-main',
		IRYTECH_URI . '/assets/css/main.css',
		array(),
		filemtime( IRYTECH_DIR . '/assets/css/main.css' )
	);

	wp_enqueue_script(
		'google-recaptcha',
		'https://www.google.com/recaptcha/api.js',
		array(),
		null,
		true
	);

	wp_enqueue_script(
		'irytech-main',
		IRYTECH_URI . '/assets/js/main.js',
		array(),
		filemtime( IRYTECH_DIR . '/assets/js/main.js' ),
		true
	);

	wp_localize_script(
		'irytech-main',
		'irytechData',
		array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'irytech_contact' ),
			'themeUrl'      => IRYTECH_URI,
			'recaptchaSite' => IRYTECH_RECAPTCHA_SITE_KEY,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'irytech_enqueue_assets' );

require IRYTECH_DIR . '/inc/portfolio-data.php';
require IRYTECH_DIR . '/inc/contact-form.php';
