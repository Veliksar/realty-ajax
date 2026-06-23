<?php

defined( 'ABSPATH' ) || exit;

function realty_register_rest_routes() {
	register_rest_route(
		'realty/v1',
		'/listings',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'realty_rest_get_listings',
			'permission_callback' => '__return_true',
			'args'                => array(
				'search_alph'           => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'search_raiting'        => array(
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
				),
				'search_raiting_order'  => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'type_build'            => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'blog_category'         => array(
					'type' => 'string',
				),
				'district'              => array(
					'type' => 'string',
				),
				'paged'                 => array(
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
					'default'           => 1,
				),
				'posts_per_page'        => array(
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
					'default'           => realty_get_posts_per_page(),
				),
			),
		)
	);
}

function realty_rest_get_listings( WP_REST_Request $request ) {
	$params = realty_get_listing_filter_params( $request->get_params() );
	$query  = realty_query_listings( $params );

	return rest_ensure_response( realty_format_listings_response( $query ) );
}

add_action( 'rest_api_init', 'realty_register_rest_routes' );
