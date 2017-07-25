<?php
/**
 * Main loop for displaying blog posts.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.0
 */


// hack needed for "<!-- more -->" to work with templates
// call before the loop
global $more;
$more = 0;
?>

<?php appthemes_before_blog_loop(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( is_single() ) appthemes_stats_update( $post->ID ); //records the page hit on single blog page view ?>

		<?php appthemes_before_blog_post(); ?>

		<?php require_once(plugin_dir_path(__FILE__)."content.php"); ?>

		<?php appthemes_after_blog_post(); ?>

	<?php endwhile; ?>

	<?php appthemes_after_blog_endwhile(); ?>

<?php else: ?>

	<?php appthemes_blog_loop_else(); ?>

	<div class="content-box">

		<div class="box-holder">

			<div class="blog">

				<h1><?php _e( 'No Posts Found', APP_TD ); ?></h1>

				<div class="text-box">

					<?php _e( 'Sorry, no posts found.', APP_TD ); ?>

				</div>

			</div>

		</div>

	</div>

<?php endif; ?>

<?php appthemes_after_blog_loop(); ?>
