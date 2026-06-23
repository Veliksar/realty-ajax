<?php
/**
 * Template Name: Realty Page
 *
 * @package Understrap
 */

defined( 'ABSPATH' ) || exit;

get_header();

require_once get_stylesheet_directory() . '/inc/realty-helpers.php';

$blog_tax = get_terms( 'district', array( 'hide_empty' => false ) );
?>
<div class="wrapper" id="full-width-page-wrapper">
    <section class="re-hero">
        <div class="re-hero__bg" style="background-image: url('<?php echo esc_url( realty_get_placeholder_url( 'hero' ) ); ?>');"></div>
        <div class="re-hero__overlay"></div>
        <div class="container re-hero__content">
            <p class="re-hero__eyebrow">Corporate Real Estate</p>
            <h1 class="re-hero__title">Premium Office &amp; Business Spaces</h1>
            <p class="re-hero__subtitle">Curated commercial properties for corporations, enterprises, and growing businesses. Find the space that matches your standards.</p>
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
                    <div class="re-hero__stat-value">A-Z</div>
                    <div class="re-hero__stat-label">Smart Filter</div>
                </div>
            </div>
        </div>
    </section>

    <?php get_template_part( 'template-parts/realty', 'catalog' ); ?>
</div>
<?php
get_footer();
