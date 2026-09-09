<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$irytech_expertise = array(
	array(
		'title' => 'Développement web',
		'desc'  => 'Sites et plateformes sur-mesure.',
		'icon'  => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
	),
	array(
		'title' => 'Applications métier',
		'desc'  => 'Outils internes & back-office.',
		'icon'  => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>',
	),
	array(
		'title' => 'Billetterie & e-commerce',
		'desc'  => 'Paiement, réservation, gestion.',
		'icon'  => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 11v2M13 17v2"/>',
	),
	array(
		'title' => 'API & intégrations',
		'desc'  => 'Connecter vos outils entre eux.',
		'icon'  => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
	),
	array(
		'title' => 'Infrastructure & déploiement',
		'desc'  => 'Docker, cloud, mise en production.',
		'icon'  => '<rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>',
	),
);
?>
<section class="expertise section-band" id="expertise">
	<div class="container">
		<p class="section-eyebrow reveal" data-reveal>Domaine technique</p>
		<h2 class="section-title reveal" data-reveal>Notre champ d'action</h2>

		<div class="expertise-grid">
			<?php foreach ( $irytech_expertise as $i => $item ) : ?>
				<div class="expertise-tile reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $i * 70 ); ?>">
					<span class="expertise-tile__icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="#c94163" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<?php echo $item['icon']; // phpcs:ignore -- SVG markup defined above, not user input. ?>
						</svg>
					</span>
					<span class="expertise-tile__title"><?php echo esc_html( $item['title'] ); ?></span>
					<span class="expertise-tile__desc"><?php echo esc_html( $item['desc'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
