<?php
/**
 * Search results template.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.0
 */


if ( $clpr_options->search_stats ) {
	appthemes_save_search();
}
if ( function_exists( 'ot_get_option' ) ) {
 $c_list_mode = ot_get_option( 'rw_site_view' , 'grid');
  $rw_search_page_sidebar = ot_get_option( 'rw_search_page_sidebar', 'right-sidebar');
}
?>
<div id="content">

	<div class="content-box">

		<div class="box-holder">

				<h2><span><?php printf( __( "Search for '%s' returned %s results", RW_CP_TD ), trim( get_search_query() ), $wp_query->found_posts ); ?></span></h2>

			<?php if ( have_posts() ) : ?>
                <div id="listgrid" class="listgrid">
                <p class="listnav"> <a class="switch_list list <?php if($c_list_mode=='list'){echo 'active';}?>" href="javascript:;"><?php _e( 'List View', RW_CP_TD ); ?></a> <a class="switch_grid grid <?php if($c_list_mode=='grid'){echo 'active';}?>" href="javascript:;"><?php _e( 'Grid View', RW_CP_TD ); ?></a> </p>
                
                <div class="view <?php echo $c_list_mode.'view'; ?>">
               <?php require( plugin_dir_path(__FILE__).'loop-search.php' ); ?>
                </div>
                </div>

			<?php else : ?>

				<div class="blog">
					<div class="pad10"></div>
					<h3><?php printf( __( 'Sorry, no coupons could be found for "%s".', RW_CP_TD ), trim( get_search_query() ) ); ?></h3>
					<div class="pad75"></div>
				</div> <!-- end blog -->

			<?php endif; ?>

		</div> <!-- end box-holder -->

	</div> <!-- end content-box -->

</div> <!-- end content -->

<?php if($rw_search_page_sidebar!='full-width'){ get_sidebar( 'coupon' );} ?>
