<?php
/**
 * The loop that displays the coupons and blog posts.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.2.4
 */


// hack needed for "<!-- more -->" to work with templates
// call before the loop
global $more;
$more = 0;
?>

<?php appthemes_before_loop( 'search' ); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php appthemes_before_post( 'search' ); ?>

		<?php require( plugin_dir_path(__FILE__).'content-search.php' ); ?>

		<?php appthemes_after_post( 'search' ); ?>

	<?php endwhile; ?>

	<?php appthemes_after_endwhile( 'search' ); ?>

<?php else: ?>

	<?php appthemes_loop_else( 'search' ); ?>

	<div class="blog">

		<h3><?php _e( 'Sorry, no coupons found', APP_TD ); ?></h3>

	</div> <!-- #blog -->

<?php endif; ?>

<?php appthemes_after_loop( 'search' ); ?>
