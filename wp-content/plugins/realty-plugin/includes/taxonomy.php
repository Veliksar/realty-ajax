<?php

defined( 'ABSPATH' ) || exit;

function my_realty_register_taxonomy() {
	$labels = array(
		'name'              => __( 'District', 'realty-plugin' ),
		'singular_name'     => __( 'District', 'realty-plugin' ),
		'search_items'      => __( 'Search Districts', 'realty-plugin' ),
		'all_items'         => __( 'All Districts', 'realty-plugin' ),
		'parent_item'       => __( 'Parent District', 'realty-plugin' ),
		'parent_item_colon' => __( 'Parent District:', 'realty-plugin' ),
		'edit_item'         => __( 'Edit District', 'realty-plugin' ),
		'update_item'       => __( 'Update District', 'realty-plugin' ),
		'add_new_item'      => __( 'Add New District', 'realty-plugin' ),
		'new_item_name'     => __( 'New District Name', 'realty-plugin' ),
		'menu_name'         => __( 'District', 'realty-plugin' ),
	);

	$args = array(
		'labels'       => $labels,
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'district' ),
	);

	register_taxonomy( 'district', 'realty', $args );
}
add_action( 'init', 'my_realty_register_taxonomy' );
