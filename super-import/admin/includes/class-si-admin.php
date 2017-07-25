<?php

class SI_Admin {
	public function load() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_assets' ), 1 );
	}

	public function admin_menu() {
		$screen = new SI_Admin_Screen_Settings();
		$screen->load();
	}

    public function register_assets() {
		wp_enqueue_style( 'si-admin', SI_URL . 'admin/assets/css/admin.css' );
		wp_enqueue_script( 'si-admin-js', SI_URL . 'admin/js/si-admin.js', array('jquery'));
	}

}
