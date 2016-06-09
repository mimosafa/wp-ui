<?php
namespace mimosafa\WP\UI;

class FormTable {

	private $tableID = '';
	private $tableArray = [];

	private $cache = [];

	public function __construct( $context = '' ) {
		if ( $context = filter_var( $context, \FILTER_SANITIZE_STRING ) ) {
			$this->tableID = $context;
		}
	}

	public function display() {
		$this->_flush_field();
		if ( ! $this->tableArray ) {
			return;
		}
		$tableAttr = $this->tableID ? ' id="' . esc_attr( $this->tableID ) . '"' : '';
		$inner = $this->_render_inner(); ?>

<table class="form-table"<?= $tableAttr ?>>
	<tbody>
	<?= $inner ?>
	</tbody>
</table>

<?php
	}

	public function field( $id, $label = '', $callback = null, $callback_args = [] ) {
		if ( ! $id = filter_var( $id, \FILTER_SANITIZE_STRING ) ) {
			return;
		}
		$this->_flush_field();
		$this->cache['id'] = $id;
		! $label ?: $this->label( $label );
		! $callback ?: $this->callback( $callback );
		! $callback_args ?: $this->callback_args( $callback_args );
		return $this;
	}

	public function label( $label ) {
		if ( $cache =& $this->getCache() ) {
			$cache['label'] = $label;
		}
		return $this;
	}

	public function callback( Callable $callback ) {
		if ( $cache =& $this->getCache() ) {
			$cache['callback'] = $callback;
		}
		return $this;
	}

	public function callback_args( Array $callback_args ) {
		if ( $cache =& $this->getCache() ) {
			$cache['callback_args'] = $callback_args;
		}
		return $this;
	}

	private function &getCache() {
		return $this->cache;
	}

	private function _flush_field() {
		if ( $this->cache ) {
			$this->tableArray[] = $this->cache;
			$this->cache = [];
		}
	}

	private function _render_inner() {
		$html = '';
		foreach ( $this->tableArray as $arr ) {
			$html .= '<tr>';
			$html .= '<th>';
			$label = isset( $arr['label'] ) && $arr['label'] ? $arr['label'] : $arr['id'];
			$html .= '<label for="' . esc_attr( $arr['id'] ) . '">' . esc_html( $label ) . '</label>';
			$html .= '</th>';
			$html .= '<td>';
			if ( isset( $arr['callback'] ) && is_callable( $arr['callback'] ) ) {
				$args = isset( $arr['callback_args'] ) && $arr['callback_args'] ? $arr['callback_args'] : [];
				$html .= call_user_func( $arr['callback'], $args );
			}
			$html .= '</td>';
			$html .= '</tr>';
		}
		return $html;
	}

}
