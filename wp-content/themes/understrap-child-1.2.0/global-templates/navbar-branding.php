<?php
/**
 * Navbar branding
 *
 * @package Understrap
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || exit;

$brand = get_bloginfo( 'name' );
$parts = preg_split( '/\s+/', trim( $brand ), 2 );
$first = $parts[0] ?? $brand;
$rest  = $parts[1] ?? '';
?>
<?php if ( is_front_page() && is_home() ) : ?>
    <h1 class="navbar-brand mb-0">
        <a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
            <?php echo esc_html( $first ); ?><?php if ( $rest ) : ?> <span><?php echo esc_html( $rest ); ?></span><?php endif; ?>
        </a>
    </h1>
<?php else : ?>
    <a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
        <?php echo esc_html( $first ); ?><?php if ( $rest ) : ?> <span><?php echo esc_html( $rest ); ?></span><?php endif; ?>
    </a>
<?php endif; ?>
