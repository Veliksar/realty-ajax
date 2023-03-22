<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <div class="entry-meta">

			<?php understrap_posted_on(); ?>

        </div><!-- .entry-meta -->

    </header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

    <div class="entry-content">

		<?php
		the_content();
		understrap_link_pages();
		?>
		<?php
        if (is_singular('realty')) {
            
		$term_blog_cat         = get_the_terms( get_the_ID(), 'district' );
		$terms_blog_cat_string = join( ', ', wp_list_pluck( $term_blog_cat, 'name' ) );
		$house_title           = get_field( 'house_title' );
		$house_image           = get_field( 'house_image' );
		$house_eco_level       = get_field( 'ecological' );
		$house_type            = get_field( 'building_type' );
		$house_floors          = get_field( 'number_of_floors' );
		?>
        <div class="col-12">
            <a href="<?php echo get_the_permalink(); ?>">
                <img src="<?php echo $house_image; ?>" alt="house image"
                     style="width: 80%; height: 400px; object-fit: cover;">
                <h2><?php echo get_the_title(); ?> - <?php echo $house_title; ?></h2>
                <h3>Disctrict - <?php echo $terms_blog_cat_string; ?></h3>
                <h4>Building type - <?php echo $house_type; ?></h4>
                <h5>Eco level - <?php echo $house_eco_level; ?></h5>
                <h5>Floors - <?php echo $house_floors; ?></h5>
            </a>
			<?php if ( have_rows( 'place' ) ): ?>
                <div>
					<?php while ( have_rows( 'place' ) ) : the_row(); ?>
						<?php $place_area = get_sub_field( 'area' ); ?>
						<?php $place_rooms = get_sub_field( 'rooms' ); ?>
						<?php $place_balcony = get_sub_field( 'balcony' ); ?>
						<?php $place_bathroom = get_sub_field( 'bathroom' ); ?>
						<?php $place_image = get_sub_field( 'place_image' ); ?>
                    <div style="border: 1px solid #0a4b78; display: flex; justify-content: space-between; align-items: center; margin: 10px 0; padding: 0 10px;">
                        <p>Area: <?php echo $place_area; ?>m^2</p>
                        <p>Rooms: <?php echo $place_rooms; ?></p>
                        <p>Balcony: <?php echo $place_balcony; ?></p>
                        <p>Bathroom: <?php echo $place_bathroom; ?></p>
                        <img src="<?php echo $place_image['url'] ?>" alt="place scheme" style="width: 180px; height: 180px;">
                    </div>
					<?php endwhile; ?>
                </div>
			<?php endif; ?>
        </div>
        <?php } ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

    </footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
