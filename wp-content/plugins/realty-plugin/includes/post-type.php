<?php

defined( 'ABSPATH' ) || exit;

function my_realty_register_post_type() {
	$labels = array(
		'name'               => __( 'Realty', 'realty-plugin' ),
		'singular_name'      => __( 'Realty', 'realty-plugin' ),
		'add_new'            => __( 'Add New', 'realty-plugin' ),
		'add_new_item'       => __( 'Add New Realty', 'realty-plugin' ),
		'edit_item'          => __( 'Edit Realty', 'realty-plugin' ),
		'new_item'           => __( 'New Realty', 'realty-plugin' ),
		'view_item'          => __( 'View Realty', 'realty-plugin' ),
		'search_items'       => __( 'Search Realty', 'realty-plugin' ),
		'not_found'          => __( 'No realty found', 'realty-plugin' ),
		'not_found_in_trash' => __( 'No realty found in Trash', 'realty-plugin' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Realty', 'realty-plugin' ),
	);

	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 3,
		'menu_icon'     => 'dashicons-admin-home',
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'       => array( 'slug' => 'realty' ),
	);

	register_post_type( 'realty', $args );
}
add_action( 'init', 'my_realty_register_post_type' );
