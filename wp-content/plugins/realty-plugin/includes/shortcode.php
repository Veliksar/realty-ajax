<?php

defined( 'ABSPATH' ) || exit;

function display_realty_shortcode( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'layout'     => 'catalog',
			'limit'      => -1,
			'pagination' => 'true',
			'per_slide'  => 3,
		),
		$atts,
		'display_realty'
	);

	ob_start();

	echo '<div class="re-shortcode-wrap">';

	if ( 'catalog' === $atts['layout'] ) {
		echo realty_render_catalog();
	} elseif ( 'carousel' === $atts['layout'] ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'realty',
				'posts_per_page' => (int) $atts['limit'],
			)
		);

		echo realty_render_listings_carousel(
			$query,
			array(
				'per_slide' => (int) $atts['per_slide'],
			)
		);
	} else {
		$query = new WP_Query(
			array(
				'post_type'      => 'realty',
				'posts_per_page' => (int) $atts['limit'],
			)
		);

		$show_pagination = filter_var( $atts['pagination'], FILTER_VALIDATE_BOOLEAN );

		echo realty_render_listings_html(
			$query,
			array(
				'pagination' => $show_pagination,
			)
		);
	}

	echo '</div>';

	return ob_get_clean();
}

add_shortcode( 'display_realty', 'display_realty_shortcode' );
add_shortcode( 'realty_shortcode', 'display_realty_shortcode' );
