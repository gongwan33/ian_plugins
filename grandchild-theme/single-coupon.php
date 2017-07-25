<?php
/**
 * The Template for displaying all single coupons.
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.0
 */
?>
<?php
if ( function_exists( 'ot_get_option' ) ) {
$detal_countdown = ot_get_option( 'rw_coupondetail_countdown' , 'on');
$rw_ribbon_coupon_new = ot_get_option( 'rw_ribbon_coupon_new' , 'on');
$rw_ribbon_coupon_new_text = ot_get_option( 'rw_ribbon_coupon_new_text' , 'New');


$rw_ribbon_coupon_feat = ot_get_option( 'rw_ribbon_coupon_feat' , 'on');
$rw_ribbon_coupon_feat_text = ot_get_option( 'rw_ribbon_coupon_feat_text' , 'Featured');
$rw_coupon_page_sidebar = ot_get_option( 'rw_coupon_page_sidebar', 'right-sidebar');
}

?>
<div id="content">
  <?php do_action( 'appthemes_notices' ); ?>
  <?php appthemes_before_loop(); ?>
  <?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
  <?php appthemes_stats_update( $post->ID ); //records the page hit ?>
  <?php appthemes_before_post(); ?>
  <div <?php post_class( 'content-box' ); ?> id="post-<?php the_ID(); ?>">
    <div class="box-holder">
      <div class="post_top">
        <p class="store-name"><?php echo get_the_term_list( $post->ID, APP_TAX_STORE, ' ', ', ', '' ); ?></p>
         <?php clpr_vote_box_badge( $post->ID ); ?>
      </div>
      <div class="blog">
         
        <div class="head-box">
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
            <a href="<?php echo clpr_get_first_term_link( $post->ID, APP_TAX_STORE ); ?>"><img src="<?php echo clpr_get_store_image_url( $post->ID, 'post_id', 160 ); ?>" alt="" /></a> </div>
          </div>
          <?php // clpr_vote_box_badge( $post->ID ); ?>
          <div class="coupon-main">
            <?php appthemes_before_post_title(); ?>
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark">
              <?php the_title(); ?>
              </a></h1>
            <?php appthemes_after_post_title(); ?>
            <div class="calendar">
              <ul>
                <li class="category"><?php echo get_the_term_list( $post->ID, APP_TAX_CAT, '', '<span class="sep">, </span>', '' ); ?></i></li>
                <li class="comment">
                  <?php comments_popup_link( __( '0 Comments', RW_CP_TD ), __( '1 Comment', RW_CP_TD ), __( '% Comments', RW_CP_TD ) ); ?>
                </li>
                <li class="create">
                  <time class="entry-date published" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
                </li>
                <li class="modify">
                  <time class="entry-date updated" datetime="<?php echo get_the_modified_date( 'c' ); ?>"><?php echo get_the_modified_date(); ?></time>
                </li>
                <li class="expire">
                  <time class="entry-date expired" datetime="<?php echo clpr_get_expire_date( $post->ID, 'c' ); ?>"><?php echo clpr_get_expire_date( $post->ID, 'display' ); ?></time>
                </li>
              </ul>
              
            </div>
             <?php if($detal_countdown=='on'){ rw_coupon_countdown($post); }?>
           
            <?php clpr_coupon_code_box(); ?>
            <div class="clear"></div>
          </div>
          <!-- #coupon-main --> 
          
        </div>
        <!-- #head-box -->
        
        <div class="text-box">
          <h2>
            <?php _e( 'Coupon Details', RW_CP_TD ); ?>
          </h2>
          <?php appthemes_before_post_content(); ?>
          <?php the_content(); ?>

          <div class="product-list">
	        <?php
          for ($i=1; $i < 10; $i++) { 
              $product_name =  types_render_field("product-name-".$i, array("id"=>$post->ID, "output"=>"raw"));
              if (!empty($product_name)) {
                  $produc_image =  types_render_field("product-image-".$i, array("id"=>$post->ID, "output"=>"raw"));
                  $produc_desc =  types_render_field("product-description-".$i, array("id"=>$post->ID, "output"=>"raw"));
                  $produc_link =  types_render_field("product-link-".$i, array("id"=>$post->ID, "output"=>"raw"));
                  $produc_orig_price =  types_render_field("product-orig-price-".$i, array("id"=>$post->ID, "output"=>"raw"));
                  $produc_price =  types_render_field("product-price-".$i, array("id"=>$post->ID, "output"=>"raw"));
                  ?>
                  <div class="product-list-box">
                    <div class="product-image"><a href="<?php echo $produc_link; ?>" target="_blank"><img src="<?php echo $produc_image; ?>" alt=""/></a></div>
                    <div class="product-price"><?php echo $produc_price; ?><span><?php echo $produc_orig_price; ?></span></div>
                    <div class="product-name"><a href="<?php echo $produc_link; ?>" target="_blank"><?php echo $product_name; ?></a></div>
                    <div class="product-desc"><?php echo $produc_desc; ?></div>
                  </div>
                  <?php       
              }
          }
	        ?>
          </div>	       

          <ul class="single_bottom_links">
            <li>
              <?php clpr_edit_coupon_link(); ?>
            </li>
            <li>
              <?php clpr_reset_coupon_votes_link(); ?>
            </li>
            <li>
              <?php appthemes_after_post_content(); ?>
            </li>
            <li>
              <?php if ( comments_open() ) comments_popup_link( '<span>' . __( 'Leave a comment', RW_CP_TD ) . '</span>', '<span>' . __( 'Leave a comment', RW_CP_TD ) . '</span>', '<span>' . __( 'Leave a comment', RW_CP_TD ) . '</span>', 'leave', '' ); ?>
            </li>
          </ul>
          <div class="navigation"><p><?php previous_post_link(__('上一篇', RW_CP_TD).': %link'); echo '<br>'; next_post_link(__('下一篇', RW_CP_TD).': %link'); echo '<br>';?></p></div>
        </div>


        <div class="text-footer">
          <?php if ( $clpr_options->stats_all && current_theme_supports( 'app-stats' ) ) { ?>
          <div class="stats">
            <?php appthemes_stats_counter( $post->ID ); ?>
          </div>
          <?php } ?>
          <div class="tags">
            <?php _e( 'Tags:', RW_CP_TD ); ?>
            <?php if ( get_the_term_list( $post->ID, APP_TAX_TAG ) ) echo get_the_term_list( $post->ID, APP_TAX_TAG, '', '&nbsp;', '' ); else _e( 'None', RW_CP_TD ); ?>
          </div>
          <div class="author vcard"> <a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
            <?php the_author(); ?>
            </a> </div>
          <div style="float:right;padding-top:6px;"><div class="share_button share_icon_wechat" onclick="wechat_button_click('QRCODE', '<?php echo get_permalink( $post->ID );?>',event)" title="微信" style="display:inline-block;zoom:1.6;"></div><div class="open_social_qrcode" onclick="jQuery(this).hide();"></div><?php wp_social_share();?></div>
          <?php // assemble the text and url we'll pass into each social media share link
								$social_text = urlencode( strip_tags( get_the_title() . ' ' . __( 'coupon from', RW_CP_TD ) . ' ' . get_bloginfo( 'name' ) ) );
								$social_url = urlencode( get_permalink( $post->ID ) );
							?>
          <ul class="social">
            <li><a class="rss" href="<?php echo get_post_comments_feed_link(get_the_ID()); ?>" rel="nofollow">
              <?php _e( 'Coupon Comments RSS', RW_CP_TD ); ?>
              </a></li>
            <li><a class="twitter" href="http://twitter.com/home?status=<?php echo $social_text; ?>+-+<?php echo $social_url; ?>" rel="nofollow" target="_blank">
              <?php _e( 'Twitter', RW_CP_TD ); ?>
              </a></li>
            <li><a class="facebook" href="javascript:void(0);" onclick="window.open('http://www.facebook.com/sharer.php?t=<?php echo $social_text; ?>&amp;u=<?php echo $social_url; ?>','doc', 'width=638,height=500,scrollbars=yes,resizable=auto');" rel="nofollow">
              <?php _e( 'Facebook', RW_CP_TD ); ?>
              </a></li>
            <li><a class="digg" href="http://digg.com/submit?phase=2&amp;url=<?php echo $social_url; ?>&amp;title=<?php echo $social_text; ?>" rel="nofollow" target="_blank">
              <?php _e( 'Digg', RW_CP_TD ); ?>
              </a></li>
          </ul>
          <div class="clear"></div>
        </div>
         
        <script type="text/javascript">
          jQuery('document').ready(function(){
            jQuery(".open_social_box.share_box").prependTo(".text-footer");  
              

          });
        </script>


        
      </div>
      <!-- #blog --> 
      
    </div>
    <!-- #box-holder --> 
    
  </div>
  <!-- #content-box -->



