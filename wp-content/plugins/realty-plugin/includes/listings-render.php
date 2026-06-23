<?php

defined( 'ABSPATH' ) || exit;

function realty_render_listings_pagination( $query ) {
	if ( function_exists( 'foundation_pagination' ) ) {
		foundation_pagination( $query );
		return;
	}

	$paged = isset( $query->query['paged'] ) ? (int) $query->query['paged'] : 1;
	$big   = 999999999;

	$links = paginate_links(
		array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'prev_next' => true,
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'current'   => max( 1, $paged ),
			'total'     => $query->max_num_pages,
			'type'      => 'list',
		)
	);

	if ( $links ) {
		echo str_replace( 'page-numbers', 'pagination', $links );
	}
}

function realty_render_listings_html( $query, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'pagination' => true,
		)
	);

	if ( ! $query->have_posts() ) {
		return sprintf(
			'<div class="re-empty"><h3 class="re-empty__title">%s</h3><p class="re-empty__text">%s</p></div>',
			esc_html__( 'No properties found', 'realty-plugin' ),
			esc_html__( 'Try adjusting your filters to see more results.', 'realty-plugin' )
		);
	}

	ob_start();

	echo '<div class="re-grid">';

	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'loop-templates/content', 'ajax' );
	}

	echo '</div>';

	if ( $args['pagination'] ) {
		realty_render_listings_pagination( $query );
	}

	wp_reset_postdata();

	return ob_get_clean();
}

function realty_render_listings_carousel( $query, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'per_slide'   => 3,
			'carousel_id' => 'realty-carousel-' . wp_unique_id(),
		)
	);

	$per_slide = max( 1, (int) $args['per_slide'] );

	if ( ! $query->have_posts() ) {
		return sprintf(
			'<div class="re-empty"><h3 class="re-empty__title">%s</h3><p class="re-empty__text">%s</p></div>',
			esc_html__( 'No properties found', 'realty-plugin' ),
			esc_html__( 'Try adjusting your filters to see more results.', 'realty-plugin' )
		);
	}

	$cards = array();

	while ( $query->have_posts() ) {
		$query->the_post();
		ob_start();
		get_template_part( 'loop-templates/content', 'ajax' );
		$cards[] = ob_get_clean();
	}

	wp_reset_postdata();

	$slides     = array_chunk( $cards, $per_slide );
	$carousel_id = esc_attr( $args['carousel_id'] );

	ob_start();
	?>
	<div id="<?php echo $carousel_id; ?>" class="carousel slide re-featured-carousel" data-bs-ride="false">
		<div class="re-featured-carousel__frame">
			<?php if ( count( $slides ) > 1 ) : ?>
			<button class="carousel-control-prev re-featured-carousel__control" type="button" data-bs-target="#<?php echo $carousel_id; ?>" data-bs-slide="prev">
				<span class="re-featured-carousel__arrow re-featured-carousel__arrow--prev" aria-hidden="true"></span>
				<span class="visually-hidden"><?php esc_html_e( 'Previous', 'realty-plugin' ); ?></span>
			</button>
			<?php endif; ?>

			<div class="carousel-inner">
				<?php foreach ( $slides as $slide_index => $slide_cards ) : ?>
					<div class="carousel-item<?php echo 0 === $slide_index ? ' active' : ''; ?>">
						<div class="row g-4">
							<?php foreach ( $slide_cards as $card_html ) : ?>
								<div class="col-md-4">
									<?php echo $card_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- card markup from theme template. ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( count( $slides ) > 1 ) : ?>
			<button class="carousel-control-next re-featured-carousel__control" type="button" data-bs-target="#<?php echo $carousel_id; ?>" data-bs-slide="next">
				<span class="re-featured-carousel__arrow re-featured-carousel__arrow--next" aria-hidden="true"></span>
				<span class="visually-hidden"><?php esc_html_e( 'Next', 'realty-plugin' ); ?></span>
			</button>
			<?php endif; ?>
		</div>

		<?php if ( count( $slides ) > 1 ) : ?>
		<div class="carousel-indicators re-featured-carousel__indicators">
			<?php foreach ( $slides as $slide_index => $slide_cards ) : ?>
				<button
					type="button"
					data-bs-target="#<?php echo $carousel_id; ?>"
					data-bs-slide-to="<?php echo esc_attr( (string) $slide_index ); ?>"
					class="<?php echo 0 === $slide_index ? 'active' : ''; ?>"
					aria-current="<?php echo 0 === $slide_index ? 'true' : 'false'; ?>"
					aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'realty-plugin' ), $slide_index + 1 ) ); ?>"
				></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

function realty_format_listings_response( $query ) {
	$items = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = realty_format_listing_item( get_the_ID() );
		}
		wp_reset_postdata();
	}

	return array(
		'found_posts'     => (int) $query->found_posts,
		'max_num_pages'   => (int) $query->max_num_pages,
		'posts'           => $items,
	);
}
