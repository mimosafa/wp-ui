<?php
namespace mimosafa\WP\UI;

class MetaBox {

	protected $id;
	protected $post_type;

	public function __construct( $id, $args = [] ) {
		if ( ! filter_var( $id ) ) {
			throw new \Exception( 'Invalid.' );
		}
		$this->id = $id;
		add_action( 'add_meta_boxes', [ $this, 'init' ], 10, 2 );
	}

	public function init( $post_type, \WP_Post $post ) {
		if ( isset( $this->post_type ) && $post_type !== $this->post_type ) {
			return;
		}
		//
	}
}
