<?php
namespace mimosafa\WP\UI;

class FindUI {

	protected $what;
	protected $q = [];

	public function __construct( $what, $q = [] ) {
		if ( ! in_array( $what, [ 'posts', 'terms' ], true ) ) {
			throw new \Exception( 'Invalid.' );
		}
		Bootstrap::init();
		$this->what = $what;
		$this->q = wp_parse_args( $q );
		add_action( 'admin_init', [ $this, 'init' ] );
	}

	public function __set( $name, $value ) {
		$this->q[$name] = $value;
	}

	public function init() {
		global $pagenow;
		if ( $pagenow !== 'upload.php' ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'admin_footer', [ $this, 'find_div' ] );
			remove_action( 'wp_ajax_find_posts', 'wp_ajax_find_posts', 10 );
		}
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'mimosafa-find-ui', MIMOSAFA_UI_JS_URL . 'find-ui.js', [ 'media', 'mimosafa-ui' ], '', true );
		wp_localize_script( 'mimosafa-find-ui', 'MIMOSAFA_FIND_UI', $this->localized_data() );
	}

	public function find_div() {
		//
	}

	protected function localized_data() {
		$data = [];
		$data['action'] = 'find_' . $this->what;
		//
		return $data;
	}

}
