<?php
/**
 * Main loop for displaying coupons.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.0
 */
?>
<?php
if ( function_exists( 'ot_get_option' ) ) {
	$coups_advertise = ot_get_option( 'rw_coups_advertise' , 'on'); }?>
<?php appthemes_before_loop(); ?>

<?php if ( have_posts() ) : ?>

   <?php $adno = 0; ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php appthemes_before_post(); ?>
        
        <?php  if ( $coups_advertise =='on' ){ $adno= $adno + 1; rw_show_coups_advertise($adno); } ?>

		<?php require( plugin_dir_path(__FILE__).'content-coupon.php' ); ?>

		<?php appthemes_after_post(); ?>

	<?php endwhile; ?>

	<?php appthemes_after_endwhile(); ?>

<?php else: ?>

	<?php appthemes_loop_else(); ?>

	<div class="blog">

		<h3><?php _e( 'Sorry, no coupons found', RW_CP_TD ); ?></h3>

	</div> <!-- #blog -->

<?php endif; ?>

<?php appthemes_after_loop(); ?>
