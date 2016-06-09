<?php
namespace mimosafa\WP\UI;

class MetaBox {

	protected $id;
	protected $title;
	protected $callback;
	protected $screen = null;
	protected $context = 'advanced';
	protected $priority = 'default';
	protected $callback_args = null;

	protected $show_on_new = null;

	public function __construct( $id, $title = '', $callback = '', $screen = null, $context = '', $priority = '', $callback_args = null ) {
		if ( ! $id = filter_var( $id, \FILTER_SANITIZE_STRING ) ) {
			throw new \Exception( 'Invalid.' );
		}
		$this->id = $id;

		! $title    ?: $this->title( $title );
		! $callback ?: $this->callback( $callback );
		! $screen   ?: $this->screen( $screen );
		! $context  ?: $this->context( $context );
		! $priority ?: $this->priority( $priority );
		! $callback_args ?: $this->callback_args( $callback_args );
	}

	public function init() {
		if ( isset( $this->show_on_new ) ) {
			if ( $this->show_on_new && ! $this->new_post() OR ! $this->show_on_new && $this->new_post() ) {
				return;
			}
		}
		if ( ! $this->title ) {
			$this->title = self::labelize( $this->id );
		}
		add_meta_box( $this->id, $this->title, $this->callback, $this->screen, $this->context, $this->priority, $this->callback_args );
	}

	public function __call( $name, $args ) {
		if ( property_exists( $this, $name ) && ! in_array( $name, [ 'id', 'show_on_new' ], true ) ) {
			$this->$name = $args[0];
			return $this;
		}
	}

	public function callback( Callable $callback ) {
		$this->callback = $callback;
		return $this;
	}

	public function show_on( $context ) {
		switch ( $context ) {
			case 'new':
				$this->show_on_new = true;
				break;
			case 'existing':
				$this->show_on_new = false;
				break;
			case 'both':
				$this->show_on_new = null;
				break;
		}
		return $this;
	}

	public function show_on_new() {
		return $this->show_on( 'new' );
	}

	public function show_on_existing() {
		return $this->show_on( 'existing' );
	}

	public function show_on_both() {
		return $this->show_on( 'both' );
	}

	protected function new_post() {
		global $pagenow;
		if ( $pagenow === 'post-new.php' ) {
			return true;
		}
		return false;
	}

	protected static function labelize( $string ) {
		return trim( ucwords( str_replace( [ '-', '_' ], ' ', $string ) ) );
	}

}
