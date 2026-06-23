<?php

defined( 'ABSPATH' ) || exit;

class trueTopPostsWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'true_top_widget',
			__( 'New posts widget', 'realty-plugin' ),
			array(
				'description' => __( 'Displays recent realty listings.', 'realty-plugin' ),
			)
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] ?? '' );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}
	}

	public function form( $instance ) {
		$title          = $instance['title'] ?? '';
		$posts_per_page = $instance['posts_per_page'] ?? '5';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'realty-plugin' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Posts count', 'realty-plugin' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="text" value="<?php echo esc_attr( $posts_per_page ); ?>" size="3" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                   = array();
		$instance['title']          = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['posts_per_page'] = is_numeric( $new_instance['posts_per_page'] ) ? $new_instance['posts_per_page'] : '5';

		return $instance;
	}
}

function true_top_posts_widget_load() {
	register_widget( 'trueTopPostsWidget' );
}
add_action( 'widgets_init', 'true_top_posts_widget_load' );
