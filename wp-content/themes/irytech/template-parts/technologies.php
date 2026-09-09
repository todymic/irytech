<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Répartition en 3 anneaux concentriques de 4 technologies chacun.
$irytech_tech_rings = array(
	array(
		'ring'          => 1,
		'radius_ratio'  => 0.179,
		'duration'      => '26s',
		'angle_offset'  => 0,
		'items'         => array(
			array( 'name' => 'PHP', 'file' => 'php.svg' ),
			array( 'name' => 'React', 'file' => 'react.svg' ),
			array( 'name' => 'Node.js', 'file' => 'nodedotjs.svg' ),
			array( 'name' => 'MySQL', 'file' => 'mysql.svg' ),
		),
	),
	array(
		'ring'          => 2,
		'radius_ratio'  => 0.321,
		'duration'      => '38s',
		'angle_offset'  => 45,
		'items'         => array(
			array( 'name' => 'Symfony', 'file' => 'symfony.svg' ),
			array( 'name' => 'Vue.js', 'file' => 'vuedotjs.svg' ),
			array( 'name' => 'TypeScript', 'file' => 'typescript.svg' ),
			array( 'name' => 'PostgreSQL', 'file' => 'postgresql.svg' ),
		),
	),
	array(
		'ring'          => 3,
		'radius_ratio'  => 0.464,
		'duration'      => '50s',
		'angle_offset'  => 22.5,
		'items'         => array(
			array( 'name' => 'WordPress', 'file' => 'wordpress.svg' ),
			array( 'name' => 'Angular', 'file' => 'angular.svg' ),
			array( 'name' => 'Java', 'file' => 'openjdk.svg' ),
			array( 'name' => 'Docker', 'file' => 'docker.svg' ),
		),
	),
);
?>
<section class="technologies" id="technologies">
	<div class="container">
		<p class="section-eyebrow reveal" data-reveal>Notre boîte à outils</p>
		<h2 class="section-title reveal" data-reveal>Technologies maîtrisées</h2>
		<p class="section-subtitle reveal" data-reveal>
			Nous choisissons les technologies les plus adaptées à chaque projet, du prototype à la mise à l'échelle.
		</p>

		<div class="tech-orbit reveal" data-reveal data-reveal-delay="120">
			<div class="tech-orbit__hub">
				<?php get_template_part( 'template-parts/logo-mark' ); ?>
			</div>

			<?php foreach ( $irytech_tech_rings as $ring ) : ?>
				<div
					class="tech-orbit__ring tech-orbit__ring--<?php echo esc_attr( $ring['ring'] ); ?>"
					style="--ring-duration: <?php echo esc_attr( $ring['duration'] ); ?>; --radius-ratio: <?php echo esc_attr( $ring['radius_ratio'] ); ?>;"
				>
					<?php
					$count = count( $ring['items'] );
					foreach ( $ring['items'] as $i => $tech ) :
						$angle = $ring['angle_offset'] + ( 360 / $count ) * $i;
						?>
						<div class="tech-orbit__anchor" style="--angle: <?php echo esc_attr( $angle ); ?>deg;">
							<div class="tech-orbit__counter">
								<span class="tech-orbit__icon" tabindex="0">
									<img src="<?php echo esc_url( IRYTECH_URI . '/assets/img/tech/' . $tech['file'] ); ?>" alt="<?php echo esc_attr( $tech['name'] ); ?>" width="24" height="24" loading="lazy">
									<span class="tech-orbit__tooltip"><?php echo esc_html( $tech['name'] ); ?></span>
								</span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
