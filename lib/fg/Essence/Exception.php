<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	The base exception class of the Essence API.
 *
 *	@package fg.Essence
 */

class Exception extends \Exception {

	/**
	 *	An alias to fit the Essence coding style.
	 *	I'm probably mad.
	 *
	 *	@return string The exception message.
	 */

	public function message( ) {

		return $this->getMessage( );
	}
}
