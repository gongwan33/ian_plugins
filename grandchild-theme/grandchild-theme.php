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

