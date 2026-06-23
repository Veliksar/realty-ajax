<?php
/**
 * The template for displaying the footer
 *
 * @package Understrap
 */

defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
$brand     = get_bloginfo( 'name' );
$parts     = preg_split( '/\s+/', trim( $brand ), 2 );
$first     = $parts[0] ?? $brand;
$rest      = $parts[1] ?? '';
?>

<footer class="re-footer" id="colophon">
    <div class="<?php echo esc_attr( $container ); ?>">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="re-footer__brand">
                    <?php echo esc_html( $first ); ?><?php if ( $rest ) : ?> <span><?php echo esc_html( $rest ); ?></span><?php endif; ?>
                </div>
                <p class="re-footer__tagline">Corporate commercial real estate for offices, business centers, and enterprise clients.</p>
            </div>
            <div class="col-md-4">
                <div class="re-footer__heading">Navigation</div>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'list-unstyled mb-0',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'link_before'    => '<span class="re-footer__link">',
                        'link_after'     => '</span>',
                    )
                );
                ?>
            </div>
            <div class="col-md-4">
                <div class="re-footer__heading">Contact</div>
                <span class="re-footer__link">info@realtyetcetera.local</span>
                <span class="re-footer__link">+1 (000) 000-0000</span>
                <span class="re-footer__link">Business hours: Mon-Fri 9:00-18:00</span>
            </div>
        </div>
        <div class="re-footer__bottom">
            <?php understrap_site_info(); ?>
        </div>
    </div>
</footer>

<?php // Closing div#page from header.php. ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
