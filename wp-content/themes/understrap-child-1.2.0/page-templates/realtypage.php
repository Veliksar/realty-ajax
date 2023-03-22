<?php
/**
 * Template Name: Realty Page
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );

if ( is_front_page() ) {
	get_template_part( 'global-templates/hero' );
}

$wrapper_id = 'full-width-page-wrapper';
if ( is_page_template( 'page-templates/no-title.php' ) ) {
	$wrapper_id = 'no-title-page-wrapper';
}
?>

    <div class="wrapper"
         id="<?php echo $wrapper_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ok. ?>">

        <div class="<?php echo esc_attr( $container ); ?>" id="content">

            <div class="row">

                <div class="col-md-12 content-area" id="primary">

                    <main class="site-main" id="main" role="main">

                        <h3>Realty page template</h3>
                        <section class="blog">
                            <div class="row">
                                <div class="filter col-12">
                                    <h2 style="text-decoration: underline;">Filter</h2>
                                    <form action="." class="js-ajax-filter">
										<?php $blog_tax = get_terms( 'district', array( 'hide_empty' => false ) ); ?>
										<?php $blog_cat_param = $_GET['blog_category'] ?? ''; ?>
										<?php $blog_search_alph = $_GET['search_alph'] ?? ''; ?>
										<?php $blog_search_raiting = $_GET['search_raiting'] ?? ''; ?>
										<?php $blog_search_raiting_order = $_GET['search_raiting_order'] ?? ''; ?>
										<?php $type_build = $_GET['type_build'] ?? ''; ?>
										<?php $blog_paged = $_GET['_s_paged'] ?? 1; ?>
										<?php $field_eco = get_field_object( 'ecological_copy' ); ?>
										<?php $building_type = get_field_object( 'building_type' ); ?>

                                        <div class="row">
                                            <div class="col-3">
                                                <h4 style="margin-top: 20px;">Search alphabet</h4>
                                                <select name="search_alph">
                                                    <option value="">All Alphabet</option>
													<?php foreach ( range( 'A', 'Z' ) as $item ): ?>
                                                        <option value="<?php echo esc_attr( $item ); ?>" <?php echo ( isset( $blog_search_alph ) && ! empty( $blog_search_alph ) && $item == $blog_search_alph ) ? 'selected' : false; ?>><?php echo esc_html( $item ); ?></option>
													<?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <h4 style="margin-top: 20px;">Ecological level</h4>
                                                <select name="search_raiting">
                                                    <option value="">All Rating</option>
													<?php foreach ( range( '1', '5' ) as $item ): ?>
                                                        <option value="<?php echo esc_attr( $item ); ?>" <?php echo ( isset( $blog_search_raiting ) && ! empty( $blog_search_raiting ) && $item == $blog_search_raiting ) ? 'selected' : false; ?>><?php echo esc_html( $item ); ?></option>
													<?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <h4 style="margin-top: 20px;">Eco level sorting</h4>
                                                <select name="search_raiting_order">
                                                    <option value="">Equals</option>
                                                    <option value="DESC" <?php echo ( isset( $blog_search_raiting_order ) && ! empty( $blog_search_raiting_order ) && 'DESC' == $blog_search_raiting_order ) ? 'selected' : false; ?>>
                                                        High to low
                                                    </option>
                                                    <option value="ASC" <?php echo ( isset( $blog_search_raiting_order ) && ! empty( $blog_search_raiting_order ) && 'ASC' == $blog_search_raiting_order ) ? 'selected' : false; ?>>
                                                        Low to high
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <h4 style="margin-top: 20px;">Categories</h4>
												<?php foreach ( $blog_tax as $cat ): ?>
                                                    <label data-cat_slug="<?php echo esc_attr( $cat->slug ); ?>">
                                                        <input name="blog_category[]" type="checkbox"
                                                               value="<?php echo esc_attr( $cat->slug ); ?>" <?php echo( isset( $blog_cat_param ) && ! empty( $blog_cat_param ) && in_array( $cat->slug, $blog_cat_param ) ? 'checked' : false ); ?>>
														<?php echo $cat->name; ?>
                                                    </label>
												<?php endforeach; ?>
                                            </div>
                                            <div class="col-3">
                                                <h4 style="margin-top: 20px;">Type building</h4>
                                                <input type="radio" name="type_build" id="first_type_building" value="Panel">
                                                <label for="first_type_building">Panel</label>
                                                <input type="radio" name="type_build" id="second_type_building" value="Brick">
                                                <label for="second_type_building">Brick</label>
                                                <input type="radio" name="type_build" id="third_type_building" value="Foam Block">
                                                <label for="third_type_building">Foam Block</label>
                                            </div>
                                        </div>


                                        <input type="submit" value="Sort" style="margin-top: 20px;">
                                        <input type="hidden" name="paged"
                                               value="<?php echo( isset( $blog_paged ) && ! empty( $blog_paged ) ? $blog_paged : 1 ); ?>">
                                        <input type="hidden" name="action" value="ajax_handler">
                                    </form>
                                </div>
                                <div class="real_estates col-12">
                                    <h2 style="text-decoration: underline;">Content</h2>
                                    <div class="js_active_filters"></div>
                                    <div class="js_result"></div>
                                </div>
                            </div>
							<?php
							while ( have_posts() ) {
								the_post();
								get_template_part( 'loop-templates/content', 'page' );

								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) {
									comments_template();
								}
							}
							?>
                        </section>

                    </main>

                </div><!-- #primary -->

            </div><!-- .row -->

        </div><!-- #content -->

    </div><!-- #<?php echo $wrapper_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ok. ?> -->

<?php
get_footer();
