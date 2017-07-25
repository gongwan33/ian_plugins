<?php
/**
 * Coupon Listing loop content template.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.6
 */

global $clpr_options, $withcomments;

$withcomments = 1;
if ( function_exists( 'ot_get_option' ) ) {
$list_countdown = ot_get_option( 'rw_couponlisting_countdown' , 'on');

$rw_ribbon_coupon_new = ot_get_option( 'rw_ribbon_coupon_new' , 'on');
$rw_ribbon_coupon_new_text = ot_get_option( 'rw_ribbon_coupon_new_text' , 'New');


$rw_ribbon_coupon_feat = ot_get_option( 'rw_ribbon_coupon_feat' , 'on');
$rw_ribbon_coupon_feat_text = ot_get_option( 'rw_ribbon_coupon_feat_text' , 'Featured');
}
?>
<?php 
    //$backtrace = debug_backtrace();
    //print_r( $backtrace );
?>

<div <?php post_class( 'item' ); ?> id="post-<?php echo $post->ID; ?>">


	<div class="item-holder">
    <div class="post_top">
    	<p class="store-name"><?php echo get_the_term_list( $post->ID, APP_TAX_STORE, ' ', ', ', '' ); ?></p>
        
        <?php clpr_vote_box_badge( $post->ID ); ?>
    </div>

		<div class="item-frame">

			<div class="store-holder">
				<div class="store-image">
                    
                     <?php if($rw_ribbon_coupon_feat=='on' && get_post_meta( $post->ID, 'clpr_featured', true )){?>
                	<span class="featured_c"><?php echo $rw_ribbon_coupon_feat_text;?></span>
                    <?php }else if($rw_ribbon_coupon_new=='on'){ 
					$c_pub_date = new DateTime($post->post_date);
					$c_pub_date->modify('+1 day');
					if($c_pub_date->format('Y-m-d H:i:s') >= date('Y-m-d H:i:s')){?>
                	<span class="new_c"><?php echo $rw_ribbon_coupon_new_text;?></span>
                    <?php } }?>
					<a href="<?php echo clpr_get_first_term_link( $post->ID, APP_TAX_STORE ); ?>"><img src="<?php echo clpr_get_store_image_url( $post->ID, 'post_id', 110 ); ?>" alt="" /></a>
				</div>
				
			</div>

			

			<div class="item-panel">

 				<?php appthemes_before_post_title(); ?>

				<h3 class="entry-title grid-title"><?php rw_coupon_title(); ?></h3> 
                <?php  if ( $clpr_options->link_single_page ) {?>
                <h3 class="entry-title list-title"><a title="<?php the_title_attribute( 'echo=0' );?>" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                <?php }else{?>
                <h3 class="entry-title list-title"><a title="<?php the_title_attribute( 'echo=0' );?>" rel="bookmark"><?php the_title(); ?></a></h3>
                <?php }?>
                

				<?php appthemes_after_post_title(); ?>
                
                
                <div class="calendar">
				<ul>
					<li class="create"><time class="entry-date published" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time></li>
					<li class="modify"><time class="entry-date updated" datetime="<?php echo get_the_modified_date( 'c' ); ?>"><?php echo get_the_modified_date(); ?></time></li>
					<li class="expire"><time class="entry-date expired" datetime="<?php echo clpr_get_expire_date( $post->ID, 'c' ); ?>"><?php echo clpr_get_expire_date( $post->ID, 'display' ); ?></time></li>
				</ul>
			</div>
                
                
                 
				<?php appthemes_before_post_content(); ?>
				<div class="grid_c"> 
				<p class="desc entry-content"><?php rw_coupon_content(); ?></p>
				</div>
                
				<?php appthemes_after_post_content(); ?>
                

			</div> <!-- #item-panel -->
            
 			<div class="coupon_cbox" > 
            <?php clpr_coupon_code_box(); ?>
            </div><!-- coupon_cbox #end -->
            
           <?php if($list_countdown=='on'){ rw_coupon_countdown($post);}?>
				

			<div class="clear"></div>

			

			<div class="taxonomy">
				<p class="category"><?php _e( 'Category:', RW_CP_TD ); ?> <?php echo get_the_term_list( $post->ID, APP_TAX_CAT, ' ', ', ', '' ); ?></p>
				<p class="tag"><?php _e( 'Tags:', RW_CP_TD ); ?> <?php echo get_the_term_list( $post->ID, APP_TAX_TAG, ' ', ', ', '' ); ?></p>
			</div>

		</div> <!-- #item-frame -->

		<div class="item-footer">

			<ul class="social">

				<li class="stats"><?php if ( $clpr_options->stats_all && current_theme_supports( 'app-stats' ) ) appthemes_stats_counter( $post->ID ); ?></li>
				<li><a class="share" href="#"><?php _e( 'Share', RW_CP_TD ); ?></a>

					<div class="drop">

					<?php
						// assemble the text and url we'll pass into each social media share link
						$social_text = urlencode( strip_tags( get_the_title() . ' ' . __( 'coupon from', RW_CP_TD ) . ' ' . get_bloginfo( 'name' ) ) );
						$social_url = urlencode( get_permalink( $post->ID ) );
					?>

						<ul>
