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
trait Util {

	/**
	 * @param  string $string
	 * @return string
	 */
	protected static function labelize( $string ) {
		return trim( ucwords( str_replace( [ '-', '_' ], ' ', $string ) ) );
	}

	/**
	 * @param  mixed
	 * @return string|boolean
	 */
	private static function isValidIdString( $string ) {
		return $string && is_string( $string ) && $string === esc_attr( $string );
	}

	/**
	 * @param  mixed
	 * @return string|boolean
	 */
	private static function validIdString( $string ) {
		return self::isValidIdString( $string ) ? $string : '';
	}

}
