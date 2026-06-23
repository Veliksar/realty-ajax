<?php

defined( 'ABSPATH' ) || exit;

function realty_get_posts_per_page() {
	return (int) apply_filters( 'realty_posts_per_page', 12 );
}

function realty_get_building_type_slug( $value ) {
	$value = sanitize_text_field( $value );

	if ( $value === '' ) {
		return '';
	}

	$slugs = array( 'panel', 'brick', 'foam' );

	if ( in_array( $value, $slugs, true ) ) {
		return $value;
	}

	$labels_to_slugs = array(
		'Panel'      => 'panel',
		'Brick'      => 'brick',
		'Foam Block' => 'foam',
	);

	return isset( $labels_to_slugs[ $value ] ) ? $labels_to_slugs[ $value ] : '';
}

function realty_get_building_type_label( $slug ) {
	$slug = sanitize_key( $slug );
	$labels = array(
		'panel' => 'Panel',
		'brick' => 'Brick',
		'foam'  => 'Foam Block',
	);

	return isset( $labels[ $slug ] ) ? $labels[ $slug ] : '';
}

function realty_get_district_slugs() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'district',
			'hide_empty' => false,
			'fields'     => 'id=>slug',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	return array_values( $terms );
}

function realty_sanitize_district_slugs( $value ) {
	$allowed = realty_get_district_slugs();
	$slugs   = array();

	if ( is_array( $value ) ) {
		$slugs = $value;
	} elseif ( is_string( $value ) && $value !== '' ) {
		$slugs = array_map( 'trim', explode( ',', $value ) );
	}

	$slugs = array_map( 'sanitize_title', $slugs );
	$slugs = array_filter( $slugs );

	return array_values( array_intersect( $slugs, $allowed ) );
}

function realty_get_listing_filter_params( $source ) {
	$district_raw = $source['blog_category'] ?? $source['district'] ?? '';

	$params = array(
		'search_alph'           => isset( $source['search_alph'] ) ? sanitize_text_field( $source['search_alph'] ) : '',
		'search_raiting'        => isset( $source['search_raiting'] ) ? absint( $source['search_raiting'] ) : 0,
		'search_raiting_order'  => isset( $source['search_raiting_order'] ) ? strtoupper( sanitize_text_field( $source['search_raiting_order'] ) ) : '',
		'type_build'            => isset( $source['type_build'] ) ? sanitize_text_field( $source['type_build'] ) : '',
		'paged'                 => isset( $source['paged'] ) ? absint( $source['paged'] ) : 1,
		'posts_per_page'        => isset( $source['posts_per_page'] ) ? absint( $source['posts_per_page'] ) : realty_get_posts_per_page(),
		'district'              => realty_sanitize_district_slugs( $district_raw ),
	);

	if ( $params['search_alph'] !== '' && ! preg_match( '/^[A-Z]$/', $params['search_alph'] ) ) {
		$params['search_alph'] = '';
	}

	if ( $params['search_raiting'] < 1 || $params['search_raiting'] > 5 ) {
		$params['search_raiting'] = 0;
	}

	if ( ! in_array( $params['search_raiting_order'], array( 'ASC', 'DESC' ), true ) ) {
		$params['search_raiting_order'] = '';
	}

	$params['type_build'] = realty_get_building_type_slug( $params['type_build'] );

	if ( $params['paged'] < 1 ) {
		$params['paged'] = 1;
	}

	if ( $params['posts_per_page'] < 1 ) {
		$params['posts_per_page'] = realty_get_posts_per_page();
	}

	if ( $params['posts_per_page'] > 50 ) {
		$params['posts_per_page'] = 50;
	}

	return $params;
}

function realty_build_listings_query_args( $params ) {
	$args = array(
		'post_type'      => 'realty',
		'post_status'    => 'publish',
		'posts_per_page' => $params['posts_per_page'],
		'paged'          => $params['paged'],
	);

	$meta_clauses = array();

	if ( $params['search_raiting'] > 0 ) {
		$compare = '=';

		if ( $params['search_raiting_order'] === 'DESC' ) {
			$compare = '<=';
		} elseif ( $params['search_raiting_order'] === 'ASC' ) {
			$compare = '>=';
		}

		$meta_clauses[] = array(
			'key'     => 'ecological',
			'value'   => $params['search_raiting'],
			'compare' => $compare,
			'type'    => 'NUMERIC',
		);

		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = 'ecological';

		if ( $params['search_raiting_order'] !== '' ) {
			$args['order'] = $params['search_raiting_order'];
		}
	}

	if ( $params['type_build'] !== '' ) {
		$meta_clauses[] = array(
			'key'     => 'building_type',
			'value'   => $params['type_build'],
			'compare' => '=',
		);
	}

	if ( ! empty( $meta_clauses ) ) {
		$args['meta_query'] = array_merge(
			array( 'relation' => 'AND' ),
			$meta_clauses
		);
	}

	if ( $params['search_alph'] !== '' ) {
		$args['realty_title_starts_with'] = $params['search_alph'];
	}

	if ( ! empty( $params['district'] ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'district',
				'field'    => 'slug',
				'terms'    => $params['district'],
			),
		);
	}

	return $args;
}

function realty_posts_where_title_starts_with( $where, $query ) {
	$letter = $query->get( 'realty_title_starts_with' );

	if ( empty( $letter ) || $query->get( 'post_type' ) !== 'realty' ) {
		return $where;
	}

	global $wpdb;

	$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", $letter . '%' );

	return $where;
}

function realty_query_listings( $params ) {
	add_filter( 'posts_where', 'realty_posts_where_title_starts_with', 10, 2 );

	$query = new WP_Query( realty_build_listings_query_args( $params ) );

	remove_filter( 'posts_where', 'realty_posts_where_title_starts_with', 10, 2 );

	return $query;
}

function realty_format_listing_item( $post_id ) {
	$districts = get_the_terms( $post_id, 'district' );
	$district_names = array();

	if ( $districts && ! is_wp_error( $districts ) ) {
		$district_names = wp_list_pluck( $districts, 'name' );
	}

	$building_slug = get_field( 'building_type', $post_id );

	return array(
		'id'                   => $post_id,
		'title'                => get_the_title( $post_id ),
		'permalink'            => get_permalink( $post_id ),
		'house_title'          => get_field( 'house_title', $post_id ),
		'house_image'          => get_field( 'house_image', $post_id ),
		'ecological'           => (int) get_field( 'ecological', $post_id ),
		'building_type'        => $building_slug,
		'building_type_label'  => realty_get_building_type_label( $building_slug ),
		'number_of_floors'     => get_field( 'number_of_floors', $post_id ),
		'districts'            => $district_names,
	);
}
