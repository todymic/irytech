<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Irytech, agence digitale basée à Madagascar : conception de sites web, applications et plateformes sur-mesure.">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="progress-bar" id="scroll-progress" aria-hidden="true"></div>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
			<img src="<?php echo esc_url( IRYTECH_URI . '/assets/img/brand/logo.png' ); ?>" alt="Irytech" class="site-logo__img">
		</a>

		<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'irytech' ); ?>">
			<a href="#realisations" class="site-nav__link">Réalisations</a>
			<a href="#expertise" class="site-nav__link">Domaine technique</a>
			<a href="#contact" class="site-nav__link">Contact</a>
			<a href="#avis" class="site-nav__link">Avis clients</a>
			<a href="#contact" class="btn btn--primary btn--sm">Démarrer un projet</a>
		</nav>

		<div class="site-header__actions">
			<button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'irytech' ); ?>">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>
