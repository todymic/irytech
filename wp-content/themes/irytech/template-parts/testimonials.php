<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Contenu de premier jet — avis fictifs à remplacer par de vrais retours clients.
$irytech_testimonials = array(
	array(
		'name'   => 'Njaka Rakoto',
		'role'   => 'Organisateur événementiel',
		'text'   => "L'équipe a compris nos besoins dès le premier échange. Notre plateforme tourne sans accroc.",
		'rating' => 5,
	),
	array(
		'name'   => 'Hanitra Andrianasolo',
		'role'   => 'Directrice marketing',
		'text'   => 'Un vrai sens du détail sur le design comme sur la technique. Délais respectés du début à la fin.',
		'rating' => 5,
	),
	array(
		'name'   => 'Fetra Randria',
		'role'   => 'Fondateur de start-up',
		'text'   => "Accompagnés de l'idée au produit fini, avec un back-office pensé pour notre équipe.",
		'rating' => 5,
	),
	array(
		'name'   => 'Miora Rasoanaivo',
		'role'   => 'Responsable digital',
		'text'   => 'Interface fluide, animations soignées, et une équipe toujours disponible pour les ajustements.',
		'rating' => 5,
	),
);

function irytech_testimonial_card( $t ) {
	?>
	<div class="testimonial-card">
		<div class="testimonial-card__stars" aria-hidden="true">
			<?php for ( $s = 1; $s <= 5; $s++ ) : ?>
				<span class="<?php echo $s <= $t['rating'] ? 'is-filled' : ''; ?>">★</span>
			<?php endfor; ?>
		</div>
		<p class="testimonial-card__text">&laquo;&nbsp;<?php echo esc_html( $t['text'] ); ?>&nbsp;&raquo;</p>
		<div class="testimonial-card__author">
			<span class="testimonial-card__avatar"><?php echo esc_html( mb_substr( $t['name'], 0, 1 ) ); ?></span>
			<div>
				<strong><?php echo esc_html( $t['name'] ); ?></strong>
				<span><?php echo esc_html( $t['role'] ); ?></span>
			</div>
		</div>
	</div>
	<?php
}
?>
<section class="testimonials section-band" id="avis">
	<div class="container">
		<p class="section-eyebrow reveal" data-reveal>Avis clients</p>
		<h2 class="section-title reveal" data-reveal>Ce que nos clients en disent</h2>
	</div>

	<div class="testimonial-track-wrap reveal" data-reveal data-reveal-delay="120">
		<div class="testimonial-track">
			<?php foreach ( $irytech_testimonials as $t ) : irytech_testimonial_card( $t ); endforeach; ?>
			<?php foreach ( $irytech_testimonials as $t ) : irytech_testimonial_card( $t ); endforeach; ?>
		</div>
	</div>
</section>
