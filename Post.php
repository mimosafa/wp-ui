<?php

/*
 * This file is part of the mimosafa\wp-ui package.
 *
 * (c) Toshimichi Mimoto <mimosafa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mimosafa\WP\UI;

/**
 * @author Toshimichi Mimoto <mimosafa@gmail.com>
 */
class Post {

	/**
	 * @var string
	 */
	protected $post_type = '';

	/**
	 * @var array { @type mimosafa\WP\UI\UI }
	 */
	protected $uis = [];

	/**
	 * Constructor.
	 *
	 * @param string $post_type
	 */
	public function __construct( $post_type = '' ) {
		if ( ! $this->post_type && $post_type ) {
			$this->post_type = $post_type;
		}
		add_action( 'load-post.php',     [ $this, 'init' ] );
		add_action( 'load-post-new.php', [ $this, 'init' ] );
	}

	public function init() {
		if ( $this->post_type ) {
			global $typenow;
			if ( $typenow !== $this->post_type ) {
				return;
			}
		}
		if ( $this->uis ) {
			foreach ( $this->uis as $array ) {
				$ui   = $array[0];
				$args = wp_parse_args( $array[1] );
				if ( ! is_object( $ui ) ) {
					if ( ! is_string( $ui ) || ! class_exists( $ui ) ) {
						continue;
					}
					$ui = new $ui();
				}
				$this->init_ui( $ui, $args );
			}
		}
	}

	protected function init_ui( UI $ui, Array $args ) {
		if ( $args ) {
			foreach ( $args as $key => $val ) {
				if ( is_string( $key ) ) {
					$ui->$key( $val );
				}
			}
		}
		$tag = $ui->get_action_tag();
		add_action( $tag, [ $ui, 'init' ] );
	}

	/**
	 * @param string|mimosafa\WP\UI\UI $ui
	 * @param array|string             $args
	 */
	public function add( $ui, $args = [] ) {
		$args = wp_parse_args( $args );
		$this->uis[] = [ $ui, $args ];
	}

}