<?php 
    $post = get_queried_object();
    //get coupon meta
    $display_store_banner = types_render_field("display-store-banner", array( "term_id" => $post->ID, "output" => "raw" ) );

  ?>
<?php 
  if ($display_store_banner == 1) { 
    $terms = wp_get_post_terms( $post->ID, "stores" );
    $store = $terms[0];
    $store_id = $store->term_id;

    //get store banner meta
    $store_banner_type = types_render_termmeta("banner-type", array( "term_id" => $store_id, "output" => "raw" ) );
    $store_banner_image_url = types_render_termmeta("banner-image", array( "term_id" => $store_id, "output" => "raw" ) );
    $store_banner_link = types_render_termmeta("banner-link", array( "term_id" => $store_id, "output" => "raw" ) );
    $store_banner_code = types_render_termmeta("banner-code", array( "term_id" => $store_id, "output" => "raw" ) );

  }
  ?>
  <?php if (!empty($store_banner_type)) { ?>
    <div class="single-coupon-banner">
      <?php if ($store_banner_type == 'Code') : ?>
        <?php echo $store_banner_code; ?>
      <?php endif; ?>
      <?php if ($store_banner_type == 'Image') : ?>
        <a href="<?php echo $store_banner_link; ?>" target="_blank">
          <img src="<?php echo $store_banner_image_url; ?>" alt="">
        </a>
      <?php endif; ?>
    </div>
   <?php } ?>

  




  <?php appthemes_after_post(); ?>
  <?php comments_template(); ?>
  <?php the_ad(19879); ?>
  <?php endwhile; ?>
  <?php appthemes_after_endwhile(); ?>
  <?php else: ?>
  <?php appthemes_loop_else(); ?>
  <div class="blog">
    <h3>
      <?php _e( 'Sorry, no coupons yet.', RW_CP_TD ); ?>
    </h3>
  </div>
  <!-- #blog -->
  
  <?php endif; ?>
  <?php appthemes_after_loop(); ?>
</div>
<!-- #content -->

<?php if($rw_coupon_page_sidebar!='full-width'){  get_sidebar( 'coupon' );} ?>

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
