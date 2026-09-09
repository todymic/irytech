<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$irytech_projects = irytech_portfolio_items();

function irytech_project_card( $project, $i, $feature = false ) {
	$front  = isset( $project['gallery']['front'] ) ? $project['gallery']['front'] : array();
	$bo     = isset( $project['gallery']['bo'] ) ? $project['gallery']['bo'] : array();
	$slides = array_merge( $front, $bo );
	$total  = count( $slides );
	$shown  = array_slice( $slides, 0, 3 );
	?>
	<article
		class="project-card <?php echo $feature ? 'project-card--feature' : ''; ?> reveal"
		data-reveal
		data-reveal-delay="<?php echo esc_attr( $i * 90 ); ?>"
		style="--project-accent: <?php echo esc_attr( $project['accent'] ); ?>;"
	>
		<div class="project-card__cover"></div>

		<div class="project-card__body">
			<h3 class="project-card__title"><?php echo esc_html( $project['name'] ); ?></h3>
			<p class="project-card__tagline"><?php echo esc_html( $project['tagline'] ); ?></p>

			<?php if ( $total > 0 ) : ?>
				<div class="project-card__gallery-row">
					<div class="project-card__gallery-preview">
						<?php foreach ( $shown as $slide ) : ?>
							<?php if ( ! empty( $slide['image'] ) ) : ?>
								<img class="project-card__thumb" src="<?php echo esc_url( IRYTECH_URI . '/assets/img/portfolio/' . $slide['image'] ); ?>" alt="">
							<?php else : ?>
								<span class="project-card__thumb"></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<span class="project-card__gallery-count">
						<?php echo $total > 3 ? '+' . esc_html( $total - 3 ) . ' captures' : esc_html( $total ) . ' capture' . ( $total > 1 ? 's' : '' ); ?>
					</span>
				</div>
			<?php endif; ?>

			<button type="button" class="project-card__link js-open-gallery" data-project="<?php echo esc_attr( $project['slug'] ); ?>">
				Voir la galerie →
			</button>
		</div>
	</article>
	<?php
}
?>
<section class="portfolio section-band" id="realisations">
	<div class="container">
		<p class="section-eyebrow reveal" data-reveal>Réalisations</p>
		<h2 class="section-title reveal" data-reveal>Ce que nous avons construit</h2>

		<div class="portfolio-grid">
			<?php irytech_project_card( $irytech_projects[0], 0, true ); ?>

			<div class="portfolio-grid__row">
				<?php foreach ( array_slice( $irytech_projects, 1 ) as $i => $project ) : ?>
					<?php irytech_project_card( $project, $i + 1 ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<!-- Lightbox galerie front / back-office -->
	<div class="gallery-modal" id="gallery-modal" aria-hidden="true">
		<div class="gallery-modal__backdrop js-close-gallery"></div>
		<div class="gallery-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="gallery-modal-title">
			<button type="button" class="gallery-modal__close js-close-gallery" aria-label="Fermer">&times;</button>

			<h3 class="gallery-modal__title" id="gallery-modal-title"></h3>

			<div class="gallery-modal__tabs" data-active="front">
				<span class="gallery-modal__tab-thumb" aria-hidden="true"></span>
				<button type="button" class="gallery-modal__tab is-active" data-tab="front">Front-end</button>
				<button type="button" class="gallery-modal__tab" data-tab="bo">Back-office</button>
			</div>

			<div class="gallery-modal__track" id="gallery-modal-track"></div>
		</div>
	</div>

	<script type="application/json" id="irytech-portfolio-data"><?php echo wp_json_encode( $irytech_projects ); ?></script>
</section>
