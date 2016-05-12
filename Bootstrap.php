<?php
namespace mimosafa\WP\UI;

class Bootstrap {

	public static function init() {
		static $instance;
		$instance ?: $instance = new self();
	}

	private function __construct() {
		$src = trailingslashit( plugins_url( '/src', __FILE__ ) );
		define( 'MIMOSAFA_UI_JS_URL',  $src . 'js/'  );
		define( 'MIMOSAFA_UI_CSS_URL', $src . 'css/' );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
	}

	public function register_scripts() {
		wp_register_script( 'mimosafa-ui', MIMOSAFA_UI_JS_URL . 'ui.js', [], '', true );
	}

}
