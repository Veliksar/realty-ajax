<?php
defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/realty-helpers.php';

$term_blog_cat         = get_the_terms( get_the_ID(), 'district' );
$terms_blog_cat_string = ( $term_blog_cat && ! is_wp_error( $term_blog_cat ) ) ? join( ', ', wp_list_pluck( $term_blog_cat, 'name' ) ) : '';
$house_title           = get_field( 'house_title' );
$house_image           = realty_get_image_url( get_field( 'house_image' ), 'card' );
$house_eco_level       = (int) get_field( 'ecological' );
$house_type            = get_field( 'building_type' );
$house_type_label      = function_exists( 'realty_get_building_type_label' ) ? realty_get_building_type_label( $house_type ) : $house_type;
$house_floors          = get_field( 'number_of_floors' );
?>
<article class="re-card">
    <a class="re-card__link" href="<?php the_permalink(); ?>">
        <div class="re-card__image">
            <img src="<?php echo esc_url( $house_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
            <div class="re-card__badges">
                <?php if ( $terms_blog_cat_string ) : ?>
                    <span class="re-card__badge"><?php echo esc_html( $terms_blog_cat_string ); ?></span>
                <?php endif; ?>
                <?php if ( $house_type_label ) : ?>
                    <span class="re-card__badge re-card__badge--gold"><?php echo esc_html( $house_type_label ); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="re-card__body">
            <h3 class="re-card__title"><?php the_title(); ?></h3>
            <?php if ( $house_title ) : ?>
                <p class="re-card__subtitle"><?php echo esc_html( $house_title ); ?></p>
            <?php endif; ?>
            <div class="re-card__meta">
                <div class="re-card__meta-item">
                    <strong>Floors</strong>
                    <?php echo esc_html( $house_floors ? $house_floors : '-' ); ?>
                </div>
                <div class="re-card__meta-item">
                    <strong>Eco rating</strong>
                    <div class="re-card__eco">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <span class="<?php echo $i <= $house_eco_level ? 'active' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </a>
</article>
