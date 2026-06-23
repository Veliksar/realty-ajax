<?php

defined( 'ABSPATH' ) || exit;

$blog_tax                  = get_terms( 'district', array( 'hide_empty' => false ) );
$blog_cat_param            = $_GET['blog_category'] ?? '';
$blog_search_alph          = $_GET['search_alph'] ?? '';
$blog_search_raiting       = $_GET['search_raiting'] ?? '';
$blog_search_raiting_order = $_GET['search_raiting_order'] ?? '';
$type_build                = $_GET['type_build'] ?? '';
$blog_paged                = $_GET['_s_paged'] ?? 1;
$posts_per_page            = function_exists( 'realty_get_posts_per_page' ) ? realty_get_posts_per_page() : 12;
$building_types            = array(
	'panel' => 'Panel',
	'brick' => 'Brick',
	'foam'  => 'Foam Block',
);
?>
<section class="re-catalog">
	<div class="container">
		<div class="re-catalog__layout">
			<aside class="re-catalog__sidebar">
				<h2 class="re-catalog__sidebar-title">Filters</h2>
				<p class="re-catalog__sidebar-desc">Refine your search by district, building type, and environmental rating.</p>

				<form action="." class="js-ajax-filter re-filter">
					<div class="re-filter__group">
						<label class="re-filter__label" for="search_alph">Alphabet</label>
						<select class="re-filter__select" name="search_alph" id="search_alph">
							<option value="">All letters</option>
							<?php foreach ( range( 'A', 'Z' ) as $item ) : ?>
								<option value="<?php echo esc_attr( $item ); ?>" <?php selected( $blog_search_alph, $item ); ?>><?php echo esc_html( $item ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="re-filter__group">
						<label class="re-filter__label" for="search_raiting">Eco rating</label>
						<select class="re-filter__select" name="search_raiting" id="search_raiting">
							<option value="">All ratings</option>
							<?php foreach ( range( 1, 5 ) as $item ) : ?>
								<option value="<?php echo esc_attr( $item ); ?>" <?php selected( $blog_search_raiting, (string) $item ); ?>><?php echo esc_html( $item ); ?> / 5</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="re-filter__group">
						<label class="re-filter__label" for="search_raiting_order">Eco sorting</label>
						<select class="re-filter__select" name="search_raiting_order" id="search_raiting_order">
							<option value="">Default</option>
							<option value="DESC" <?php selected( $blog_search_raiting_order, 'DESC' ); ?>>High to low</option>
							<option value="ASC" <?php selected( $blog_search_raiting_order, 'ASC' ); ?>>Low to high</option>
						</select>
					</div>

					<?php if ( ! empty( $blog_tax ) && ! is_wp_error( $blog_tax ) ) : ?>
					<div class="re-filter__group">
						<span class="re-filter__label">Districts</span>
						<div class="re-filter__checkboxes">
							<?php foreach ( $blog_tax as $cat ) : ?>
								<label class="re-filter__checkbox" data-cat_slug="<?php echo esc_attr( $cat->slug ); ?>">
									<input name="blog_category[]" type="checkbox" value="<?php echo esc_attr( $cat->slug ); ?>" <?php echo ( is_array( $blog_cat_param ) && in_array( $cat->slug, $blog_cat_param, true ) ) || ( is_string( $blog_cat_param ) && $blog_cat_param === $cat->slug ) ? 'checked' : ''; ?>>
									<?php echo esc_html( $cat->name ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<div class="re-filter__group">
						<span class="re-filter__label">Building type</span>
						<div class="re-filter__radios">
							<?php foreach ( $building_types as $build_slug => $build_label ) : ?>
								<?php $input_id = 'type_build_' . $build_slug; ?>
								<label class="re-filter__radio" for="<?php echo esc_attr( $input_id ); ?>">
									<input type="radio" name="type_build" id="<?php echo esc_attr( $input_id ); ?>" value="<?php echo esc_attr( $build_slug ); ?>" <?php checked( $type_build, $build_slug ); ?>>
									<?php echo esc_html( $build_label ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>

					<button type="submit" class="re-filter__submit">Apply filters</button>
					<input type="hidden" name="paged" value="<?php echo esc_attr( $blog_paged ); ?>">
					<input type="hidden" name="posts_per_page" value="<?php echo esc_attr( (string) $posts_per_page ); ?>">
					<input type="hidden" name="action" value="ajax_handler">
				</form>
			</aside>

			<div class="re-catalog__main">
				<div class="re-catalog__results-header">
					<h2 class="re-catalog__results-title">Available Properties</h2>
					<span class="re-catalog__count js_result-count"></span>
				</div>
				<div class="re-active-filters js_active_filters"></div>
				<div class="js_result"></div>
			</div>
		</div>
	</div>
</section>
