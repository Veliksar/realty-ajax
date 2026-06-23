<?php

defined( 'ABSPATH' ) || exit;

function realty_get_placeholder_url( $type = 'card' ) {
	$map = array(
		'card'  => 'placeholder-card.svg',
		'hero'  => 'placeholder-hero.svg',
		'floor' => 'placeholder-floor.svg',
	);

	$file = isset( $map[ $type ] ) ? $map[ $type ] : $map['card'];

	return get_stylesheet_directory_uri() . '/assets/images/' . $file;
}

function realty_get_image_url( $url, $type = 'card' ) {
	if ( ! empty( $url ) ) {
		return esc_url( $url );
	}

	return esc_url( realty_get_placeholder_url( $type ) );
}

function realty_get_eco_label( $level ) {
	$level = (int) $level;

	if ( $level < 1 || $level > 5 ) {
		return '';
	}

	return sprintf( 'Eco %d/5', $level );
}
