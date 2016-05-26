<?php
namespace mimosafa\WP\UI;

class FindUI {

	protected $context;
	protected static $args = [];

	public function __construct( $context, $q = [] ) {
		if ( ! filter_var( $context ) || isset( self::$args[$context] ) ) {
			throw new \Exception( 'Invalid.' );
		}
		Bootstrap::init();
		$this->context = $context;
		self::$args[$context] = wp_parse_args( $q );
		static $done = false;
		if ( ! $done ) {
			add_action( 'admin_init', [ $this, 'init' ] );
			$done = true;
		}
	}

	public function __set( $name, $value ) {
		self::$args[$this->context][$name] = $value;
	}

	public function init() {
		global $pagenow;
		if ( $pagenow !== 'upload.php' ) {
			add_action( 'admin_footer', 'find_posts_div' );
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'mimosafa-find-ui', MIMOSAFA_UI_JS_URL . 'find-ui.js', [ 'media', 'mimosafa-ui' ], '', true );
		wp_localize_script( 'mimosafa-find-ui', 'MIMOSAFA_FIND_UI', self::$args );
	}

}
