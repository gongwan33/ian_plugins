 <?php
/**
 * Post loop content template.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.6
 */
?>

<div <?php post_class( 'content-box' ); ?> id="post-<?php the_ID(); ?>">

	<div class="box-holder">

		<div class="blog">

			<?php appthemes_before_blog_post_title(); ?>

			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

			<?php appthemes_after_blog_post_title(); ?>

			<?php appthemes_before_blog_post_content(); ?>

			<div class="text-box">
				
                               
				<?php if ( has_post_thumbnail() ) the_post_thumbnail(); ?>

				<?php the_content( '<p>' . __( 'Continue reading &raquo;', RW_CP_TD ) . '</p>' ); ?>

				
				 <ul class="single_bottom_links">
            		<li><?php edit_post_link( __( 'Edit Post', RW_CP_TD ), '<p class="edit">', '</p>' ); ?></li>
                
                <li><?php if ( comments_open() ) comments_popup_link( ( '<span>' . __( 'Leave a comment', RW_CP_TD ) . '</span>' ), ( '<span>' . __( 'Leave a comment', RW_CP_TD ) . '</span>' ), ( '<span>' . __( 'Leave a comment', RW_CP_TD ) . '</span>' ), 'leave', '' ); ?></li>
                	
                 	
                </ul>
                <div class="navigation"><p><?php previous_post_link(__('上一篇', RW_CP_TD).': %link'); echo '<br>'; next_post_link(__('下一篇', RW_CP_TD).': %link'); echo '<br>';?></p></div>
			</div>
            
            
            
            <div class="text-footer">

		<div class="tags"><?php _e( 'Tags:', RW_CP_TD ); ?> <?php if ( get_the_tags() ) the_tags( ' ', ', ', '' ); else echo ' ' . __( 'None', RW_CP_TD ); ?></div>

		<?php if ( $clpr_options->stats_all && current_theme_supports( 'app-stats' ) ) { ?>
			<div class="stats"><?php appthemes_stats_counter( $post->ID ); ?></div>
		<?php } ?>

		<div class="author vcard">
			<a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php the_author(); ?></a>
		</div>	
                <div style="float:right;padding-top:6px;"><div class="share_button share_icon_wechat" onclick="wechat_button_click('QRCODE', '<?php echo get_permalink( $post->ID );?>',event)" title="微信" style="display:inline-block;zoom:1.6;"></div><div class="open_social_qrcode" onclick="jQuery(this).hide();"></div><?php wp_social_share();?></div>
          <div class="clear"></div>

	</div>
             
            

			<?php // appthemes_after_blog_post_content(); ?>

		</div>

	</div>

</div>

<style>
#weixin-btn div:active {
    background-color: #500;
}
</style>

<script>
function test() {
console.log('test');
}

function shareCoupon() {
    var ins = document.getElementById('wx-guide');
    if(ins.style.display == 'block') {
        ins.style.display = "none";
    } else {
        ins.style.display = "block";
    }
}
</script>

<div id="wx-guide" style="background-color:white;border:solid 1px red;color:red;font-size:large;display:none;position:fixed;top:0;right:0;">点击上方按钮分享&#8593&#8593&#8593</div>
<div id="weixin-btn" style="position:fixed;width:100%;display:none;bottom:0;text-align:center;z-index:10;height:60px;left:0">
<div type="button" onclick="location.href='<?php echo clpr_get_coupon_out_url( $post );?>';" title="直达链接" value="直达链接" style="width:50%;height:100%;color:red;font-size:large;border:solid 2px red;background-color:white;display:inline-block;padding-top:15px;">直达链接</div><!--
--><div type="button" onclick="shareCoupon();" title="分享" value="分享" style="width:50%;height:100%;color:white;font-size:large;border:solid 2px red;background-color:red;display:inline-block;padding-top:15px;">分享</div>
</div>

<script>
function isWeiXin(){ 
        var ua = window.navigator.userAgent.toLowerCase(); 
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){ 
                return true; 
        }else{ 
                return false; 
        } 
} 

if(isWeiXin()) {
    console.log('is ');
    var wbBtn = document.getElementById('weixin-btn');
    wbBtn.style.display = "block";    
} else {
    console.log('not ');
}

</script>