<li><div class="share_button share_icon_wechat" style="zoom:0.9;" onclick="wechat_button_click('QRCODE', '<?php echo get_permalink( $post->ID );?>',event)" title="微信"></div><span onclick="wechat_button_click('QRCODE', '<?php echo get_permalink( $post->ID );?>',event)">微信</span><div class="open_social_qrcode" onclick="jQuery(this).hide();"></div></li>
<!--                                                        <li><a class="wechat" rel="nofollow" href="javascript:;" style="height:16px;background:url(/wp-content/plugins/wp-connect/images/share/icon16.png?var=20170210) no-repeat 0 -864px;" onclick='var wcs=document.getElementById("wcs-image-<?php echo $post->ID;?>");if(!wcs.innerHTML) wcs.innerHTML="<"+"img "+"src=\"http://s.jiathis.com/qrcode.php?url=http%3A%2F%2Fwww.qdeal.com.au%2Farchives%2F<?php echo $post->ID;?>\" alt=\"Failed to load\" width=180 height=180 />";var wechatBlk = document.getElementById("wp-connect-share-wechat-<?php echo $post->ID;?>");console.log(wechatBlk.style.display);if(wechatBlk.style.display=="none"){wechatBlk.style.display="block";}else{wechatBlk.style.display="none";};return false'><?php _e('微信', RW_CP_TD);?></a></li>-->
                                                        <div id="wp-connect-share-wechat-<?php echo $post->ID;?>" style="display:none"><div id="wcs-image-<?php echo $post->ID;?>"></div></div>
                   
                                                        <li><a class="sina" rel="nofollow" href="javascript:;" style="background:none;" onclick="social_share(<?php echo $post->ID;?>,'sina');"><div class="sina-logo"></div><?php _e('新浪微博', RW_CP_TD);?></a></li>
							<li><a class="mail" href="#" data-id="<?php echo $post->ID; ?>" rel="nofollow"><?php _e( 'Email to Friend', RW_CP_TD ); ?></a></li>
							<li><a class="facebook" href="javascript:void(0);" onclick="window.open('http://www.facebook.com/sharer.php?t=<?php echo $social_text; ?>&amp;u=<?php echo $social_url; ?>','doc', 'width=638,height=500,scrollbars=yes,resizable=auto');" rel="nofollow"><?php _e( 'Facebook', RW_CP_TD ); ?></a></li>
							<li><a class="twitter" href="http://twitter.com/home?status=<?php echo $social_text; ?>+-+<?php echo $social_url; ?>" rel="nofollow" target="_blank"><?php _e( 'Twitter', RW_CP_TD ); ?></a></li>
						<!--	<li><a class="digg" href="http://digg.com/submit?phase=2&amp;url=<?php echo $social_url; ?>&amp;title=<?php echo $social_text; ?>" rel="nofollow" target="_blank"><?php _e( 'Digg', RW_CP_TD ); ?></a></li>-->
							<!--<li><a class="reddit" href="http://reddit.com/submit?url=<?php echo $social_url; ?>&amp;title=<?php echo $social_text; ?>" rel="nofollow" target="_blank"><?php _e( 'Reddit', RW_CP_TD ); ?></a></li>-->
						</ul>

					</div>

				</li>

				<li><?php clpr_comments_popup_link( '<span></span> ' . __( 'Comment', RW_CP_TD ), '<span>1</span> ' . __( 'Comment', RW_CP_TD ), __( '<span>%</span> Comments', RW_CP_TD ), 'show-comments' ); // leave spans for ajax to work correctly ?></li>

				<?php clpr_report_coupon( true ); ?>

			</ul>

			<div id="comments-<?php echo $post->ID; ?>" class="comments-list">

				<p class="links">
					<span class="pencil"></span>
					<?php if ( comments_open() ) clpr_comments_popup_link( __( 'Add a comment', RW_CP_TD ), __( 'Add a comment', RW_CP_TD ), __( 'Add a comment', RW_CP_TD ), 'mini-comments' ); else echo '<span class="closed">' . __( 'Comments closed', RW_CP_TD ) . '</span>'; ?>
					<span class="minus"></span>
					<?php clpr_comments_popup_link( __( 'Close comments', RW_CP_TD ), __( 'Close comments', RW_CP_TD ), __( 'Close comments', RW_CP_TD ), 'show-comments' ); ?>
				</p>

				<?php comments_template( '/comments-mini.php' ); ?>

			</div>

			<div class="author vcard">
				<a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php the_author(); ?></a>
			</div>

		</div>

	</div>

</div>

