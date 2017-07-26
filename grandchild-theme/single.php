<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.0
 */
 if ( function_exists( 'ot_get_option' ) ) {
 $rw_blog_page_sidebar = ot_get_option( 'rw_blog_page_sidebar', 'right-sidebar');
}
?>


<div id="content">

	<?php require_once(plugin_dir_path(__FILE__)."loop.php"); ?>

	<?php appthemes_advertise_content(); ?>

	<?php if ( comments_open() ) comments_template(); ?>
    <?php the_ad(19879); ?>

</div>

<?php if($rw_blog_page_sidebar!='full-width'){ get_sidebar( 'blog' ); }?>
