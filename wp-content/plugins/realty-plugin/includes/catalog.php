<?php

defined( 'ABSPATH' ) || exit;

function realty_render_catalog() {
	ob_start();
	get_template_part( 'template-parts/realty', 'catalog' );
	return ob_get_clean();
}
