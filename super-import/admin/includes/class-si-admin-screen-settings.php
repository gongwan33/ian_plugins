<?php

class SI_Admin_Screen_Settings {
	public function load() {
		self::add_menu_item();
	}

	public function add_menu_item() {
		add_options_page(
			__( 'Super Importer', 'super-import' ),
			__( 'Super Importer', 'super-import' ),
			'manage_options',
			'si',
			array( $this, 'render_screen' )
		);
	}

	public function render_screen() {
		include( SI_PATH . 'admin/views/screen-settings.php' );
	}

}
