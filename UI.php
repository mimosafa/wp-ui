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
interface UI {

	/**
	 * @access public
	 * @return void
	 */
	public function init();

	/**
	 * @access public
	 * @return  string
	 */
	public function get_action_tag();

}
