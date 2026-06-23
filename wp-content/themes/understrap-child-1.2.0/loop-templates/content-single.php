<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/realty-helpers.php';
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <?php if ( is_singular( 'realty' ) ) :
        $term_blog_cat         = get_the_terms( get_the_ID(), 'district' );
        $terms_blog_cat_string = ( $term_blog_cat && ! is_wp_error( $term_blog_cat ) ) ? join( ', ', wp_list_pluck( $term_blog_cat, 'name' ) ) : '';
        $house_title           = get_field( 'house_title' );
        $house_image           = realty_get_image_url( get_field( 'house_image' ), 'hero' );
        $house_eco_level       = get_field( 'ecological' );
        $house_type            = get_field( 'building_type' );
        $house_type_label      = function_exists( 'realty_get_building_type_label' ) ? realty_get_building_type_label( $house_type ) : $house_type;
        $house_floors          = get_field( 'number_of_floors' );
    ?>
        <div class="re-single__hero">
            <img src="<?php echo esc_url( $house_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
            <div class="re-single__hero-overlay">
                <div class="container">
                    <h1 class="re-single__hero-title"><?php the_title(); ?></h1>
                    <?php if ( $house_title ) : ?>
                        <p class="re-single__hero-subtitle"><?php echo esc_html( $house_title ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="re-single__specs">
                <div class="re-single__spec">
                    <div class="re-single__spec-label">District</div>
                    <div class="re-single__spec-value"><?php echo esc_html( $terms_blog_cat_string ? $terms_blog_cat_string : '-' ); ?></div>
                </div>
                <div class="re-single__spec">
                    <div class="re-single__spec-label">Building type</div>
                    <div class="re-single__spec-value"><?php echo esc_html( $house_type_label ? $house_type_label : '-' ); ?></div>
                </div>
                <div class="re-single__spec">
                    <div class="re-single__spec-label">Floors</div>
                    <div class="re-single__spec-value"><?php echo esc_html( $house_floors ? $house_floors : '-' ); ?></div>
                </div>
                <div class="re-single__spec">
                    <div class="re-single__spec-label">Eco rating</div>
                    <div class="re-single__spec-value"><?php echo esc_html( $house_eco_level ? $house_eco_level . '/5' : '-' ); ?></div>
                </div>
            </div>

            <?php if ( get_the_content() ) : ?>
                <div class="entry-content mb-5">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>

            <?php if ( have_rows( 'place' ) ) : ?>
                <h2 class="re-single__places-title">Floor Plans &amp; Units</h2>
                <?php while ( have_rows( 'place' ) ) : the_row();
                    $place_area     = get_sub_field( 'area' );
                    $place_rooms    = get_sub_field( 'rooms' );
                    $place_balcony  = get_sub_field( 'balcony' );
                    $place_bathroom = get_sub_field( 'bathroom' );
                    $place_image    = get_sub_field( 'place_image' );
                    $place_img_url  = realty_get_image_url( is_array( $place_image ) ? ( $place_image['url'] ?? '' ) : $place_image, 'floor' );
                ?>
                    <div class="re-single__place">
                        <img class="re-single__place-image" src="<?php echo esc_url( $place_img_url ); ?>" alt="Floor plan">
                        <div class="re-single__place-details">
                            <div class="re-single__place-detail"><strong>Area</strong><?php echo esc_html( $place_area ); ?> m²</div>
                            <div class="re-single__place-detail"><strong>Rooms</strong><?php echo esc_html( $place_rooms ); ?></div>
                            <div class="re-single__place-detail"><strong>Balcony</strong><?php echo esc_html( $place_balcony ); ?></div>
                            <div class="re-single__place-detail"><strong>Bathroom</strong><?php echo esc_html( $place_bathroom ); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

    <?php else : ?>

        <header class="entry-header">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <div class="entry-meta">
                <?php understrap_posted_on(); ?>
            </div>
        </header>

        <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

        <div class="entry-content">
            <?php
            the_content();
            understrap_link_pages();
            ?>
        </div>

    <?php endif; ?>

    <footer class="entry-footer">
        <?php understrap_entry_footer(); ?>
    </footer>

</article>
