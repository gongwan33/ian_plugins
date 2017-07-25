<?php
/**
 * Plugin Name: Super Import 
 * Plugin URI: http://www.joybin.cn/wordpress-plugins/super-import.html
 * Description: Easyly importing data.
 * Version: 1.0
 * Author: JoyBin, Inc.
 * Author URI: http://www.joybin.cn/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: super-import
 */

/*  Copyright 2010 - 2016 Wagner Wang  (email : wagner@joybin.cn)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

global $super_import;

if ( ! defined( 'SI_PATH' ) ) {
	define( 'SI_PATH', dirname( __FILE__ ) . '/' );
}

if ( ! defined( 'SI_URL' ) ) {
	define( 'SI_URL', plugin_dir_url( __FILE__ ) );
}


function si_autoloader( $class ) {
	if ( 0 !== strpos( $class, 'SI' ) ) {
		return;
	}

	$file  = dirname( __FILE__ );
	$file .= ( false === strpos( $class, 'Admin' ) ) ? '/includes/' : '/admin/includes/';
	$file .= 'class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';

	if ( file_exists( $file ) ) {
		require_once( $file );
	}
}
spl_autoload_register( 'si_autoloader' );

$super_import = new SI();
add_action( 'plugins_loaded', array( $super_import, 'load_plugin' ) );

