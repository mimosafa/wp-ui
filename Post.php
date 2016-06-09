<?php
namespace mimosafa\WP\UI;

class Post {

	/**
	 * @var string
	 */
	protected $post_type = '';

	protected $meta_boxes        = [];
	protected $before_permalink  = [];
	protected $after_title       = [];
	protected $after_editor      = [];

	public function __construct( $post_type = '' ) {
		if ( $post_type ) {
			$this->post_type = $post_type;
		}
		add_action( 'load-post.php',     [ $this, 'load_post' ] );
		add_action( 'load-post-new.php', [ $this, 'load_post' ] );
	}

	public function add_meta_box( MetaBox $meta_box ) {
		$this->meta_boxes[] = $meta_box;
	}

	public function load_post() {
		if ( $this->typenow() ) {
			add_action( 'edit_form_top', [ $this, '_nonce' ] );
			add_action( $this->post_type ? 'add_meta_boxes_' . $this->post_type : 'add_meta_boxes', [ $this, '_add_meta_boxes' ] );
			add_action( 'edit_form_before_permalink', [ $this, '_before_permalink' ] );
			add_action( 'edit_form_after_title', [ $this, '_after_title' ] );
			add_action( 'edit_form_after_editor', [ $this, '_after_editor' ] );
			add_action( 'save_post', [ $this, '_save_post' ] );
		}
	}

	public function _nonce( \WP_Post $post ) {
		$nonce  = $this->create_nonce_string( $post->ID );
		$action = $this->create_action_string( $post->ID );
		wp_nonce_field( $action, $nonce, false, true );
	}

	public function _add_meta_boxes() {
		if ( ! empty( $this->meta_boxes ) ) {
			foreach ( $this->meta_boxes as $meta_box ) {
				call_user_func( [ $meta_box, 'init' ] );
			}
		}
	}

	public function _before_permalink( \WP_Post $post ) {
		# $this->before_permalink( $post );
	}

	public function _after_title( \WP_Post $post ) {
		# $this->after_title( $post );
	}

	public function _after_editor( \WP_Post $post ) {
		# $this->after_editor( $post );
	}

	public function _save_post( $post_id ) {
		if ( empty( $_POST ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		check_admin_referer( $this->create_action_string( $post_id ), $this->create_nonce_string( $post_id ) );
		$this->save_post( $post_id );
	}

	/**
	 * @access public
	 *
	 * @param  WP_Post $post
	 * @param  bool $postnew
	 */
	public function save_post( $post_id, $postnew ) {
		//
	}

	protected function create_nonce_string( $post_id ) {
		return sprintf( '_%s_%s_nonce', $this->post_type, $post_id );
	}

	protected function create_action_string( $post_id ) {
		return sprintf( 'edit_%s_%s', $this->post_type, $post_id );
	}

	/**
	 * @access protected
	 * @return bool
	 */
	protected function typenow() {
		global $typenow;
		return $typenow === $this->post_type;
	}

}
