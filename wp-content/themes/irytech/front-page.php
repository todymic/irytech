<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/portfolio' );
get_template_part( 'template-parts/expertise' );
get_template_part( 'template-parts/contact' );
get_template_part( 'template-parts/testimonials' );
get_template_part( 'template-parts/clients' );
get_template_part( 'template-parts/location' );

get_footer();
