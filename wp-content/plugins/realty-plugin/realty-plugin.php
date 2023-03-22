<?php
/*
Plugin Name: My Realty Plugin
Plugin URI: https://github.com/Veliksar
Description: Plugin for creating and displaying real estate. Shortcode for displaying [realty_shortcode]
Version: 1.0
Author: Andrew Veliksar
Author URI: https://github.com/Veliksar
*/

// Register custom post type
function my_realty_register_post_type() {
	$labels = array(
		'name' => 'Realty',
		'singular_name' => 'Realty',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Realty',
		'edit_item' => 'Edit Realty',
		'new_item' => 'New Realty',
		'view_item' => 'View Realty',
		'search_items' => 'Search Realty',
		'not_found' => 'No realty found',
		'not_found_in_trash' => 'No realty found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Realty'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'menu_position' => 3,
		'menu_icon' => 'dashicons-admin-home',
		'supports' => array( 'title', 'editor', 'thumbnail' ),
		'rewrite' => array( 'slug' => 'realty' )
	);

	register_post_type( 'realty', $args );
}
add_action( 'init', 'my_realty_register_post_type' );

// Register custom taxonomy
function my_realty_register_taxonomy() {
	$labels = array(
		'name' => 'District',
		'singular_name' => 'District',
		'search_items' => 'Search Districts',
		'all_items' => 'All Districts',
		'parent_item' => 'Parent District',
		'parent_item_colon' => 'Parent District:',
		'edit_item' => 'Edit District',
		'update_item' => 'Update District',
		'add_new_item' => 'Add New District',
		'new_item_name' => 'New District Name',
		'menu_name' => 'District'
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'district' ),
	);

	register_taxonomy( 'district', 'realty', $args );
}
add_action( 'init', 'my_realty_register_taxonomy' );

function realty_shortcode() {
	$arg = array(
		'post_type'       => 'realty',
		'posts_per_page'  => -1
	);
	$the_query = new WP_Query( $arg );
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			 the_title('<h3>','</h3>');
		endwhile;
	endif;
	wp_reset_query();
}
//  [realty_shortcode]
add_shortcode( 'display_realty', 'realty_shortcode' );

class trueTopPostsWidget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'true_top_widget',
			'New posts widget',
			array( 'description' => 'Description of widget' )
		);
	}

	//front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$posts_per_page = $instance['posts_per_page'];

		echo $args['before_widget'];

		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];


	}

	//back-end
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		if ( isset( $instance[ 'posts_per_page' ] ) ) {
			$posts_per_page = $instance[ 'posts_per_page' ];
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">Posts count</label>
			<input id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo ($posts_per_page) ? esc_attr( $posts_per_page ) : '5'; ?>" size="3" />
		</p>
		<?php
	}

	//save settings widget
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['posts_per_page'] = ( is_numeric( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : '5'; // по умолчанию выводятся 5 постов
		return $instance;
	}
}

//registration widget
function true_top_posts_widget_load() {
	register_widget( 'trueTopPostsWidget' );
}
add_action( 'widgets_init', 'true_top_posts_widget_load' );
