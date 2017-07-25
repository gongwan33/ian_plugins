<?php
/**
 * Template Name: Coupons Home Template
 *
 * @package Clipper\Templates
 * @author  AppThemes
 * @since   Clipper 1.5
 */

$post_status = ( $clpr_options->exclude_unreliable ) ? array( 'publish' ) : array( 'publish', 'unreliable' );
$posts_count = appthemes_count_posts( APP_POST_TYPE, $post_status );
if ( function_exists( 'ot_get_option' ) ) {
 $c_list_mode = ot_get_option( 'rw_site_view' , 'grid');
 $homenew_coupon = ot_get_option( 'rw_home_newcoupon' , 'on');
 $homepop_coupon = ot_get_option( 'rw_home_popucoupon' , 'on');
 $homefeat_coupon = ot_get_option( 'rw_home_featcoupon' , 'on');
 $homepop_store = ot_get_option( 'rw_home_popustore' , 'on');
 
 $rw_home_popustore_type = ot_get_option( 'rw_home_popustore_type' , 'all');
 $homepop_popustore_no = ot_get_option( 'rw_home_popustore_no' , '20');
 $rw_home_popustore_orderby = ot_get_option( 'rw_home_popustore_orderby' , 'count');
 $rw_home_popustore_order = ot_get_option( 'rw_home_popustore_order' , 'DESC');
  $rw_home_popustore_hide = ot_get_option( 'rw_home_popustore_hide' , 'true');
  $rw_home_popustore_cp_no = ot_get_option( 'rw_home_popustore_cp_no' , 'true');
  if($rw_home_popustore_hide=='true')
  $rw_home_popustore_hide =1;
  else
  $rw_home_popustore_hide =0;
  
   if($rw_home_popustore_cp_no=='true')
  $rw_home_popustore_cp_no =1;
  else
  $rw_home_popustore_cp_no =0;
  
  $rw_ribbon_store_feat = ot_get_option( 'rw_ribbon_store_feat' , 'on');
 $rw_ribbon_store_feat_text = ot_get_option( 'rw_ribbon_store_feat_text' , 'Featured'); 
 $rw_home_page_sidebar = ot_get_option( 'rw_home_page_sidebar', 'right-sidebar');
}
remove_action( 'appthemes_after_endwhile', 'clpr_coupon_pagination' );
$post_type_url = add_query_arg( array( 'paged' => 2 ), get_post_type_archive_link( APP_POST_TYPE ) );
remove_action( 'appthemes_before_loop', 'coups_filters_dropdown' );
remove_action( 'appthemes_before_loop', 'rw_coupons_sorts_dropdown' );
?>

