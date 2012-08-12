<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



/**
 *	Http utilities.
 *
 *	@package Essence
 */

class Http {

	/**
	 *	
	 */
	
	static function get( $url ) {

		return @file_get_contents( $url );
	}
}
