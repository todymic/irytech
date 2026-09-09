<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer class="site-footer">
		<div class="container site-footer__inner">
			<span class="site-logo">
				<img src="<?php echo esc_url( IRYTECH_URI . '/assets/img/brand/logo.png' ); ?>" alt="Irytech" class="site-logo__img">
			</span>

			<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Navigation pied de page', 'irytech' ); ?>">
				<a href="#realisations">Réalisations</a>
				<a href="#expertise">Domaine technique</a>
				<a href="#contact">Contact</a>
				<a href="#avis">Avis clients</a>
			</nav>
		</div>

		<div class="container">
			<div class="site-footer__divider"></div>
			<div class="site-footer__bottom">
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Irytech. Tous droits réservés.</p>
				<a href="mailto:gtody.rabekoto@gmail.com">gtody.rabekoto@gmail.com</a>
			</div>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
