<?php

defined( 'ABSPATH' ) || exit;

function realty_ajax_load_posts() {
	$params = realty_get_listing_filter_params( wp_unslash( $_POST ) );
	$query  = realty_query_listings( $params );

	wp_send_json_success(
		array(
			'posts'       => realty_render_listings_html( $query ),
			'found_posts' => (int) $query->found_posts,
		)
	);
}

add_action( 'wp_ajax_ajax_handler', 'realty_ajax_load_posts' );
add_action( 'wp_ajax_nopriv_ajax_handler', 'realty_ajax_load_posts' );
