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
class FormTable {

	/**
	 * @var string
	 */
	private $id = '';

	/**
	 * @var array
	 */
	private $table = [];

	/**
	 * @var array
	 */
	private $cache = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param  string $id
	 */
	public function __construct( $id = '' ) {
		if ( $id = self::validIdString( $id ) ) {
			$this->id = $id;
		}
	}

	/**
	 * Render Form Table.
	 *
	 * @access public
	 */
	public function display() {
		$this->_flush_field();
		if ( ! $this->table ) {
			return;
		}
		$tableAttr = $this->id ? ' id="' . esc_attr( $this->id ) . '"' : '';
		$inner = $this->_render_inner(); ?>

<table class="form-table"<?= $tableAttr ?>>
	<tbody>
<?= $inner ?>
	</tbody>
</table>

<?php
	}

	/**
	 * @access public
	 *
	 * @param  string $id
	 * @param  string $label
	 * @param  callable $callback
	 * @param  array $callback_args
	 * @return $this
	 */
	public function field( $id, $label = '', $callback = null, $callback_args = [] ) {
		if ( ! $id = self::validIdString( $id ) ) {
			return;
		}
		$this->_flush_field();
		$this->cache['id'] = $id;
		! $label ?: $this->label( $label );
		! $callback ?: $this->callback( $callback );
		! $callback_args ?: $this->callback_args( $callback_args );
		return $this;
	}

	/**
	 * @access public
	 *
	 * @param  string $label
	 * @return $this
	 */
	public function label( $label ) {
		if ( $this->cache ) {
			$this->cache['label'] = $label;
		}
		return $this;
	}

	/**
	 * @access public
	 *
	 * @param  callable $callback
	 * @return $this
	 */
	public function callback( Callable $callback ) {
		if ( $this->cache ) {
			$this->cache['callback'] = $callback;
		}
		return $this;
	}

	/**
	 * @access public
	 *
	 * @param  array $callback_args
	 * @return $this
	 */
	public function callback_args( Array $callback_args ) {
		if ( $this->cache ) {
			$this->cache['callback_args'] = $callback_args;
		}
		return $this;
	}

	private function _flush_field() {
		if ( $this->cache ) {
			$this->table[] = $this->cache;
			$this->cache = [];
		}
	}

	private function _render_inner() {
		$html = '';
		foreach ( $this->table as $arr ) {
			$html .= "\t\t<tr>\n";
			$html .= "\t\t\t<th>";
			$label = isset( $arr['label'] ) && $arr['label'] ? $arr['label'] : $arr['id'];
			$html .= '<label for="' . esc_attr( $arr['id'] ) . '">' . esc_html( $label ) . '</label>';
			$html .= "</th>\n";
			$html .= "\t\t\t<td>";
			if ( isset( $arr['callback'] ) && is_callable( $arr['callback'] ) ) {
				$args = isset( $arr['callback_args'] ) && $arr['callback_args'] ? $arr['callback_args'] : [];
				$html .= call_user_func( $arr['callback'], $args );
			}
			$html .= "</td>\n";
			$html .= "\t\t</tr>\n";
		}
		return $html;
	}

	/**
	 * @param  mixed
	 * @return string|boolean
	 */
	private static function validIdString( $string ) {
		return is_string( $string ) && $string === esc_attr( $string ) ? $string : false;
	}

}