<div id="content">
  <div class="content-box">
    <div class="box-holder">
      <!-- #head --> 
    		 <?php  if(function_exists('coups_filters_dropdown')){ coups_filters_dropdown();}
	       if(function_exists('rw_coupons_sorts_dropdown')){rw_coupons_sorts_dropdown();}
	   ?>  
       
      <div id="coupsTab">
      		<ul class="resp-tabs-list hor_1">
             <?php if($homenew_coupon == 'on'){?>
                <li> <?php _e( 'New Coupons', RW_CP_TD ); ?></li>
                <?php } ?>
                <?php if($homepop_coupon == 'on'){?>
                <li><?php _e( 'Popular Coupons', RW_CP_TD ); ?></li>
                <?php } ?>
                 <?php if($homefeat_coupon == 'on'){?>
                <li>  <?php _e( 'Featured Coupons', RW_CP_TD ); ?></li>
                 <?php } ?>
                 <?php if($homepop_store == 'on'){?>
                <li><?php _e( 'Popular Stores', RW_CP_TD ); ?></li>
                <?php } ?>
            </ul>
      <div class="resp-tabs-container hor_1">
       <?php 
	   // New Coupon Tab start
	   if($homenew_coupon == 'on'){?>
                <div>
                      <?php
				// show all coupons and setup pagination
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'post_type' => APP_POST_TYPE, 'post_status' => $post_status, 'ignore_sticky_posts' => 1, 'paged' => $paged ) );
				$total_pages = max( 1, absint( $wp_query->max_num_pages ) );
			?>
          <div id="listgrid" class="listgrid">
            <p class="listnav"> <a class="switch_list list <?php if($c_list_mode=='list'){echo 'active';}?>" href="javascript:;"><?php _e( 'List View', RW_CP_TD ); ?></a> <a class="switch_grid grid <?php if($c_list_mode=='grid'){echo 'active';}?>" href="javascript:;"><?php _e( 'Grid View', RW_CP_TD ); ?></a> </p>
            <div class="counter"><?php printf( _n( 'There are currently %s active coupon', 'There are currently %s active coupons', $posts_count, RW_CP_TD ), html( 'span', $posts_count ) ); ?></div>
            <div class="view <?php echo $c_list_mode.'view'; ?>">
              <?php require( plugin_dir_path(__FILE__).'loop-coupon.php' ); ?>
              
              <?php
							if ( $total_pages > 1 ) {
						?>
                        <div class="paging"><div class="pages"><a href="<?php echo $post_type_url; ?>" class="page-numbers view_more"> <?php _e( 'VIEW MORE NEW COUPONS', RW_CP_TD ); ?> </a></div></div>
						<?php } ?>
              
              <?php wp_reset_query(); ?>
            </div>
            <!-- view #end --> 
            
          </div>
          <!--listgrid #end --> 
                </div>
                <?php } // New Coupon Tab end
				?>
                
                 <?php
				 // Popular Coupon Tab start
				  if($homepop_coupon == 'on'){?>
                <div>
                                 <?php 
							$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							query_posts( array( 'post_type' => APP_POST_TYPE, 'paged' => $paged, 'ignore_sticky_posts' => 1, 'orderby' => 'comment_count' ) );
							$total_pages = max( 1, absint( $wp_query->max_num_pages ) );?>
         
         <div id="listgrid" class="listgrid">
            <p class="listnav"> <a class="switch_list list <?php if($c_list_mode=='list'){echo 'active';}?>" href="javascript:;"><?php _e( 'List View', RW_CP_TD ); ?></a> <a class="switch_grid grid <?php if($c_list_mode=='grid'){echo 'active';}?>" href="javascript:;"><?php _e( 'Grid View', RW_CP_TD ); ?></a> </p>
            <div class="counter"><?php printf( _n( 'There are currently %s active coupon', 'There are currently %s active coupons', $wp_query->found_posts, RW_CP_TD ), html( 'span', $wp_query->found_posts ) ); ?></div>
            <div class="view <?php echo $c_list_mode.'view'; ?>">
            
              <?php require( plugin_dir_path(__FILE__).'loop-coupon.php' ); ?>
              
               <?php
							if ( $total_pages > 1 ) {
								$popular_url = add_query_arg( array( 'sort' => 'popular' ), $post_type_url );
						?>
								<div class="paging"><div class="pages"><a href="<?php echo $popular_url; ?>" class="page-numbers view_more"> <?php _e( 'VIEW MORE POPULAR COUPONS', RW_CP_TD ); ?> </a></div></div>
						<?php } ?>
              
              
                <?php wp_reset_query(); ?>
            </div>
            <!-- view #end --> 
            
          </div>
                </div>
                 <?php } // Popular Coupon Tab end?>
                
                
                <?php 
				// Featured Coupon Tab Start 
				if($homefeat_coupon == 'on'){?>
                <div>
                     <?php 
							$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							query_posts( array('post_type' => APP_POST_TYPE,'ignore_sticky_posts' => 1,'meta_key' => 'clpr_featured','meta_value' => '1','paged' => $paged) );
							$total_pages = max( 1, absint( $wp_query->max_num_pages ) );?>
        
         <div id="listgrid" class="listgrid">
            <p class="listnav"> <a class="switch_list list <?php if($c_list_mode=='list'){echo 'active';}?>" href="javascript:;"><?php _e( 'List View', RW_CP_TD ); ?></a> <a class="switch_grid grid <?php if($c_list_mode=='grid'){echo 'active';}?>" href="javascript:;"><?php _e( 'Grid View', RW_CP_TD ); ?></a> </p>
            <div class="counter"><?php printf( _n( 'There are currently %s featured coupon', 'There are currently %s featured coupons', $wp_query->found_posts, RW_CP_TD ), html( 'span', $wp_query->found_posts ) ); ?></div>
            <div class="view <?php echo $c_list_mode.'view'; ?>">
          
              <?php require( plugin_dir_path(__FILE__).'loop-coupon.php' ); ?>
              
               <?php
							if ( $total_pages > 1 ) {
								$featured_url = add_query_arg( array( 'sort' => 'featured' ), $post_type_url );
						?>                              
                                <div class="paging"><div class="pages"><a href="<?php echo $featured_url; ?>" class="page-numbers view_more"> <?php _e( 'VIEW MORE FEATURED COUPONS', RW_CP_TD ); ?> </a></div></div>
						<?php } ?>
              
              
                <?php wp_reset_query(); ?>
            </div>
            <!-- view #end --> 
            
          </div>
                </div>
                 <?php }  // Featured Coupon Tab end ?>
                
                 <?php 
				 // Popular Store Tab start
				 if($homepop_store == 'on'){?>
                <div>
                     			<?php
			$hidden_stores = clpr_hidden_stores();
			$featured_stores = clpr_featured_stores();
			$featured_stores = array_diff( $featured_stores, $hidden_stores );
            if ( empty( $homepop_popustore_no ) || ! $number = absint( $homepop_popustore_no ) ) {
            $number = 20;
            }
            $tax_args = array(
            'orderby' => $rw_home_popustore_orderby,
            'order' => $rw_home_popustore_order,
            'hide_empty' => $rw_home_popustore_hide,
            'show_count' => $rw_home_popustore_cp_no,
			'number' => $number,
            'pad_counts' => 0,
            'app_pad_counts' => 1,
            'exclude' => clpr_hidden_stores(),
            );
			
			if($rw_home_popustore_type=='featured'){
			

			$tax_args = array(
				'orderby' => $rw_home_popustore_orderby,
				'order' => $rw_home_popustore_order,
				'hide_empty' => $rw_home_popustore_hide,
				'show_count' => $rw_home_popustore_cp_no,
				'number' => $number,
				'include' => $featured_stores,
			);
			}
		$stores = get_terms( APP_TAX_STORE, $tax_args );
			
			
            $stores = get_terms( APP_TAX_STORE, $tax_args );
            
            $result = '';
            $i = 0;
            
            $result .= '<div class="store-list"><ul>';
            
            if ( $stores && is_array( $stores ) ) {
            
            foreach ( $stores as $store ) {
            if ( $i >= $number ) {
            break;
            }
            $image_url = clpr_get_store_image_url( $store->term_id, 'term_id', 160 );
			//$result .= '<img src="'.$image_url.'" alt="" />';
			$store_feat="";
			 if($rw_ribbon_store_feat=='on'){
				$hidden_stores = clpr_hidden_stores();
			$featured_stores = clpr_featured_stores();
			$featured_stores = array_diff( $featured_stores, $hidden_stores );
			 if(in_array($store->term_id, $featured_stores)){
				 $store_feat='<span class="featured_c">'.$rw_ribbon_store_feat_text.'</span>' ;
			 } }
            $link = get_term_link( $store, APP_TAX_STORE );
			$coupons_text='';
			if($rw_home_popustore_cp_no){
            $coupons_text =' - '.sprintf( _n( '%1$d coupon', '%1$d coupons', $store->count, RW_CP_TD ), $store->count );
			}
            $result .= '<li>' . $store_feat . '<div class="store-img-container"><a  href="' . $link . '"><img src="'.$image_url.'" alt="" /></a></div><p><a  href="' . $link . '">' . $store->name . '</a>' . $coupons_text . '</p></li>' . PHP_EOL;
            $i++;
            }
            
            } else {
            $result .= '<li class="no-results">' . __( 'No stores found.', RW_CP_TD ) . '</li>';
            }
            
            $result .= '</ul></div>';
            
            echo $result;
            ?>
             
                </div>
                
                  <?php }  // Popular Store Tab end ?>
                  
                
            </div>
        </div>    
            
      
    </div>
    <!-- #box-holder --> 
    
  </div>
  <!-- #content-box --> 
  <?php the_ad(19879); ?>
  
</div>
<!-- #container -->
<?php if($rw_home_page_sidebar!='full-width'){ get_sidebar( 'home' );} ?>
