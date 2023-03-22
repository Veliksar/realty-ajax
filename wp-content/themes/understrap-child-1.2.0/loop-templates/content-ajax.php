<?php
$term_blog_cat         = get_the_terms( get_the_ID(), 'district' );
$terms_blog_cat_string = join( ', ', wp_list_pluck( $term_blog_cat, 'name' ) );
$house_title           = get_field( 'house_title' );
$house_image           = get_field( 'house_image' );
$house_eco_level       = get_field( 'ecological' );
$house_type            = get_field( 'building_type' );
$house_floors          = get_field( 'number_of_floors' );
?>
<div class="col-4">
    <a href="<?php echo get_the_permalink(); ?>">
        <img src="<?php echo $house_image; ?>" alt="house image"
             style="width: 200px; height: 200px; object-fit: cover;">
        <h3><?php echo get_the_title(); ?> - <?php echo $house_title; ?></h3>
        <h4>Disctrict - <?php echo $terms_blog_cat_string; ?></h4>
        <h5>Building type - <?php echo $house_type; ?></h5>
        <h5>Eco level - <?php echo $house_eco_level; ?></h5>
        <p>Floors - <?php echo $house_floors; ?></p>
    </a>
</div>
