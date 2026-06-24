<?php
/**
 * Template Name: Home Page
 *
 * @package Understrap
 */

defined( 'ABSPATH' ) || exit;

get_header();

require_once get_stylesheet_directory() . '/inc/realty-helpers.php';

$blog_tax        = get_terms( 'district', array( 'hide_empty' => false ) );
$realty_pages    = get_pages(
	array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'page-templates/realtypage.php',
		'number'     => 1,
	)
);
$realty_page_url = ! empty( $realty_pages ) ? get_permalink( $realty_pages[0] ) : home_url( '/' );
$faq_items       = array(
	array(
		'question' => 'What types of commercial properties do you list?',
		'answer'   => 'Our catalog includes office buildings, business centers, and corporate spaces across multiple districts. Each listing includes building type, floor count, district, and eco rating to help you compare options quickly.',
	),
	array(
		'question' => 'How does the eco rating work?',
		'answer'   => 'Every property is rated from 1 to 5 based on environmental factors such as energy efficiency, materials, and sustainability features. You can filter and sort listings by eco rating on the full catalog page.',
	),
	array(
		'question' => 'Can I filter properties by district or building type?',
		'answer'   => 'Yes. Use the catalog page to filter by district, alphabet, building type (panel, brick, foam block), and eco rating. Active filters are shown above the results grid for easy refinement.',
	),
	array(
		'question' => 'Are property details verified before publication?',
		'answer'   => 'Listings are reviewed for key data consistency: location, building characteristics, floor plans, and contact details. We aim to keep the catalog accurate and up to date for corporate tenants and investors.',
	),
	array(
		'question' => 'How do I schedule a viewing or request more information?',
		'answer'   => 'Open any property page to see full details, floor plans, and contact options. For general inquiries, use the contact page or reach out through the form linked in the site footer.',
	),
);
?>
<div class="wrapper" id="full-width-page-wrapper">
	<section class="re-hero re-hero--home">
		<div class="re-hero__bg" style="background-image: url('<?php echo esc_url( realty_get_placeholder_url( 'hero' ) ); ?>');"></div>
		<div class="re-hero__overlay"></div>
		<div class="container re-hero__content">
			<p class="re-hero__eyebrow">Realty Etcetera</p>
			<h1 class="re-hero__title">Corporate Real Estate, Curated for Growth</h1>
			<p class="re-hero__subtitle">Discover premium office and business spaces with transparent data on location, building type, and environmental performance. Browse our catalog of properties and find the perfect space for your business.</p>
			<div class="re-hero__stats">
				<div>
					<div class="re-hero__stat-value"><?php echo esc_html( wp_count_posts( 'realty' )->publish ); ?>+</div>
					<div class="re-hero__stat-label">Properties</div>
				</div>
				<div>
					<div class="re-hero__stat-value"><?php echo is_array( $blog_tax ) ? count( $blog_tax ) : 0; ?></div>
					<div class="re-hero__stat-label">Districts</div>
				</div>
				<div>
					<div class="re-hero__stat-value">Eco 1-5</div>
					<div class="re-hero__stat-label">Rating Scale</div>
				</div>
			</div>
			<div class="re-hero__actions">
				<a class="btn btn-secondary re-hero__btn" href="<?php echo esc_url( $realty_page_url ); ?>">Browse catalog</a>
				<a class="btn btn-outline-light re-hero__btn" href="#featured-properties">Featured properties</a>
			</div>
		</div>
	</section>

	<section class="re-home-intro">
		<div class="container">
			<div class="row align-items-center g-4 g-lg-5">
				<div class="col-lg-6">
					<p class="re-home-intro__eyebrow">About our platform</p>
					<h2 class="re-home-intro__title">Smart search for corporate tenants</h2>
					<p class="re-home-intro__text">Whether you are expanding a headquarters, opening a regional office, or evaluating investment assets, Realty Etcetera brings structured property data into one searchable catalog.</p>
					<p class="re-home-intro__text">Compare districts, building types, and sustainability scores side by side. Each listing includes imagery, floor count, and detailed specs so your team can shortlist spaces without endless back-and-forth.</p>
				</div>
				<div class="col-lg-6">
					<div class="row g-3 re-home-intro__features">
						<div class="col-sm-6">
							<div class="re-home-feature">
								<h3 class="re-home-feature__title">District coverage</h3>
								<p class="re-home-feature__text">Explore commercial stock across all mapped districts with taxonomy-based filtering.</p>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="re-home-feature">
								<h3 class="re-home-feature__title">Building insights</h3>
								<p class="re-home-feature__text">Panel, brick, and foam block types help you match construction standards to your requirements.</p>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="re-home-feature">
								<h3 class="re-home-feature__title">Eco transparency</h3>
								<p class="re-home-feature__text">Visual eco ratings make it easy to prioritize sustainable spaces for ESG-aligned portfolios.</p>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="re-home-feature">
								<h3 class="re-home-feature__title">Ready to scale</h3>
								<p class="re-home-feature__text">From single-floor offices to multi-story business centers - find spaces that grow with your company.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="re-home-listings" id="featured-properties">
		<div class="container">
			<div class="re-home-listings__header">
				<div>
					<p class="re-home-listings__eyebrow">Featured selection</p>
					<h2 class="re-home-listings__title">Latest commercial properties</h2>
					<p class="re-home-listings__desc">A preview of recently added listings. Open the full catalog for advanced filters and pagination.</p>
				</div>
				<a class="btn btn-outline-primary re-home-listings__link" href="<?php echo esc_url( $realty_page_url ); ?>">View all properties</a>
			</div>
			<?php echo do_shortcode( '[display_realty limit="9" layout="carousel" per_slide="3"]' ); ?>
		</div>
	</section>

	<section class="re-home-faq">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<p class="re-home-faq__eyebrow text-center">FAQ</p>
					<h2 class="re-home-faq__title text-center">Frequently asked questions</h2>
					<p class="re-home-faq__desc text-center">Quick answers about our catalog, ratings, and how to get in touch.</p>

					<div class="accordion re-faq-accordion" id="homeFaqAccordion">
						<?php foreach ( $faq_items as $index => $item ) : ?>
							<?php
							$item_number = $index + 1;
							$heading_id  = 'homeFaqHeading' . $item_number;
							$collapse_id = 'homeFaqCollapse' . $item_number;
							$is_first    = 0 === $index;
							?>
							<div class="accordion-item">
								<h3 class="accordion-header" id="<?php echo esc_attr( $heading_id ); ?>">
									<button class="accordion-button<?php echo $is_first ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $collapse_id ); ?>">
										<?php echo esc_html( $item['question'] ); ?>
									</button>
								</h3>
								<div id="<?php echo esc_attr( $collapse_id ); ?>" class="accordion-collapse collapse<?php echo $is_first ? ' show' : ''; ?>" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" data-bs-parent="#homeFaqAccordion">
									<div class="accordion-body">
										<?php echo esc_html( $item['answer'] ); ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	while ( have_posts() ) {
		the_post();
		$page_content = trim( get_the_content() );
		if ( $page_content ) {
			echo '<section class="re-home-editor">';
			echo '<div class="container">';
			echo apply_filters( 'the_content', $page_content );
			echo '</div>';
			echo '</section>';
		}
	}
	?>
</div>
<?php
get_footer();
