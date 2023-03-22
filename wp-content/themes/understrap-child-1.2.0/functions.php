<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

function foundation_pagination( $query = '' ) {
	if ( empty( $query ) ) {
		global $wp_query;
		$query = $wp_query;
		if (is_front_page()) {
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		} else {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
	} else {
		$paged = $query->query['paged'];
	}

	$big = 999999999;

	$links = paginate_links( array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'prev_next' => true,
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'current'   => max( 1, $paged ),
		'total'     => $query->max_num_pages,
		'type'      => 'list'
	) );

	$pagination = str_replace( 'page-numbers', 'pagination', $links );

	echo $pagination;
}


/**
 * AJAX
 */
add_action('wp_head','active_ajax_ajax_url');
function active_ajax_ajax_url() { ?>
	<script type="text/javascript">
        var ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
<?php }

function load_posts() {
	$result_array = array();
//    $result_array['current_page'] = (int) $_POST['paged'] + 1;
//    $_POST['blog_category'][1];

	$blog_cat_param = $_POST['blog_category'] ?? ''; //form fields
	$blog_search_alph = $_POST['search_alph'] ?? '';
	$blog_search_raiting = $_POST['search_raiting'] ?? '';
	$blog_search_raiting_order = $_POST['search_raiting_order'] ?? '';
	$type_build = $_POST['type_build'] ?? '';
	$paged = $_POST['paged'] ?? '';

	if (isset($blog_search_raiting) && !empty($blog_search_raiting) && $blog_search_raiting >= 1 && isset($blog_search_raiting_order) && !empty($blog_search_raiting_order)) {
		if ($blog_search_raiting_order == 'DESC') {
			$compare = '<=';
		} else if ($blog_search_raiting_order == 'ASC') {
			$compare = '>=';
		} else {
			$compare = '=';
		}
	}

	$args = array(
		'post_type' => 'realty',
		'posts_per_page' => 5,
		'paged' => $paged,
        'meta_query' => array(
	        'key' => 'ecological',
	        'value' => $blog_search_raiting,
	        'compare' => $compare,
        ),
		'orderby' => 'meta_value_num',
	);

	if (isset($blog_search_raiting_order) && !empty($blog_search_raiting_order)) {
		$args['order'] = $blog_search_raiting_order;
	}

	if ( $blog_search_alph !== '') {
		$args['starts_with'] = $blog_search_alph;
	}

	if ( $type_build !== '') {
		$args['meta_query'][] = array(
			'value' => $type_build,
		);
	}

	if ($blog_cat_param !== '') {
		$args['tax_query'][] = array(
			'taxonomy' => 'district',
			'field' => 'slug',
			'terms' => $blog_cat_param
		);
	}

	if (isset($args['tax_query']) && !empty($args['tax_query']) && count($args['tax_query']) > 1) {
		$args['tax_query']['relation'] = 'AND';
	}

	$query = new WP_Query ($args);
	$i = 0;
	ob_start();
	if ($query->have_posts()) {
		echo '<div class="row">';
		while ($query->have_posts()) {
			$query->the_post();
            get_template_part('loop-templates/content', 'ajax');
		}
		echo '</div>';
		foundation_pagination($query);
		$result_array['posts'] = ob_get_clean();
	} else {
		// no posts found
	}
	$result_array['found_posts'] = $query->found_posts;
	/* Restore original Post Data */
	wp_reset_postdata();



	wp_send_json_success($result_array);
	wp_die();
}

add_action('wp_ajax_ajax_handler','load_posts');
add_action('wp_ajax_nopriv_ajax_handler','load_posts');
