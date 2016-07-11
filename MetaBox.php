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
class MetaBox implements UI {

	/**
	 * @uses mimosafa\WP\UI\Util
	 */
	use Util;

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * @var string|null
	 */
	protected $screen;

	/**
	 * @var string 'normal'|'advanced'|'side'
	 */
	protected $context = 'advanced';

	/**
	 * @var string 'high'|'core'|'default'|'low'
	 */
	protected $priority = 'default';

	/**
	 * @var array|null
	 */
	protected $callback_args;

	/**
	 * @var string 'new'|'existing'|'both'
	 */
	protected $show_on = 'both';

	/**
	 * Constructor.
	 *
	 * @access public
	 * @uses   mimosafa\WP\UI\Util::isValidIdString()
	 */
	public function __construct( $id = '', $title = '', $callback = '', $screen = null, $context = '', $priority = '', $callback_args = null, $show_on = '' ) {
		foreach ( compact( 'id', 'title', 'callback', 'screen', 'context', 'priority', 'callback_args', 'show_on' ) as $key => $val ) {
			! $val ?: $this->$key( $val );
		}
	}

	/**
	 * @uses mimosafa\WP\UI\Util::labelize()
	 */
	public function init() {
		extract( get_object_vars( $this ) );
		if ( ! self::isValidIdString( $id ) ) {
			return;
		}
		if ( $show_on !== 'both' ) {
			if ( ! in_array( $show_on, [ 'new', 'existing' ], true ) ) {
				return;
			}
			if ( $show_on === 'new' && ! $this->new_post() OR $show_on === 'existing' && $this->new_post() ) {
				return;
			}
		}
		if ( ! $callback ) {
			if ( ! method_exists( $this, 'meta_box_callback' ) ) {
				throw new \Exception( 'Callback is required.' );
			}
			$callback = [ $this, 'meta_box_callback' ];
		}
		$title = $title ?: self::labelize( $id );
		add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
	}

	public function __call( $name, $args ) {
		if ( property_exists( $this, $name ) ) {
			$this->$name = $args[0];
			return $this;
		}
	}

	/**
	 * @uses mimosafa\WP\UI\Util::validIdString()
	 */
	public function id( $id ) {
		if ( $id = self::validIdString( $id ) ) {
			$this->id = $id;
		}
		return $this;
	}

	public function callback( Callable $callback ) {
		$this->callback = $callback;
		return $this;
	}

	public function show_on( $context ) {
		if ( in_array( $context, [ 'new', 'existing', 'both' ], true ) ) {
			$this->show_on = $context;
		}
		return $this;
	}

	/**
	 * @return boolean
	 */
	protected function new_post() {
		global $pagenow;
		if ( $pagenow === 'post-new.php' ) {
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function get_action_tag() {
		$tags = [ 'add_meta_boxes' ];
		if ( $this->screen ) {
			$tags .= 'add_meta_boxes_' . $this->screen;
		}
		foreach ( $tags as $tag ) {
			if ( ! did_action( $tag ) ) {
				return $tag;
			}
		}
		return '';
	}

}
