<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Contenu de premier jet — logos clients fictifs à remplacer par de vrais partenaires.
$irytech_clients = array( 'NovaCorp', 'Andriana Group', 'Baobab Retail', 'Malaza Tech', 'Firaisana Bank', 'Zaza Media' );
?>
<section class="clients" id="clients">
	<div class="container">
		<p class="clients__eyebrow reveal" data-reveal>Ils nous font confiance</p>
	</div>

	<div class="clients__track-wrap">
		<div class="clients__track">
			<?php foreach ( array_merge( $irytech_clients, $irytech_clients ) as $name ) : ?>
				<span class="clients__logo"><?php echo esc_html( $name ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
