<?php
/*
 * Plugin Name: Grandchild Theme
 * Plugin URI: http://www.joybin.cn/
 * Description: Additional modification of the target theme. Or we may call this grandchild theme.
 * Author: Wagner Wang
 * Version: 0.1
 * Author URI: http://www.joybin.cn/
 * */

// These two lines ensure that your CSS is loaded alongside the parent or child theme's CSS
add_action('wp_head', 'wpc_theme_add_headers', 0);
add_action('init', 'wpc_theme_add_css');


// This filter replaces a complete file from the parent theme or child theme with your file (in this case the archive page).
// Whenever the archive is requested, it will use YOUR archive.php instead of that of the parent or child theme.
add_filter ('single_template', 
	function ($template) { 
		if(substr($template, -17, 17) == 'single-coupon.php') {
			return plugin_dir_path(__FILE__)."single-coupon.php";
		}
		else {
			return plugin_dir_path(__FILE__)."single.php";
		}
	});

add_filter ('frontpage_template', 
	function ($template) { 
		if(substr($template, -14, 14) == 'front-page.php') {
			return plugin_dir_path(__FILE__)."front-page.php";
		} else {
			return $template;
		}
	});

function wpc_theme_add_headers () {
	wp_enqueue_style('grandchild_style');
	wp_enqueue_script('grandchild_js');
	//wp_enqueue_script('os_js');
        wp_enqueue_script('jquery.qrcode', 'http://cdn.bootcss.com/jquery.qrcode/1.0/jquery.qrcode.min.js', array('jquery'));
}

function wpc_theme_add_css() {
	$timestamp = @filemtime(plugin_dir_path(__FILE__).'/style.css');
	$timestampjs = @filemtime(plugin_dir_path(__FILE__).'/grand.js');
	wp_register_style ('grandchild_style', plugins_url('style.css', __FILE__).'', array(), $timestamp);
	wp_register_script ('grandchild_js', plugins_url('grand.js', __FILE__).'', array(), $timestampjs);
	//wp_register_script ('os_js', plugins_url('os.js', __FILE__).'', array(), $timestampjs);
}


function insert_after_post($data, $postarr = null) {
    //$data['post_content'] .= (implode(',', $data).implode(',',$postattr)."");
    $dst_url .= $_POST['clpr_coupon_aff_url'];
    $has_head = preg_replace('/(^http)[\s\S]*/', '\1', $dst_url);
    if(empty($has_head) || $has_head != 'http') {
        $dst_url  = 'http://'.$dst_url;
    }
    
    return $data;
}

//add_filter('wp_insert_post_data', 'insert_after_post');

function gd_query_vars_filter($vars) {
  $vars[] = 'coupon_url';
  return $vars;
}
add_filter( 'query_vars', 'gd_query_vars_filter' );

function get_postid_by_dst_url($url) {
    global $wpdb;
    $sql = $wpdb->prepare('SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key="clpr_coupon_aff_url" AND meta_value=%s', $url);
    $res = $wpdb->get_row($sql);
    //var_dump($sql);
    //var_dump($res);
    return $res->post_id;
}

global $is_url_parsed;
$is_url_parsed = false;

add_action( 'parse_query', function (){
    global $is_url_parsed;
    if($is_url_parsed) {
        return;
    }

    $query = get_query_var('coupon_url');
    if(!empty($query)) {
        $is_url_parsed = true;

        $has_head = preg_replace('/(^http)[\s\S]*/', '\1', $query);
        if(empty($has_head) || $has_head != 'http') {
            $query  = 'http://'.$query;
        }
 
        $post_id = get_postid_by_dst_url($query); 

	if(!empty($post_id)) {
	    $count = get_post_meta( $post_id, 'clpr_coupon_aff_clicks', true );
	    if ( $count ) {
	    $count++;
	    } else {
	    	$count = 1;
	    }

	    update_post_meta( $post_id, 'clpr_coupon_aff_clicks', $count );
	}

        //var_dump($query);
        header('Location: '.$query);
        die();
    }
});
